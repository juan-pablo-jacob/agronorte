<?php

namespace App\Http\Controllers;

use App\Cotizacion;
use App\CotizacionIncentivo;
use App\Incentivo;
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

        if (((int)$request->get("tipo_propuesta_negocio_id") == 3 || (int)$request->get("tipo_propuesta_negocio_id") == 4) && (int)$request->get("is_toma") == 1) {
            //Si es toma el producto no va a estar dado de alta en el sistema, entonces tengo que crear el producto
            $validator = Validator::make($request->all(), Producto::getRules());

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
                "costo_usado" => $producto->costo_usado]);
        }

        if ((int)$request->get("is_toma") != 1) {
            $precio_venta = Cotizacion::getPrecioVenta($request);
            if (!$precio_venta || $precio_venta <= 0) {
                $errors = new MessageBag(['error' => ['Error. Verifique el campo precio de lista y el costo básico del producto']]);
                return Redirect::back()->withErrors($errors)->withInput()->with("data", ["step" => $step]);
            }
            $request->merge(["precio_venta" => $precio_venta]);
        }

        //Seteo los parámetros de la cotización de acuerdo al tipo de propuesta de negocio
        $this->setParamsRequest($request);


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

        //Como los productos tomados requieren de cálculo del total se realiza esto para que se actualicen todos los campos

        if ($request->get("tipo_propuesta_negocio_id") == 3 || $request->get("tipo_propuesta_negocio_id") == 4) {
            $rdo_actualizacion = $this->actualizarCamposCostoCotizaciones($cotizacion->propuesta_negocio_id);
            if (!$rdo_actualizacion) {
                $errors = new MessageBag(['error' => ['Error. No se pudieron actualizar los precios de las otras cotizaciones']]);
                DB::Rollback();
                return Redirect::back()->withErrors($errors)->withInput()->with("data", ["step" => $step]);
            }
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
        $precio_venta = Cotizacion::getPrecioVenta($request);
        if (!$precio_venta || $precio_venta <= 0) {
            $errors = new MessageBag(['error' => ['Error. Verifique el campo precio de lista y el costo básico del producto']]);
            return Redirect::back()->withErrors($errors)->withInput()->with("data", ["step" => $step]);
        }
        $request->merge(["precio_venta" => $precio_venta]);

        //Seteo los parámetros de la cotización de acuerdo al tipo de propuesta de negocio
        $this->setParamsRequest($request);

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
                ->whereNotIn("incentivo_id", $array_incentivos)
                ->delete();
        }

        if ($request->get("tipo_propuesta_negocio_id") == 3 || $request->get("tipo_propuesta_negocio_id") == 4) {
            $rdo_actualizacion = $this->actualizarCamposCostoCotizaciones($cotizacion->propuesta_negocio_id);
            if (!$rdo_actualizacion) {
                $errors = new MessageBag(['error' => ['Error. No se pudieron actualizar los precios de las otras cotizaciones']]);
                DB::Rollback();
                return Redirect::back()->withErrors($errors)->withInput()->with("data", ["step" => $step]);
            }
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
        $cotizacion = Cotizacion::find($id);

        $rdo = DB::table('cotizacion')
            ->where('id', $id)
            ->update(['active' => 0]);

        if (!$rdo) {
            $errors = new MessageBag(['error' => ['Error. No se pudo eliminar la cotización']]);
            DB::Rollback();
            return Redirect::back()->withErrors($errors)->withInput();
        }

        return redirect()->action(
            'PropuestaController@edit', ['id' => $cotizacion->propuesta_negocio_id]
        )->with('message', "Cotización eliminada con éxito.");
    }


    /**
     * Mêtodo privado utilizado para setear los parámetros particulares de cada cotización que dependen
     * del tipo de propuesta que sea
     * @param Request $request
     */
    private function setParamsRequest(Request &$request)
    {
        switch ((int)$request->get("tipo_propuesta_negocio_id")) {
            case 1:
                //Cálculo costo real
                $costo_real_producto = Producto::getCostoRealNuevo($request);
                $request->merge(["costo_real_producto" => $costo_real_producto]);
                //Cálculos rentabilidad Vs. Precio venta
                $ganancia = (float)$request->get("precio_venta") - $costo_real_producto;

                $request->merge(["rentabilidad_vs_precio_venta" => number_format($ganancia * 100 / $request->get("precio_venta"), 2)]);
                $request->merge(["rentabilidad_vs_costo_real" => number_format($ganancia * 100 / $costo_real_producto, 2)]);
                break;
            case 2:
                $costo_real_producto = Producto::getCostoRealUsado($request);

                if (!is_null($costo_real_producto)) {

                    $request->merge(["costo_real_producto" => $costo_real_producto]);

                    //Cálculos rentabilidad Vs. Precio venta
                    $ganancia = (float)$request->get("precio_venta") - $costo_real_producto;
                    $request->merge(["rentabilidad_vs_costo_real" => number_format($ganancia * 100 / $costo_real_producto, 2)]);
                    $request->merge(["rentabilidad_vs_precio_venta" => number_format($ganancia * 100 / $request->get("precio_venta"), 2)]);
                }

                break;
            case 3:
                if ($request->get("is_toma")) {
                    //saco la sumatoria de las ventas de productos nuevos
                    $rdo_ventas = DB::table("cotizacion")
                        ->select(DB::raw("SUM(IFNULL(precio_venta,0)) as cant_precio_venta"), DB::raw("SUM(IFNULL(costo_real_producto, 0)) as cant_costo_real_nuevo"))
                        ->where("is_toma", 0)
                        ->where("active", 1)
                        ->where("propuesta_negocio_id", $request->get("propuesta_negocio_id"))
                        ->groupBy("propuesta_negocio_id")
                        ->first();

                    //saco la sumatoria del precio de toma de los usados
                    $query_toma = DB::table("cotizacion")
                        ->select(DB::raw("SUM(IFNULL(precio_toma, 0)) as cant_precio_toma"))
                        ->where("active", 1)
                        ->where("is_toma", 1)
                        ->where("propuesta_negocio_id", $request->get("propuesta_negocio_id"));

                    //Si viene cotizacion_id, es porque se está editando el producto. no lo tengo que incluir en la consulta.
                    if ((int)$request->get("cotizacion_id") > 0) {
                        $query_toma->where("cotizacion.id", "<>", (int)$request->get("cotizacion_id"));
                    }
                    $rdo_toma = $query_toma
                        ->groupBy("propuesta_negocio_id")
                        ->first();

                    //Tomo el precio de toma del producto cargado por el usuario y le sumo los otros productos si es que hay
                    $precio_toma = $request->get("precio_toma");
                    if ($rdo_toma) {
                        $precio_toma += (float)$rdo_toma->cant_precio_toma;
                    }

                    //Obtengo la diferencia a pagar por el cliente
                    $a_pagar_por_cliente = (float)$rdo_ventas->cant_precio_venta - $precio_toma;

                    //Sumatoria de todos los costos reales de los productos nuevos que hay.
                    $costo_usado = (float)$rdo_ventas->cant_costo_real_nuevo / 0.85 - $a_pagar_por_cliente;
                    //Costo real del producto
                    $request->merge(["costo_real_producto" => number_format($costo_usado, 2)]);


                } else {
                    //Cálculo costo real
                    $costo_real_producto = Producto::getCostoRealNuevo($request);
                    $request->merge(["costo_real_producto" => $costo_real_producto]);
                    //Cálculos rentabilidad Vs. Precio venta
                    $ganancia = (float)$request->get("precio_venta") - $costo_real_producto;

                    $request->merge(["rentabilidad_vs_precio_venta" => number_format($ganancia * 100 / $request->get("precio_venta"), 2)]);
                    $request->merge(["rentabilidad_vs_costo_real" => number_format($ganancia * 100 / $costo_real_producto, 2)]);
                }
                break;
            case 4:

                if ($request->get("is_toma")) {

                } else {
                    $costo_real_producto = Producto::getCostoRealUsado($request);

                    if (!is_null($costo_real_producto)) {

                        $request->merge(["costo_real_producto" => $costo_real_producto]);

                        //Cálculos rentabilidad Vs. Precio venta
                        $ganancia = (float)$request->get("precio_venta") - $costo_real_producto;
                        $request->merge(["rentabilidad_vs_costo_real" => number_format($ganancia * 100 / $costo_real_producto, 2)]);
                        $request->merge(["rentabilidad_vs_precio_venta" => number_format($ganancia * 100 / $request->get("precio_venta"), 2)]);
                    }
                }
                break;
            default:
                break;
        }
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
        switch ((int)$cotizacion->tipo_propuesta_negocio_id) {

            case 3:
                if ($cotizacion->is_toma == 1) {
                    //saco la sumatoria de las ventas de productos nuevos
                    $rdo_ventas = DB::table("cotizacion")
                        ->select(DB::raw("SUM(IFNULL(precio_venta,0)) as cant_precio_venta"), DB::raw("SUM(IFNULL(costo_real_producto, 0)) as cant_costo_real_nuevo"))
                        ->where("is_toma", 0)
                        ->where("propuesta_negocio_id", $cotizacion->propuesta_negocio_id)
                        ->groupBy("propuesta_negocio_id")
                        ->first();

                    //saco la sumatoria del precio de toma de los usados
                    $query_toma = DB::table("cotizacion")
                        ->select(DB::raw("SUM(IFNULL(precio_toma, 0)) as cant_precio_toma"))
                        ->where("is_toma", 1)
                        ->where("propuesta_negocio_id", $cotizacion->propuesta_negocio_id);

                    //Si viene cotizacion_id, es porque se está editando el producto. no lo tengo que incluir en la consulta.
                    $query_toma->where("cotizacion.id", "<>", (int)$cotizacion->id);

                    $rdo_toma = $query_toma
                        ->groupBy("propuesta_negocio_id")
                        ->first();

                    //Tomo el precio de toma del producto cargado por el usuario y le sumo los otros productos si es que hay
                    $precio_toma = $cotizacion->precio_toma;
                    if ($rdo_toma) {
                        $precio_toma += (float)$rdo_toma->cant_precio_toma;
                    }

                    //Obtengo la diferencia a pagar por el cliente
                    $a_pagar_por_cliente = (float)$rdo_ventas->cant_precio_venta - $precio_toma;
                    //Sumatoria de todos los costos reales de los productos nuevos que hay.
                    $costo_usado = (float)$rdo_ventas->cant_costo_real_nuevo / 0.85 - $a_pagar_por_cliente;
                    //Costo real del producto
                    $array_devolucion["costo_real_producto"] = number_format($costo_usado, 2);

                }
                break;
            case 4:
                break;
            default:
                break;
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
}
