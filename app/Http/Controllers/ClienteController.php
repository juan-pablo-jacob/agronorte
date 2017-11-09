<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\CondicionIVA;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;

class ClienteController extends Controller
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
    public function index(Request $request)
    {
        $clientes =
            Cliente::email($request->get('email'))
                ->nombre($request->get('razon_social'))
                ->select("cliente.*", "provincia.provincia")
                ->leftJoin("provincia", "cliente.provincia_id", "=", "provincia.id")
                ->where("active", 1)
                ->orderBy('email', 'DESC')
                ->get();

        return view('cliente.list', ["clientes" => $clientes, "request" => $request]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $provincias = DB::table('provincia')
            ->orderBy("provincia", "asc")
            ->get();

        $condiciones_iva = CondicionIVA::all();

        return view('cliente.new', ['provincias' => $provincias, 'condiciones_iva' => $condiciones_iva]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), Cliente::getValidationStore());

        if ($validator->fails()) {
            $errors = $validator->errors();
            return Redirect::back()->withErrors($errors)->withInput();
        }

        DB::beginTransaction();

        $cliente = Cliente::create($request->all());

        if (!$cliente) {
            DB::Rollback();
            $errors = new MessageBag(['error' => ['No se pudo crear el cliente']]);
            return Redirect::back()->withErrors($errors);
        }

        DB::commit();
        return redirect('/cliente')->with('message', 'Cliente creado con éxito');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $id
     * @return mixed
     */
    public function edit($id)
    {
        $cliente = Cliente::find($id);

        $provincias = DB::table('provincia')
            ->orderBy("provincia", "asc")
            ->get();

        $condiciones_iva = CondicionIVA::all();

        return view('cliente/edit', ["cliente" => $cliente, "provincias" => $provincias, 'condiciones_iva' => $condiciones_iva]);
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
        $validator = Validator::make($request->all(), Cliente::getValidationPUT($id));

        $cliente = Cliente::find($id);


        if ($validator->fails()) {
            $errors = $validator->errors();
            return Redirect::back()->withErrors($errors)->withInput();
        }

        DB::beginTransaction();

        $cliente->fill($request->all());
        $rdo = $cliente->save();

        if (!$rdo) {
            DB::Rollback();
            $errors = new MessageBag(['error' => ['No se pudo actualizar el cliente']]);
            return Redirect::back()->withErrors($errors);
        }

        DB::commit();
        return redirect('/cliente')->with('message', 'El cliente fue modificado con éxito');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $rdo = DB::table('cliente')
            ->where('id', $id)
            ->update(['active' => 0]);

        if (!$rdo) {
            return redirect('/cliente')->with('message', 'No se pudo eliminar el Cliente');
        }

        return redirect('/cliente')->with('message', 'Cliente eliminado con éxito');
    }



    /**
     * Método que retorna el valor del registro en formato JSON
     * @param type $id
     * @return type
     */
    public function showJSON($id)
    {
        $record = Cliente::find($id);
        if ($record) {
            return response()->json($record);
        } else {
            return response()->json([
                "result" => false,
                "msg" => "registro no encontrado"
            ]);
        }
    }


    /**
     * Método de retorno del autossugest
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function autosuggest(Request $request)
    {
        //Buscar en la base de datos por el nombre de usuario
        $query = $request->input('query');
        $users = DB::table('cliente')
            ->select('id', DB::raw('CONCAT(IFNULL(razon_social, " ")," - ",IFNULL(CUIT, " "), " - ", IFNULL(email, " ")) as cliente'))
            ->where('CUIT', 'like', '%' . $query . '%')
            ->orWhere('nombre_fantasia', 'like', '%' . $query . '%')
            ->orderBy('nombre_fantasia', 'asc')
            ->get();

        return response()->json($users);
    }
}
