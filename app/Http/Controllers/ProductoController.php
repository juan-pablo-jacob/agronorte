<?php

namespace App\Http\Controllers;

use App\ArchivoProducto;
use App\ParametrosSistema;
use App\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;

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

        $usuarios_vendedores = DB::table('users')
            ->where("is_activo", 1)
            ->where("tipo_usuario_id", 2)
            ->orderBy("nombre", "asc")
            ->get();

        $parametros_sistema = ParametrosSistema::find(1);


        return view('producto.new', ["tipo_productos" => $tipo_productos, "marcas" => $marcas, "parametros_sistema" => $parametros_sistema, "usuarios_vendedores" => $usuarios_vendedores]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        //El producto es nuevo
        if ($request->get("is_nuevo") == 1) {
            $costo_basico = (float)$request->input("precio_lista") * (100 - $request->input("bonificacion_basica")) / 100;
            $request->merge(["costo_basico" => $costo_basico, "costo_usado" => "", "precio_sin_canje" => ""]);
        } else {
            $request->merge(["bonificacion_basica" => "", "costo_basico" => ""]);
        }


        $validator = Validator::make($request->all(), Producto::getRules());

        if ($validator->fails()) {
            $errors = $validator->errors();
            return Redirect::back()->withErrors($errors)->withInput();
        }


        DB::beginTransaction();

        $producto = Producto::create($request->all());

        if (!$producto) {
            DB::Rollback();
            $errors = new MessageBag(['error' => ['No se pudo crear el producto']]);
            return Redirect::back()->withErrors($errors);
        }

        $this->addFileProducto($request, $producto->id);

        DB::commit();
        return redirect('/producto')->with('message', 'Producto creado con éxito');
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
     * Método que retorna el valor del registro en formato JSON
     * @param type $id
     * @return type
     */
    public function showJSON($id)
    {
        $record = DB::table("producto")
            ->select("producto.*", "tipo_producto.tipo_producto")
            ->leftJoin("tipo_producto", "producto.tipo_producto_id", "=", "tipo_producto.id")
            ->where("producto.id", $id)
            ->first();

        if ($record) {
            return response()->json($record);
        } else {
            return response()->json([
                "result" => false,
                "msg" => "Registro no encontrado"
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $producto = Producto::find($id);

        $tipo_productos = DB::table('tipo_producto')
            ->where("active", 1)
            ->orderBy("tipo_producto", "asc")
            ->get();

        $marcas = DB::table('marca')
            ->where("active", 1)
            ->orderBy("marca", "asc")
            ->get();

        $usuarios_vendedores = DB::table('users')
            ->where("is_activo", 1)
            ->where("tipo_usuario_id", 2)
            ->orderBy("nombre", "asc")
            ->get();

        return view('producto.edit', ["producto" => $producto, "tipo_productos" => $tipo_productos, "marcas" => $marcas,
            "usuarios_vendedores" => $usuarios_vendedores]);
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
        $producto = Producto::find($id);


        //El producto es nuevo
        if ($request->get("is_nuevo") == 1) {
            $costo_basico = (float)$request->input("precio_lista") * (100 - $request->input("bonificacion_basica")) / 100;
            $request->merge(["costo_basico" => $costo_basico, "costo_usado" => "", "precio_sin_canje" => ""]);
        } else {
            $request->merge(["bonificacion_basica" => "", "costo_basico" => ""]);
        }

        $validator = Validator::make($request->all(), Producto::getRules());

        if ($validator->fails()) {
            $errors = $validator->errors();
            return Redirect::back()->withErrors($errors)->withInput();
        }


        DB::beginTransaction();

        $producto->fill($request->all());
        $rdo = $producto->save();

        if (!$rdo) {
            DB::Rollback();
            $errors = new MessageBag(['error' => ['No se pudo actualizar el producto']]);
            return Redirect::back()->withErrors($errors);
        }

        $this->addFileProducto($request, $producto->id);

        DB::commit();
        return redirect('/producto')->with('message', 'Producto actualizado con éxito');
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


    /**
     * retorna JSON con todos los archivos pertenecientes a un "object_id"
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function multiUpload(Request $request)
    {

        $archivos_producto = DB::table("archivo_producto")
            ->where("producto_id", $request->input("object_id"))
            ->get();


        if (count($archivos_producto) > 0) {
            return response()->json([
                "result" => true,
                "files" => $archivos_producto
            ]);
        } else {
            return response()->json([
                "result" => false
            ]);
        }

    }


    /**
     * Método que agrega los upload multiples de los documentos
     * @param Request $request
     * @return mixed
     */
    public function multiUploadSave(Request $request)
    {
        $this->addFileProducto($request, $request->input("object_id"));

        return Redirect::back()->with('message', 'Archivos subidos con éxito');
    }


    /**
     * Método que agrega los archivos al file producto
     * @param Request $request
     * @param $id
     */
    public function addFileProducto(Request $request, $id)
    {

        if (count($request->file('files')) > 0) {

            foreach ($request->file('files') as $key => $file) {
                if ($file->isValid() && ArchivoProducto::validateFileExtension($file->getClientOriginalExtension())) {
                    $insert_array = [
                        "nombre_archivo" => $file->getClientOriginalName(),
                        "ext" => $file->getClientOriginalExtension(),
                        "content_type" => $file->getClientMimeType(),
                        "producto_id" => $id,
                        "path" => "storage/app/public/archivos/archivo_producto/$id/"
                    ];

                    $validator = Validator::make($insert_array, ArchivoProducto::getRules());

                    if ($validator->fails()) {
                        $errors = $validator->errors();
                        return Redirect::back()->withErrors($errors)->withInput();
                    }

                    $archivo_producto = ArchivoProducto::create($insert_array);

                    if ($archivo_producto) {
                        Storage::disk('public')->put("archivos/archivo_producto/{$id}/" . $archivo_producto->id . "." . $archivo_producto->ext, file_get_contents($file->getRealPath()));
                    }

                }
            }
        }
        return;
    }


    /**
     * Método utilizado para eliminar archivos
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteArchivo($id)
    {
        $archivo_producto = ArchivoProducto::find($id);

        $file = storage_path("app/public/archivos/archivo_producto/{$archivo_producto->producto_id}/{$archivo_producto->id}.{$archivo_producto->ext}");

        if ($archivo_producto->delete()) {

            unlink($file);

            return response()->json([
                "result" => true,
                "msg" => "Archivo eliminado con éxito"
            ]);
        } else {
            $errors = new MessageBag(['error' => ['No se eliminar el archivo']]);
            return response()->json([
                "result" => true,
                "errors" => $errors
            ]);
        }
    }


    /**
     * Mètodo utilizado para retornar el archivo
     * @param $id
     */
    public function getArchivo($id)
    {
        $archivo_producto = ArchivoProducto::find($id);

        $enlace = base_path($archivo_producto->path . $id . "." . $archivo_producto->ext);
        if (is_file($enlace)) {

            header("Content-Disposition: attachment; filename=" . $archivo_producto->nombre_archivo);
            header("Content-Type: " . $archivo_producto->content_type);
            header("Content-Length: " . filesize($enlace));
            readfile($enlace);
        } else {
            die("No se encontró el archivo en el servidor");
        }
    }


    /**
     * autossugest para los productos nuevos en base al modelo
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function autosuggestNuevos(Request $request)
    {
        //Buscar en la base de datos por el nombre de usuario
        $query = $request->input('query');
        $users = DB::table('producto')
            ->select('id', "modelo")
            ->where('modelo', 'like', '%' . $query . '%')
            ->where("is_nuevo", 1)
            ->where("active", 1)
            ->orderBy('modelo', 'asc')
            ->get();

        return response()->json($users);
    }


    /**
     * autossugest para los productos nuevos en base al modelo
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function autosuggestUsados(Request $request)
    {
        //Buscar en la base de datos por el nombre de usuario
        $query = $request->input('query');
        $users = DB::table('producto')
            ->select('id', "modelo")
            ->where('modelo', 'like', '%' . $query . '%')
            ->where("is_nuevo", 0)
            ->where("active", 1)
            ->orderBy('modelo', 'asc')
            ->get();

        return response()->json($users);
    }
}
