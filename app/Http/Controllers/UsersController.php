<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;


class UsersController extends Controller
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


        $users = User::name($request->get('name'))
            ->email($request->get('email'))
            ->select("users.*")
            ->orderBy('name', 'DESC')
            ->paginate();


        return view('users.list', ["users" => $users, "request" => $request]);
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

        return view('users.new', compact('provincias'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CreateUserRequest $request : Trabajo de validación
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Merge Password
        $request->merge(["password" => Hash::make($request->input("password"))]);
        $request->merge(["is_admin" => $request->input("is_admin") == "on" ? 1 : 0]);

        //Validaciones
        $validator = Validator::make($request->all(), User::getRulesSTORE($request));

        if ($validator->fails()) {
            $errors = $validator->errors();
            return Redirect::back()->withErrors($errors)->withInput();
        }

        //Creación
        User::create($request->all());

        return redirect('/users')->with('message', 'Usuario creado con éxito');
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
     * @return \Illuminate\Http\
     */
    public function edit($id)
    {
        $user = User::find($id);

        $provincias = DB::table('provincia')
            ->orderBy("provincia", "asc")
            ->get();

        return view('users/edit', ["user" => $user, "provincias" => $provincias]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\CreateUserRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $user = User::find($id);

        //Pregunto por la contraseña, si viene distinto de vacío tengo que aplicar encriptación
        $password = $request->input("password");
        if (!empty($password) && $password != "") {
            $request->merge(["password" => Hash::make($password)]);
        } else {
            $request->merge(["password" => $user["password"]]);
        }
        $request->merge(["is_admin" => $request->input("is_admin") == "on" ? 1 : 0]);

        //Validaciones
        $validator = Validator::make($request->all(), User::getRulesPUT($id, $request));
        if ($validator->fails()) {
            $errors = $validator->errors();
            return Redirect::back()->withErrors($errors)->withInput();
        }

        $user->fill($request->all());
        $user->save();
        return redirect('/users')->with('message', 'El usuario fue modificado con éxito');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $rdo = DB::table('users')
            ->where('id', $id)
            ->update(['is_activo' => 0]);

        if (!$rdo) {
            return redirect('/users')->with('message', 'No se pudo eliminar el Usuario');
        }

        return redirect('/users')->with('message', 'Usuario eliminado con éxito');
    }

    public function autosuggest(Request $request)
    {
        //Buscar en la base de datos por el nombre de usuario
        $query = $request->input('query');
        $users = DB::table('users')
            ->select('id', DB::raw('CONCAT(name," ",lastname) as user'))
            ->where('name', 'like', '%' . $query . '%')
            ->orWhere('lastname', 'like', '%' . $query . '%')
            ->orderBy('name', 'asc')
            ->get();

        return response()->json($users);
    }

}
