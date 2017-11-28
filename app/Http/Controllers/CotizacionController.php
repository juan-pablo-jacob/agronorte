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
            Cotizacion::select("cotizacion.*", "producto.modelo")
                ->leftJoin("producto", "producto.id", "=", "cotizacion.producto_id")
                ->where("cotizacion.propuesta_negocio_id", $request->get("propuesta_negocio_id"))
                ->where("cotizacion.active", 1)
                ->where("cotizacion.is_toma", $request->get("is_toma"))
                ->orderBy('producto.modelo', 'DESC')
                ->get(200);

        return view('propuesta.tabla_cotizaciones', ['cotizaciones' => $records]);
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
        //Parseo a date la fecha de entrega
        if ($request->get("fecha_entrega") != "") {
            $date = \DateTime::createFromFormat("d/m/Y", $request->get('fecha_entrega'));
            $dateFormated = $date->format("Y-m-d");
            $request->merge(["fecha_entrega" => $dateFormated]);
        }

        $producto = Producto::find($request->get("producto_id"));
        if ($producto) {
            $request->merge(["costo_basico_producto" => $producto->costo_basico,
                "bonificacion_basica_producto" => $producto->bonificacion_basica]);
        }

        $precio_venta = Cotizacion::getPrecioVenta($request);
        if (!$precio_venta || $precio_venta <= 0) {
            $errors = new MessageBag(['error' => ['Error. Verifique el campo precio de lista y el costo básico del producto']]);
            return Redirect::back()->withErrors($errors)->withInput();
        }
        $request->merge(["precio_venta" => $precio_venta]);

        //Seteo los parámetros de la cotización de acuerdo al tipo de propuesta de negocio
        $this->setParamsRequest($request);

        DB::beginTransaction();

        $validator = Validator::make($request->all(), Cotizacion::getRules());
        if ($validator->fails()) {
            $errors = $validator->errors();
            DB::Rollback();
            return Redirect::back()->withErrors($errors)->withInput();
        }

        //Inserto cotización
        $cotizacion = Cotizacion::create($request->all());
        if (!$cotizacion) {
            $errors = new MessageBag(['error' => ['Error. No se pudo crear la cotización. Verifique los datos']]);
            DB::Rollback();
            return Redirect::back()->withErrors($errors)->withInput();
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
                    return Redirect::back()->withErrors($errors)->withInput();
                }

                //Inserto cotización / incentivo
                $cotizacion_incentivo = CotizacionIncentivo::create($insert);
                if (!$cotizacion_incentivo) {
                    $errors = new MessageBag(['error' => ['Error. Hubo un problema al asociar la cotización con el incentivo seleccionado']]);
                    DB::Rollback();
                    return Redirect::back()->withErrors($errors)->withInput();
                }
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

        $cotizacion = Cotizacion::find($id);

        //Parseo a date la fecha de entrega
        if ($request->get("fecha_entrega") != "") {
            $date = \DateTime::createFromFormat("d/m/Y", $request->get('fecha_entrega'));
            $dateFormated = $date->format("Y-m-d");
            $request->merge(["fecha_entrega" => $dateFormated]);
        }

        $producto = Producto::find($request->get("producto_id"));
        if ($producto) {
            $request->merge(["costo_basico_producto" => $producto->costo_basico,
                "bonificacion_basica_producto" => $producto->bonificacion_basica]);
        }

        $precio_venta = Cotizacion::getPrecioVenta($request);
        if (!$precio_venta || $precio_venta <= 0) {
            $errors = new MessageBag(['error' => ['Error. Verifique el campo precio de lista y el costo básico del producto']]);
            return Redirect::back()->withErrors($errors)->withInput();
        }
        $request->merge(["precio_venta" => $precio_venta]);

        //Seteo los parámetros de la cotización de acuerdo al tipo de propuesta de negocio
        $this->setParamsRequest($request);

        DB::beginTransaction();

        $validator = Validator::make($request->all(), Cotizacion::getRules());
        if ($validator->fails()) {
            $errors = $validator->errors();
            DB::Rollback();
            return Redirect::back()->withErrors($errors)->withInput();
        }

        //Actualizo cotización
        $cotizacion->fill($request->all());
        $rdo = $cotizacion->save();

        if (!$rdo) {
            $errors = new MessageBag(['error' => ['Error. No se pudo actualizar la cotización. Verifique los datos']]);
            DB::Rollback();
            return Redirect::back()->withErrors($errors)->withInput();
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
                        return Redirect::back()->withErrors($errors)->withInput();
                    }


                    //Inserto cotización / incentivo
                    $cotizacion_incentivo = CotizacionIncentivo::create($insert);
                    if (!$cotizacion_incentivo) {
                        $errors = new MessageBag(['error' => ['Error. Hubo un problema al asociar la cotización con el incentivo seleccionado']]);
                        DB::Rollback();
                        return Redirect::back()->withErrors($errors)->withInput();
                    }
                }
            }
        }

        $incentivos_a_eliminar = DB::table("cotizacion_incentivo")
            ->whereNotIn("incentivo_id", $array_incentivos)
            ->delete();

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
                break;
            case 3:
                break;
            case 4:
                break;
            default:
                break;
        }
    }


    /**
     * Mètodo que retorna la cotización en formato tabla
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getDatosCotizacionesTabla(Request $request)
    {
        $records =
            Cotizacion::select("cotizacion.*", "producto.modelo")
                ->leftJoin("producto", "producto.id", "=", "cotizacion.producto_id")
                ->where("cotizacion.propuesta_negocio_id", $request->get("propuesta_negocio_id"))
                ->where("cotizacion.active", 1)
                ->where("cotizacion.is_toma", $request->get("is_toma"))
                ->orderBy('producto.modelo', 'DESC')
                ->get(200);

        return view('propuesta.tabla_visualizacion_cotizaciones_step_4', ['cotizaciones' => $records]);
    }
}
