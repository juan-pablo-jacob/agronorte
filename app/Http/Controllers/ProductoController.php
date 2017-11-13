<?php

namespace App\Http\Controllers;

use App\ParametrosSistema;
use App\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductoController extends Controller
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
        $productos =
            Producto::modelo($request->get('modelo'))
                ->marca($request->get('marca_id'))
                ->tipoProducto($request->get('tipo_producto_id'))
                ->select("producto.*", "tipo_producto.tipo_producto", "marca.marca")
                ->leftJoin("tipo_producto", "producto.tipo_producto_id", "=", "tipo_producto.id")
                ->leftJoin("marca", "producto.marca_id", "=", "marca.id")
                ->where("producto.active", 1)
                ->orderBy('producto.modelo', 'DESC')
                ->paginate(200);

        $tipo_productos = DB::table('tipo_producto')
            ->where("active", 1)
            ->orderBy("tipo_producto", "asc")
            ->get();

        $marcas = DB::table('marca')
            ->where("active", 1)
            ->orderBy("marca", "asc")
            ->get();

        return view('producto.list', ["productos" => $productos, "request" => $request, "tipo_productos" => $tipo_productos, "marcas" => $marcas]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tipo_productos = DB::table('tipo_producto')
            ->where("active", 1)
            ->orderBy("tipo_producto", "asc")
            ->get();

        $marcas = DB::table('marca')
            ->where("active", 1)
            ->orderBy("marca", "asc")
            ->get();

        $parametros_sistema = ParametrosSistema::find(1);


        return view('producto.new', ["tipo_productos" => $tipo_productos, "marcas" => $marcas, "parametros_sistema" => $parametros_sistema]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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


    public function multiUpload(Request $request)
    {
        return response()->json([
            "result" => true,
            "msg" => "La marca fue creada con éxito"
        ]);
    }


    public function multiUploadSave(Request $request)
    {
        $files = $request->file('files');
        dd($files);
    }
}
