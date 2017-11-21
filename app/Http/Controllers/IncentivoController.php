<?php

namespace App\Http\Controllers;

use App\Incentivo;
use App\IncentivoProducto;
use App\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;

class IncentivoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $incentivos =
            Incentivo::nocaducados($request->get('no_caducados'))
                ->select("incentivo.*", DB::raw("COUNT(incentivo_producto.id) as cantidad_productos"))
                ->leftJoin("incentivo_producto", "incentivo.id", "=", "incentivo_producto.incentivo_id")
                ->where("active", 1)
                ->groupBy("incentivo.id")
                ->orderBy('fecha_caducidad', 'DESC')
                ->paginate(200);

        return view('incentivo.list', ["incentivos" => $incentivos, "request" => $request]);
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
        $date = \DateTime::createFromFormat("d/m/Y", $request->input('fecha_caducidad'));
        $dateFormated = $date->format("Y-m-d");
        $request->merge(["fecha_caducidad" => $dateFormated]);

        $validator = Validator::make($request->all(), Incentivo::getRules());

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
        Incentivo::create($request->all());

        return response()->json([
            "result" => true,
            "msg" => "El incentivo fue creado con éxito"
        ]);
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
        $date = \DateTime::createFromFormat("d/m/Y", $request->input('fecha_caducidad'));
        $dateFormated = $date->format("Y-m-d");
        $request->merge(["fecha_caducidad" => $dateFormated]);

        $validator = Validator::make($request->all(), Incentivo::getRules());

        if ($validator->fails()) {
            $errors = $validator->errors();
            if ($request->ajax()) {
                return response()->json([
                    "result" => false,
                    "errors" => $errors
                ]);
            }
        }

        $record = Incentivo::find($id);

        $record->fill($request->all());
        $record->save();

        return response()->json([
            "result" => true,
            "msg" => "El incentivo fue actualizado con éxito"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $rdo = DB::table('incentivo')
            ->where('id', $id)
            ->update(['active' => 0]);

        if (!$rdo) {
            return redirect('/incentivo')->with('message', 'No se pudo eliminar el incentivo');
        }

        return redirect('/incentivo')->with('message', 'Incentivo eliminado con éxito');
    }


    /**
     * Método que retorna el valor del registro en formato JSON
     * @param type $id
     * @return type
     */
    public function showJSON($id)
    {
        $record = DB::table("incentivo")
            ->where("id", $id)
            ->select("*", DB::raw("DATE_FORMAT(fecha_caducidad,'%d/%m/%Y') as fecha_caducidad_format"))
            ->first();
        if ($record) {
            return response()->json($record);
        } else {
            return response()->json([
                "result" => false,
                "msg" => "registro no encontrado"
            ]);
        }
    }

    public function verProductos($id, Request $request)
    {
        $incentivo = Incentivo::find($id);

        $query =
            Producto::modelo($request->get('modelo'))
                ->marca($request->get('marca_id'))
                ->tipoProducto($request->get('tipo_producto_id'))
                ->select("producto.*", "tipo_producto.tipo_producto", "marca.marca")
                ->leftJoin("tipo_producto", "producto.tipo_producto_id", "=", "tipo_producto.id")
                ->leftJoin("marca", "producto.marca_id", "=", "marca.id");


        $incentivos = DB::table("incentivo_producto")
            ->select("incentivo_producto.producto_id")
            ->where("incentivo_producto.incentivo_id", $id)
            ->get();
        $array = [];
        foreach ($incentivos as $value) {
            $array[] = $value->producto_id;
        }

        if ($request->get('asignado') == 1) {
            $query->whereIn('producto.id', $array);
        } else {
            $query->whereNotIn('producto.id', $array);
        }

        $productos = $query->where("producto.active", 1)
            ->where("producto.is_nuevo", 1)
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

        return view('incentivo.list_productos', ["productos" => $productos, "request" => $request, "tipo_productos" => $tipo_productos, "marcas" => $marcas, "incentivo" => $incentivo]);
    }


    /**
     * Método utilizado para asociar productos - incentivos en la tabla incentivo_producto
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function agregarProductos(Request $request)
    {
        $ids_productos = explode(",", $request->get("ids"));
        if (count($ids_productos) == 0) {
            $errors = new MessageBag(['error' => ['Error. No hay productos seleccionados']]);
            return Redirect::back()->withErrors($errors);
        }

        $incentivo = Incentivo::find($request->get("incentivo_id"));
        if (!$incentivo) {
            $errors = new MessageBag(['error' => ['Error. No se encontró el incentivo en la base de datos']]);
            return Redirect::back()->withErrors($errors);
        }

        foreach ($ids_productos as $producto_id) {
            $producto = Producto::find($producto_id);
            if ($producto) {
                $rdo = IncentivoProducto::create([
                    "producto_id" => $producto_id,
                    "incentivo_id" => $incentivo->id
                ]);
                if (!$rdo) {
                    $errors = new MessageBag(['error' => ['Error. Uno de los registros no se pudo insertar']]);
                    return Redirect::back()->withErrors($errors);
                }
            }
        }

        return Redirect::back()->with('message', 'Los productos fueron asignados con éxito');
    }


    /**
     * Mètodo utilizado para quitar los productos
     * @param Request $request
     * @return mixed
     */
    public function quitarProductos(Request $request)
    {

        $ids_productos = explode(",", $request->get("ids"));
        if (count($ids_productos) == 0) {
            $errors = new MessageBag(['error' => ['Error. No hay productos seleccionados']]);
            return Redirect::back()->withErrors($errors);
        }

        $incentivo = Incentivo::find($request->get("incentivo_id"));
        if (!$incentivo) {
            $errors = new MessageBag(['error' => ['Error. No se encontró el incentivo en la base de datos']]);
            return Redirect::back()->withErrors($errors);
        }


        foreach ($ids_productos as $producto_id) {

            $incentivo_producto = IncentivoProducto::where("producto_id", $producto_id)
                ->where("incentivo_id", $incentivo->id)
                ->first();

            if ($incentivo_producto) {
                $rdo = $incentivo_producto->delete();

                if (!$rdo) {
                    $errors = new MessageBag(['error' => ['Error. Uno de los registros no se pudo insertar']]);
                    return Redirect::back()->withErrors($errors);
                }
            }
        }

        return Redirect::back()->with('message', 'Los incentivos de los productos fueron eliminados con éxito');
    }
}
