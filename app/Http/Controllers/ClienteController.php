<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\CondicionIVA;
use App\Provincia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use Mockery\Exception;
use Illuminate\Http\UploadedFile;

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
            Cliente::CUIT($request->get('CUIT'))
                ->nombre($request->get('razon_social'))
                ->select("cliente.*", "provincia.provincia")
                ->leftJoin("provincia", "cliente.provincia_id", "=", "provincia.id")
                ->where("active", 1)
                ->orderBy('email', 'DESC')
                ->paginate(200);

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


    /**
     * Método utilizado para el parseo de clientes XLS
     */
    public function parserClienteFromXLS()
    {

        ini_set("max_execution_time", 2000);
        $objPHPExcel = new \PHPExcel();

        $inputFileName = public_path("exportacion_clientes/1.xls");
        //  Read your Excel workbook
        try {

            $inputFileType = \PHPExcel_IOFactory::identify($inputFileName);
            $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($inputFileName);
        } catch (\Exception $e) {
            die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . $e->getMessage());
        }

        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        $correcto = 0;
        $incorrecto = 0;

        for ($row = 2; $row <= $highestRow; $row++) {
            $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                NULL,
                TRUE,
                FALSE);

            $client_array = $this->getClienteArrayFromParseXLS($rowData);

            //Busco si ya hay cargado un cliente con el CUIT
            $client = Cliente::where("CUIT", $client_array["CUIT"])->first();
            if ($client) {
                $client->fill($client_array);
                $rdo = $client->save();
            } else {
                $rdo = Cliente::create($client_array);
            }

            if (!$rdo) {
                $incorrecto++;
            } else {
                $correcto++;
            }
        }

        return response()->json(["msg" => "Se procesaron cliente/s " + $correcto + " correctamente y " + $incorrecto + " incorrectos.", "correctos" => $correcto, "incorrectos" => $incorrecto, "result" => true]);
    }

    /**
     * Mètodo privado para retornar un array asociativo de la fila leída por el xls.
     * @param $array_xls
     * @return array
     */
    private function getClienteArrayFromParseXLS($array_xls)
    {
        $array_xls = $array_xls[0];

        $client_insert = ['razon_social' => $array_xls[0],
            'condicion_iva' => $array_xls[1],
            'CUIT' => $array_xls[2],
            'email' => $array_xls[3],
            'telefono' => $array_xls[8],
            'fax' => $array_xls[9],
            'celular' => $array_xls[10],
            'localidad' => $array_xls[6],
            'domicilio' => $array_xls[4],
            'codigo_postal' => $array_xls[5],
            'localidad_comercial' => $array_xls[13],
            'domicilio_comercial' => $array_xls[11],
            'codigo_postal_comercial' => $array_xls[12]
        ];

        //$array_xls[7] = provincia
        if ($array_xls[7] != "") {
            $provincia = Provincia::where("provincia", $array_xls[7])->first();
            if ($provincia) {
                $client_insert["provincia_id"] = $provincia->id;
            }
        }

        //$array_xls[14] = provincia_comercial
        if ($array_xls[14] != "") {
            $provincia = Provincia::where("provincia", $array_xls[14])->first();
            if ($provincia) {
                $client_insert["provincia_comercial_id"] = $provincia->id;
            }
        }


        return $client_insert;
    }


    /**
     * Método que realiza la subida del archivo XLS
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function uploadXLS(Request $request)
    {

        $file = $request->file('fileinput');

        if (!is_null($file)) {

            $partes_ruta = pathinfo($file->getClientOriginalName());
            if($partes_ruta['extension'] != "xls"){
                $errors = new MessageBag(['error' => ['Debe ingresar un archivo XLS']]);
                return Redirect::back()->withErrors($errors);
            }

            Input::file('fileinput')->move(public_path("exportacion_clientes"), "1.xls");

            $path = public_path("exportacion_clientes/1.xls");
            if (is_file($path)) {
                return view('cliente/upload_xls', ["file" => $path]);
            }
        }
        $errors = new MessageBag(['error' => ['No ingresó ningún archivo']]);
        return Redirect::back()->withErrors($errors);
    }

    /**
     * Retorna la pantalla utilizada para parseo de clientes
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getUploadXLS()
    {
        $inputFileName = public_path("exportacion_clientes/1.xls");
        if (is_file($inputFileName)) {
            return view('cliente/upload_xls', ["file" => $inputFileName]);
        } else {
            return view('cliente/upload_xls', ["file" => ""]);
        }
    }

    /**
     * Mètodo utilizado para retornar el archivo
     */
    public function getArchivo()
    {

        $enlace = public_path("exportacion_clientes/1.xls");
        if (is_file($enlace)) {

            header("Content-Disposition: attachment; filename=Archivo_exportación_clientes.xls");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Length: " . filesize($enlace));
            readfile($enlace);
        } else {
            die("No se encontró el archivo en el servidor");
        }
    }
}
