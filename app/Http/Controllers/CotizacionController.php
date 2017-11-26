<?php

namespace App\Http\Controllers;

use App\Cotizacion;
use App\CotizacionIncentivo;
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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
            $request->merge(["costo_basico_producto" => $producto->costo_basico]);
        }

        $precio_venta = Cotizacion::getPrecioVenta($request);
        if (!$precio_venta) {
            $errors = new MessageBag(['error' => ['Error. Verifique el campo precio de lista y el costo básico del producto']]);
            return Redirect::back()->withErrors($errors)->withInput();
        }


        //Seteo los parámetros de la cotización de acuerdo al tipo de propuesta de negocio
        $this->setParamsRequest($request);

        DB::beginTransaction();

        $validator = Validator::make($request->all(), Cotizacion::getRules());
        if($validator->fails()){
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
        foreach ($array_incentivos as $incentivo_id) {
            $insert =["incentivo_id" => $incentivo_id, "cotizacion_id" => $cotizacion->id];
            $validator = Validator::make($insert, CotizacionIncentivo::getRules());
            if($validator->fails()){
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    private function setParamsRequest(Request &$request)
    {
        switch ((int)$request->get("tipo_propuesta_negocio_id")) {
            case 1:
                $costo_real_producto = Producto::getCostoRealNuevo($request);
                $request->merge(["costo_real_producto" => $costo_real_producto]);
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
}
