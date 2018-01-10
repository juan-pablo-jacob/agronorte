<?php

namespace App\Http\Controllers;

use App\Cotizacion;
use App\CotizacionIncentivo;
use App\Incentivo;
use App\ParametrosSistema;
use App\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;

class CotizacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     */
    public function index(Request $request)
    {

        $records =
            Cotizacion::select("cotizacion.*", "producto.modelo", "marca.marca", "tipo_producto.tipo_producto")
                ->leftJoin("producto", "producto.id", "=", "cotizacion.producto_id")
                ->leftJoin("marca", "producto.marca_id", "=", "marca.id")
                ->leftJoin("tipo_producto", "producto.tipo_producto_id", "=", "tipo_producto.id")
                ->where("cotizacion.propuesta_negocio_id", $request->get("propuesta_negocio_id"))
                ->where("cotizacion.active", 1)
                ->where("cotizacion.is_toma", $request->get("is_toma"))
                ->orderBy('producto.modelo', 'DESC')
                ->get(200);

        if ($request->get("is_toma") == 1) {
            return view('propuesta.tabla_cotizaciones_toma', ['cotizaciones' => $records]);
        } else {
            return view('propuesta.tabla_cotizaciones', ['cotizaciones' => $records]);
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        $step = is_null($request->get("step")) ? 2 : $request->get("step");
        $parametros_sistema = ParametrosSistema::find(1);

        if (((int)$request->get("tipo_propuesta_negocio_id") == 3 || (int)$request->get("tipo_propuesta_negocio_id") == 4) && (int)$request->get("is_toma") == 1) {
            //Si es toma el producto no va a estar dado de alta en el sistema, entonces tengo que crear el producto
            $validator = Validator::make($request->all(), Producto::getRules($request));

            if ($validator->fails()) {
                $errors = $validator->errors();
                return Redirect::back()->withErrors($errors)->withInput()->with("data", ["step" => $step]);
            }

            $producto = Producto::create($request->all());

            if (!$producto) {
                DB::Rollback();
                $errors = new MessageBag(['error' => ['No se pudo crear el producto']]);
                return Redirect::back()->withErrors($errors)->withInput()->with("data", ["step" => $step]);
            }
            $request->merge(["producto_id" => $producto->id, "precio_lista_producto" => $producto->precio_lista]);
        } else {
            $producto = Producto::find($request->get("producto_id"));
        }
        if ($producto) {
            $request->merge(["costo_basico_producto" => $producto->costo_basico,
                "bonificacion_basica_producto" => $producto->bonificacion_basica,
                "costo_usado_producto" => $producto->costo_usado]);
        }

        if ((int)$request->get("is_toma") != 1) {
            $precio_venta = Cotizacion::getPrecioVenta($request);
            if (!$precio_venta || $precio_venta <= 0) {
                $errors = new MessageBag(['error' => ['Error. Verifique el campo precio de lista y el costo básico del producto']]);
                return Redirect::back()->withErrors($errors)->withInput()->with("data", ["step" => $step]);
            }
            $request->merge(["precio_venta" => $precio_venta]);
            $request->merge(["precio_venta_iva" => $precio_venta * (100 + $parametros_sistema->iva) / 100]);
            $request->merge(["precio_lista_producto_iva" => (float)$request->get("precio_lista_producto") * (100 + $parametros_sistema->iva) / 100]);
        } else {
            $request->merge(["precio_toma_iva" => (float)$request->get("precio_toma") * (100 + $parametros_sistema->iva) / 100]);
        }

        $validator = Validator::make($request->all(), Cotizacion::getRules($request));
        if ($validator->fails()) {
            $errors = $validator->errors();
            DB::Rollback();
            return Redirect::back()->withErrors($errors)->withInput()->with("data", ["step" => $step]);
        }

        //Inserto cotización
        $cotizacion = Cotizacion::create($request->all());
        if (!$cotizacion) {
            $errors = new MessageBag(['error' => ['Error. No se pudo crear la cotización. Verifique los datos']]);
            DB::Rollback();
            return Redirect::back()->withErrors($errors)->withInput()->with("data", ["step" => $step]);
        }

        //Inserto las cotizaciones incentivos
        $array_incentivos = $request->get("incentivos_id");
        if (!is_null($array_incentivos) && count($array_incentivos) > 0) {
            foreach ($array_incentivos as $incentivo_id) {
                $insert = ["incentivo_id" => $incentivo_id, "cotizacion_id" => $cotizacion->id];
                $validator = Validator::make($insert, CotizacionIncentivo::getRules());
                if ($validator->fails()) {
                    $errors = $validator->errors();
                    DB::Rollback();
                    return Redirect::back()->withErrors($errors)->withInput()->with("data", ["step" => $step]);
                }

                //Inserto cotización / incentivo
                $cotizacion_incentivo = CotizacionIncentivo::create($insert);
                if (!$cotizacion_incentivo) {
                    $errors = new MessageBag(['error' => ['Error. Hubo un problema al asociar la cotización con el incentivo seleccionado']]);
                    DB::Rollback();
                    return Redirect::back()->withErrors($errors)->withInput()->with("data", ["step" => $step]);
                }
            }
        }

        //Actualizo todos los campos de cotizaciones
        $rdo_actualizacion = $this->actualizarCamposCostoCotizaciones($cotizacion->propuesta_negocio_id);
        if (!$rdo_actualizacion) {
            $errors = new MessageBag(['error' => ['Error. No se pudieron actualizar los precios de las otras cotizaciones']]);
            DB::Rollback();
            return Redirect::back()->withErrors($errors)->withInput()->with("data", ["step" => $step]);
        }


        DB::commit();
        return redirect()->action(
            'PropuestaController@edit', ['id' => $cotizacion->propuesta_negocio_id]
        )->with('message', "La cotización fue creada con éxito.");
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $step = is_null($request->get("step")) ? 2 : $request->get("step");
        $parametros_sistema = ParametrosSistema::find(1);

        $cotizacion = Cotizacion::find($id);

        //Obtengo el producto de la cotización
        $producto = Producto::find($request->get("producto_id"));
        if ($producto) {
            //Inserto los parámetros que no cambian del producto. Los que pasan a la cotización
            $request->merge(["costo_basico_producto" => $producto->costo_basico,
                "bonificacion_basica_producto" => $producto->bonificacion_basica,
                "costo_usado" => $producto->costo_usado]);
        }

        //Obtengo el precio de la venta, calculo con incentivos, etc
        if ($cotizacion->is_toma != 1) {
            $precio_venta = Cotizacion::getPrecioVenta($request);
            if (!$precio_venta || $precio_venta <= 0) {
                $errors = new MessageBag(['error' => ['Error. Verifique el campo precio de lista y el costo básico del producto']]);
                return Redirect::back()->withErrors($errors)->withInput()->with("data", ["step" => $step]);
            }
            $request->merge(["precio_venta" => $precio_venta]);
            $request->merge(["precio_venta_iva" => $precio_venta * (100 + $parametros_sistema->iva) / 100]);
            $request->merge(["precio_lista_producto_iva" => (float)$request->get("precio_lista_producto") * (100 + $parametros_sistema->iva) / 100]);
        }

        DB::beginTransaction();

        $validator = Validator::make($request->all(), Cotizacion::getRules($request));
        if ($validator->fails()) {
            $errors = $validator->errors();
            DB::Rollback();
            return Redirect::back()->withErrors($errors)->withInput()->with("data", ["step" => $step]);
        }

        //Actualizo cotización
        $cotizacion->fill($request->all());
        $rdo = $cotizacion->save();

        if (!$rdo) {
            $errors = new MessageBag(['error' => ['Error. No se pudo actualizar la cotización. Verifique los datos']]);
            DB::Rollback();
            return Redirect::back()->withErrors($errors)->withInput()->with("data", ["step" => $step]);
        }

        //Inserto las cotizaciones incentivos
        $array_incentivos = $request->get("incentivos_id");
        if (!is_null($array_incentivos) && count($array_incentivos) > 0) {

            foreach ($array_incentivos as $incentivo_id) {

                $cotizacion_incentivo = CotizacionIncentivo::where("incentivo_id", $incentivo_id)
                    ->where("cotizacion_id", $cotizacion->id)
                    ->first();
                //Si no hay cotizacione inserto
                if (!$cotizacion_incentivo) {

                    $insert = ["incentivo_id" => $incentivo_id, "cotizacion_id" => $cotizacion->id];
                    $validator = Validator::make($insert, CotizacionIncentivo::getRules());
                    if ($validator->fails()) {
                        $errors = $validator->errors();
                        DB::Rollback();
                        return Redirect::back()->withErrors($errors)->withInput()->with("data", ["step" => $step]);
                    }


                    //Inserto cotización / incentivo
                    $cotizacion_incentivo = CotizacionIncentivo::create($insert);
                    if (!$cotizacion_incentivo) {
                        $errors = new MessageBag(['error' => ['Error. Hubo un problema al asociar la cotización con el incentivo seleccionado']]);
                        DB::Rollback();
                        return Redirect::back()->withErrors($errors)->withInput()->with("data", ["step" => $step]);
                    }
                }
            }

            $incentivos_a_eliminar = DB::table("cotizacion_incentivo")
                ->where("cotizacion_id", $cotizacion->id)
                ->whereNotIn("incentivo_id", $array_incentivos)
                ->delete();
        } else {
            $incentivos_a_eliminar = DB::table("cotizacion_incentivo")
                ->where("cotizacion_id", $cotizacion->id)
                ->delete();
        }

        //Mando a actualizar los costos de los productos y sus rentabilidadedes de todas las cotizaciones que corresponden a una propuesta de negocio
        $rdo_actualizacion = $this->actualizarCamposCostoCotizaciones($cotizacion->propuesta_negocio_id);
        if (!$rdo_actualizacion) {
            $errors = new MessageBag(['error' => ['Error. No se pudieron actualizar los precios de las otras cotizaciones']]);
            DB::Rollback();
            return Redirect::back()->withErrors($errors)->withInput()->with("data", ["step" => $step]);
        }


        DB::commit();
        return redirect()->action(
            'PropuestaController@edit', ['id' => $cotizacion->propuesta_negocio_id]
        )->with('message', "La cotización fue actualizada con éxito.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        $cotizacion = Cotizacion::find($id);

        $rdo = DB::table('cotizacion')
            ->where('id', $id)
            ->update(['active' => 0]);

        if (!$rdo) {
            $errors = new MessageBag(['error' => ['Error. No se pudo eliminar la cotización']]);
            DB::Rollback();
            return Redirect::back()->withErrors($errors)->withInput();
        }

        //Mando a actualizar los costos de los productos y sus rentabilidadedes de todas las cotizaciones que corresponden a una propuesta de negocio
        $rdo_actualizacion = $this->actualizarCamposCostoCotizaciones($cotizacion->propuesta_negocio_id);
        if (!$rdo_actualizacion) {
            $errors = new MessageBag(['error' => ['Error. No se pudieron actualizar los precios de las otras cotizaciones']]);
            DB::Rollback();
            return Redirect::back()->withErrors($errors)->withInput();
        }

        DB::commit();
        return redirect()->action(
            'PropuestaController@edit', ['id' => $cotizacion->propuesta_negocio_id]
        )->with('message', "Cotización eliminada con éxito.");
    }


    /**
     * Actualización de todas las cotizaciones.
     * @param $propuesta_negocio_id
     * @return bool
     */
    private function actualizarCamposCostoCotizaciones($propuesta_negocio_id)
    {
        //Obtengo todas las cotizaciones de la propuesta y actualizo los campos.
        $cotizaciones = Cotizacion::select("cotizacion.*", "propuesta_negocio.tipo_propuesta_negocio_id")
            ->join("propuesta_negocio", "propuesta_negocio.id", "=", "cotizacion.propuesta_negocio_id")
            ->where("propuesta_negocio_id", $propuesta_negocio_id)
            ->get();

        if (count($cotizaciones) > 0) {
            foreach ($cotizaciones as $cotizacion) {
                $params = $this->setParamsCotizacion($cotizacion);

                if (count($params) > 0) {
                    $cotizacion->fill($params);
                    $rdo = $cotizacion->save();
                    if (!$rdo)
                        return false;
                }
            }
        }
        return true;
    }


    /**
     * Mêtodo privado utilizado para setear los parámetros particulares de una cotización. cuando hay alguna
     * actualización de cotización y sus valores dependen de las otras cotizaciones
     * @param Cotizacion $cotizacion
     * @return array
     */
    private function setParamsCotizacion(Cotizacion $cotizacion)
    {
        $array_devolucion = array();

        /**
         * Productos tomados
         */
        if (((int)$cotizacion->tipo_propuesta_negocio_id == 3 || (int)$cotizacion->tipo_propuesta_negocio_id == 4) && $cotizacion->is_toma == 1) {
            if ((int)$cotizacion->tipo_propuesta_negocio_id == 3) {
                $rdo_ventas = DB::table("cotizacion")
                    ->select(DB::raw("SUM(IFNULL(precio_venta,0)) as cant_precio_venta"), DB::raw("SUM(IFNULL(costo_real_producto, 0)) as cant_costo_real_nuevo"))
                    ->join("producto", "producto.id", "=", "cotizacion.producto_id")
                    ->where("is_toma", 0)
                    ->where("cotizacion.active", 1)
                    ->where("producto.is_nuevo", 1)
                    ->where("propuesta_negocio_id", $cotizacion->propuesta_negocio_id)
                    ->groupBy("propuesta_negocio_id")
                    ->first();
            } else {
                $rdo_ventas = DB::table("cotizacion")
                    ->select(DB::raw("SUM(IFNULL(precio_venta,0)) as cant_precio_venta"), DB::raw("SUM(IFNULL(costo_usado, 0)) as cant_costo_usado"))
                    ->join("producto", "producto.id", "=", "cotizacion.producto_id")
                    ->where("is_toma", 0)
                    ->where("producto.is_nuevo", 0)
                    ->where("active", 1)
                    ->where("propuesta_negocio_id", $cotizacion->propuesta_negocio_id)
                    ->groupBy("propuesta_negocio_id")
                    ->first();
            }

            if ($rdo_ventas) {


                //saco la sumatoria del precio de toma de los usados
                $rdo_toma = DB::table("cotizacion")
                    ->select(DB::raw("SUM(IFNULL(precio_toma, 0)) as cant_precio_toma"))
                    ->where("active", 1)
                    ->where("is_toma", 1)
                    ->where("propuesta_negocio_id", $cotizacion->propuesta_negocio_id)
                    ->where("cotizacion.id", "<>", (int)$cotizacion->id)
                    ->groupBy("propuesta_negocio_id")
                    ->first();


                //Tomo el precio de toma del producto cargado por el usuario y le sumo los otros productos si es que hay
                $precio_toma = $cotizacion->precio_toma;
                if ($rdo_toma) {
                    $precio_toma += (float)$rdo_toma->cant_precio_toma;
                }

                //Obtengo la diferencia a pagar por el cliente
                $a_pagar_por_cliente = (float)$rdo_ventas->cant_precio_venta - $precio_toma;

                if ((int)$cotizacion->tipo_propuesta_negocio_id == 3) {
                    $costo_usado = (float)$rdo_ventas->cant_costo_real_nuevo / 0.85 - $a_pagar_por_cliente;
                } else {
                    $costo_usado = (float)$rdo_ventas->cant_costo_usado - $a_pagar_por_cliente;
                }

                //Obtengo el porcentaje que representa el costo del usado sobre el total de los costos de usados
                $porcentaje_costo_usado = (float)$cotizacion->precio_toma * 100 / $precio_toma;

                $array_devolucion["costo_real_producto"] = $costo_usado * $porcentaje_costo_usado / 100;

            } else {
                $array_devolucion["costo_real_producto"] = null;
            }
        } /**
         * Productos Vendidos
         */
        elseif ($cotizacion->is_toma == 0) {

            $producto = Producto::find($cotizacion->producto_id);
            if ($producto) {
                if ($producto->is_nuevo == 0) {
                    /**
                     * Calculo Venta productos usados
                     */
                    $costo_real_producto = (float)$cotizacion->costo_usado_producto;

                    if (!is_null($costo_real_producto) && $costo_real_producto > 0) {

                        $array_devolucion["costo_real_producto"] = $costo_real_producto;

                        //Cálculos rentabilidad Vs. Precio venta
                        $ganancia = (float)$cotizacion->precio_venta - $costo_real_producto;
                        $array_devolucion["rentabilidad_vs_costo_real"] = $ganancia * 100 / $costo_real_producto;
                        $array_devolucion["rentabilidad_vs_precio_venta"] = $ganancia * 100 / $cotizacion->precio_venta;
                    }
                } else {
                    /**
                     *Calculo Venta producto nuevos
                     */
                    //Cálculo costo real
                    $costo_real_producto = (float)$cotizacion->costo_basico_producto;

                    $cotizaciones_incentivos = DB::table("cotizacion_incentivo")
                        ->where("cotizacion_id", $cotizacion->id)
                        ->get();

                    if ($cotizaciones_incentivos) {
                        $porcentaje_incentivo = 0;
                        foreach ($cotizaciones_incentivos as $value) {
                            $incentivo = Incentivo::find($value->incentivo_id);
                            $porcentaje_incentivo += (float)$incentivo->porcentaje;
                        }
                        $costo_real_producto -= ($porcentaje_incentivo) * (float)$cotizacion->costo_basico_producto / 100;
                    }

                    $array_devolucion["costo_real_producto"] = $costo_real_producto;
                    //Cálculos rentabilidad Vs. Precio venta
                    $ganancia = (float)$cotizacion->precio_venta - $costo_real_producto;
                    $array_devolucion["rentabilidad_vs_costo_real"] = $ganancia * 100 / $costo_real_producto;
                    $array_devolucion["rentabilidad_vs_precio_venta"] = $ganancia * 100 / $cotizacion->precio_venta;

                }
            }
        }


        return $array_devolucion;
    }

    /**
     * Mètodo que retorna la cotización en formato tabla
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getDatosCotizacionesTabla(Request $request)
    {
        $records =
            Cotizacion::select("cotizacion.*", "producto.modelo", "marca.marca", "tipo_producto.tipo_producto")
                ->leftJoin("producto", "producto.id", "=", "cotizacion.producto_id")
                ->leftJoin("marca", "producto.marca_id", "=", "marca.id")
                ->leftJoin("tipo_producto", "producto.tipo_producto_id", "=", "tipo_producto.id")
                ->where("cotizacion.propuesta_negocio_id", $request->get("propuesta_negocio_id"))
                ->where("cotizacion.active", 1)
//                ->where("cotizacion.is_toma", $request->get("is_toma"))
                ->orderBy('producto.modelo', 'DESC')
                ->get(200);

        return view('propuesta.tabla_visualizacion_cotizaciones_step_4', ['cotizaciones' => $records]);
    }


    /**
     * Cron utilizado para actualizar todos los costos reales de los productos
     */
    public function cronActualizarCostoRealProductos()
    {
        //Obtengo todos los productos de las cotizaciones activas y que estèn abiertas o en negociaciòn
        $productos = DB::table("cotizacion")
            ->select("producto.*", DB::raw("cotizacion.id as cotizacion_id"))
            ->join("producto", "producto.id", "=", "cotizacion.producto_id")
            ->join("propuesta_negocio", "propuesta_negocio.id", "=", "cotizacion.propuesta_negocio_id")
            ->where("cotizacion.active", 1)
            ->where("cotizacion.is_toma", 0)
            ->where("producto.is_nuevo", 1)
            ->whereIn("propuesta_negocio.estados", [1, 2])
            ->where("producto.active", 1)
            ->get();

        if ($productos && count($productos) > 0) {
            foreach ($productos as $producto) {
                $update = ["costo_real_producto" => (float)$producto->costo_basico];

                //Tengo que traer todas los incentivos vigentes del producto perteneciente al incentivo
                $incentivos_vigentes = DB::table("incentivo_producto")
                    ->select("incentivo_producto.id", "incentivo.porcentaje")
                    ->join("incentivo", "incentivo.id", "=", "incentivo_producto.incentivo_id")
                    ->whereDate('incentivo.fecha_caducidad', '>=', date("Y-m-d"))
                    ->where("incentivo_producto.producto_id", $producto->id)
                    ->get();

                if ($incentivos_vigentes) {
                    $porcentaje_incentivo = 0;
                    foreach ($incentivos_vigentes as $value) {
                        $porcentaje_incentivo += (float)$value->porcentaje;
                    }
                    $update["costo_real_producto"] -= ($porcentaje_incentivo) * (float)$producto->costo_basico / 100;
                }

                $cotizacion = Cotizacion::find($producto->cotizacion_id);

                if ($cotizacion) {
                    $cotizacion->fill($update);
                    $rdo = $cotizacion->save();
                }
            }
        }
    }
}
