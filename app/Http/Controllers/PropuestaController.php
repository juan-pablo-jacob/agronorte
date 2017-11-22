<?php

namespace App\Http\Controllers;

use App\Producto;
use App\PropuestaNegocio;
use App\TipoPropuestaNegocio;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;

class PropuestaController extends Controller
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
        //Listado vendedores
        $vendedores = User::where("tipo_usuario_id", 2)
            ->where("is_activo", 1)
            ->get();

        //Listado Tipos de propuesta
        $tipos_propuestas = TipoPropuestaNegocio::all();
        return view('propuesta.new', ["vendedores" => $vendedores, "tipos_propuestas_negocios" => $tipos_propuestas]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->get("fecha") != "") {
            $date = \DateTime::createFromFormat("d/m/Y", $request->get('fecha'));
            $dateFormated = $date->format("Y-m-d");
            $request->merge(["fecha" => $dateFormated]);
        }

        $validator = Validator::make($request->all(), PropuestaNegocio::getRules());
        if(!$validator){
            $errors = $validator->errors();
            return Redirect::back()->withErrors($errors)->withInput();

        }

        $propuesta = PropuestaNegocio::create($request->all());
        if(!$propuesta){
            $errors = new MessageBag(['error' => ['Error. No se pudo crear la propuesta']]);
            return Redirect::back()->withErrors($errors)->withInput();
        }

        return redirect('/propuesta/create?step=2')->with('message', "La propuesta fue creada con éxito");
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
}
