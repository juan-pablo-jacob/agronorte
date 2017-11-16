<?php

namespace App\Http\Controllers;

use App\Incentivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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


    /**
     * Método que retorna el valor del registro en formato JSON
     * @param type $id
     * @return type
     */
    public function showJSON($id) {
        $record = Incentivo::find($id);
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
