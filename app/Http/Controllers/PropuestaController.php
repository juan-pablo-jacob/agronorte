<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\Producto;
use App\PropuestaNegocio;
use App\TipoPropuestaNegocio;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $propuestas =
            PropuestaNegocio::TipoPropuestaNegocio($request->get('tipo_propuesta_negocio_id'))
                ->vendedor($request->get('users_id'))
                ->select("propuesta_negocio.*",
                    "tipo_propuesta_negocio.tipo_propuesta_negocio",
                    "users.nombre", "users.apellido", "cliente.razon_social")
                ->join("tipo_propuesta_negocio", "propuesta_negocio.tipo_propuesta_negocio_id", "=", "tipo_propuesta_negocio.id")
                ->join("users", "propuesta_negocio.users_id", "=", "users.id")
                ->join("cliente", "propuesta_negocio.cliente_id", "=", "cliente.id")
                ->where("propuesta_negocio.active", 1)
                ->orderBy('propuesta_negocio.fecha', 'DESC')
                ->paginate(200);

        $vendedores = User::where("tipo_usuario_id", 2)
            ->where("is_activo", 1)
            ->get();

        //Listado Tipos de propuesta
        $tipos_propuestas = TipoPropuestaNegocio::all();

        return view('propuesta.list', ["propuestas" => $propuestas, "request" => $request, "vendedores" => $vendedores, "tipo_propuestas" => $tipos_propuestas]);
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

        if ($validator->fails()) {
            $errors = $validator->errors();
            return Redirect::back()->withErrors($errors)->withInput();
        }

        $propuesta = PropuestaNegocio::create($request->all());
        if (!$propuesta) {
            $errors = new MessageBag(['error' => ['Error. No se pudo crear la propuesta']]);
            return Redirect::back()->withErrors($errors)->withInput();
        }

        //return redirect('/propuesta/create?step=2')->with('message', "La propuesta fue creada con éxito");
        return redirect()->action(
            'PropuestaController@edit', ['id' => $propuesta->id]
        )->with('message', "La propuesta fue creada con éxito");
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
        $array_response = $this->getEditData($id);

        $array_response["step"] = 2;

        return view('propuesta/edit', $array_response);
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
        $propuesta = PropuestaNegocio::find($id);

        if ($request->get("fecha") != "") {
            $date = \DateTime::createFromFormat("d/m/Y", $request->get('fecha'));
            $dateFormated = $date->format("Y-m-d");
            $request->merge(["fecha" => $dateFormated]);
        }

        $validator = Validator::make($request->all(), PropuestaNegocio::getRules());

        if ($validator->fails()) {
            $errors = $validator->errors();
            return Redirect::back()->withErrors($errors)->withInput();
        }

        $propuesta->fill($request->all());
        $propuesta->save();

        if (!$propuesta) {
            $errors = new MessageBag(['error' => ['Error. No se pudo crear la propuesta']]);
            return Redirect::back()->withErrors($errors)->withInput();
        }

        //return redirect('/propuesta/create?step=2')->with('message', "La propuesta fue creada con éxito");
        return redirect()->action(
            'PropuestaController@edit', ['id' => $propuesta->id]
        )->with('message', "La propuesta fue editada con éxito");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $rdo = DB::table('propuesta_negocio')
            ->where('id', $id)
            ->update(['active' => 0]);

        if (!$rdo) {
            return redirect('/cliente')->with('message', 'No se pudo eliminar la propuesta de negocio');
        }

        return redirect('/cliente')->with('message', 'Propuesta de negocio eliminada con éxito');
    }

    /**
     * Mètodo utilizado para retornar un array con los datos de la propuesta
     * usados en la pantalla edit
     * @param $id
     * @return array
     */
    private function getEditData($id)
    {

        //Listado vendedores
        $vendedores = User::where("tipo_usuario_id", 2)
            ->where("is_activo", 1)
            ->get();

        //Listado Tipos de propuesta
        $tipos_propuestas = TipoPropuestaNegocio::all();

        $propuesta = PropuestaNegocio::find($id);

        $cliente = Cliente::find($propuesta->cliente_id);

        $user = User::find($propuesta->users_id);

        return [
            "propuesta" => $propuesta,
            "cliente" => $cliente,
            "user" => $user,
            "vendedores" => $vendedores,
            "tipos_propuestas_negocios" => $tipos_propuestas
        ];
    }
}
