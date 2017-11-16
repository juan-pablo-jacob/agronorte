<?php

namespace App\Http\Controllers;

use App\Incentivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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
}
