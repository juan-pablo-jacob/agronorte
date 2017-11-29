<?php

namespace App\Http\Controllers;

use App\MailPropuestaNegocio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;

class MailPropuestaNegocioController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
    }


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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        $validator = Validator::make($request->all(), MailPropuestaNegocio::getRules());

        if ($validator->fails()) {
            $errors = $validator->errors();
            if ($request->ajax()) {
                return response()->json([
                    "result" => false,
                    "errors" => $errors
                ]);
            }
        }

        //Creo la marca
        MailPropuestaNegocio::create($request->all());

        return app('App\Http\Controllers\PropuestaController')->editWithParams($request->get("propuesta_negocio_id"), ["step" => 4, "mensaje" => "Se creó el mail para la propuesta de negocio, puede enviarlo"]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $mail_propuesta_negocio = MailPropuestaNegocio::find($id);

        $validator = Validator::make($request->all(), MailPropuestaNegocio::getRules());

        if ($validator->fails()) {
            $errors = new MessageBag(['error' => ['Error. No se pudo editar la información a enviar al mail']]);
            return Redirect::back()->withErrors($errors)->withInput();
        }

        //Creo la marca
        $mail_propuesta_negocio->fill($request->all());
        $mail_propuesta_negocio->save();

        return app('App\Http\Controllers\PropuestaController')->editWithParams($request->get("propuesta_negocio_id"), ["step" => 4, "mensaje" => "Se modificaron los datos para el mail de la propuesta de negocio, puede enviarlo"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
