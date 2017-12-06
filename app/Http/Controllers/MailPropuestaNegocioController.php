<?php

namespace App\Http\Controllers;

use App\ArchivoPropuesta;
use App\MailPropuestaNegocio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use PHPMailer\PHPMailer\PHPMailer;

class MailPropuestaNegocioController extends Controller
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


        $validator = Validator::make($request->all(), MailPropuestaNegocio::getRules());

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
        $rdo = MailPropuestaNegocio::create($request->all());

        if ($rdo) {
            $this->sendMail($rdo->id);
        }

        return app('App\Http\Controllers\PropuestaController')->editWithParams($request->get("propuesta_negocio_id"), ["step" => 4, "mensaje" => "Se creó el mail para la propuesta de negocio, puede enviarlo"]);
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
        $mail_propuesta_negocio = MailPropuestaNegocio::find($id);

        $validator = Validator::make($request->all(), MailPropuestaNegocio::getRules());

        if ($validator->fails()) {
            $errors = new MessageBag(['error' => ['Error. No se pudo editar la información a enviar al mail']]);
            return Redirect::back()->withErrors($errors)->withInput();
        }

        //Creo la marca
        $mail_propuesta_negocio->fill($request->all());
        $rdo_save = $mail_propuesta_negocio->save();
        $this->sendMail($mail_propuesta_negocio->id);


        return app('App\Http\Controllers\PropuestaController')->editWithParams($request->get("propuesta_negocio_id"), ["step" => 4, "mensaje" => "Se modificaron los datos para el mail de la propuesta de negocio, puede enviarlo"]);
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
     * Mètodo utilizado para el envío de emails
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendMail($id)
    {
        $mail_propuesta_negocio = MailPropuestaNegocio::find($id);

        if ($mail_propuesta_negocio) {

            if ($mail_propuesta_negocio->mail_cliente == "" && $mail_propuesta_negocio->mail_vendedores == "") {
//                $errors = new MessageBag(['error' => ['Error. Ingrese algún contacto para enviar mail']]);
//                return response()->json([
//                    "result" => false,
//                    "errors" => $errors
//                ]);
                return false;
            }

            $mail = new PHPMailer(true); // notice the \  you have to use root namespace here
            try {
                //$mail->SMTPDebug = 2;
                $mail->isSMTP(); // tell to use smtp
                $mail->CharSet = "utf-8"; // set charset to utf8
                $mail->SMTPAuth = config('mail.phpmailer.SMTPAuth');  // use smpt auth
                $mail->SMTPSecure = config('mail.phpmailer.SMTPSecure');  // or ssl
                $mail->Host = config('mail.phpmailer.Host');
                $mail->Port = config('mail.phpmailer.Port'); // most likely something different for you. This is the mailtrap.io port i use for testing.
                $mail->Username = config('mail.phpmailer.Username');
                $mail->Password = config('mail.phpmailer.Password');
                $mail->setFrom(config('mail.phpmailer.From'), config('mail.phpmailer.FromName'));

                $mail->SMTPOptions = array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                );
                $mail->Subject = "Propuesta de negocio";


                //Agrego los archivos si es que los hay
                $archivos_propuestas = ArchivoPropuesta::where("propuesta_negocio_id", $mail_propuesta_negocio->propuesta_negocio_id)
                    ->get();
                if ($archivos_propuestas) {
                    foreach ($archivos_propuestas as $archivo) {
                        $enlace = base_path($archivo->path . $archivo->id . "." . $archivo->ext);
                        if (is_file($enlace)) {
                            $mail->AddAttachment($enlace, $archivo->nombre_archivo);
                        }
                    }
                }

                $mail->MsgHTML("Envío propuesta negocio.");

                if ($mail_propuesta_negocio->mail_cliente != "") {
                    $mail->addAddress($mail_propuesta_negocio->mail_cliente);
                }
                if ($mail_propuesta_negocio->mail_vendedores != "") {
                    $mail->addAddress($mail_propuesta_negocio->mail_vendedores);
                }

                $mail->send();
            } catch (\Exception $e) {
//                $errors = new MessageBag(['error' => [$e->getMessage()]]);
//                return response()->json([
//                    "result" => false,
//                    "errors" => $errors
//                ]);
                return false;
            }
//            return response()->json([
//                "result" => true,
//                "msg" => "La propuesta fue enviada con éxito a las personas seleccionadas"
//            ]);
            return true;
        }

//        $errors = new MessageBag(['error' => ['Error. No se pudieron enviar los mails']]);
//        return response()->json([
//            "result" => false,
//            "errors" => $errors
//        ]);
        return false;
    }


    /**
     * Mètodo utilizado para el envío de emails
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function testSendMail()
    {


        $mail = new PHPMailer(true); // notice the \  you have to use root namespace here
        try {
            $mail->SMTPDebug = 2;
            $mail->isSMTP(); // tell to use smtp
            $mail->CharSet = "utf-8"; // set charset to utf8
            $mail->SMTPAuth = config('mail.phpmailer.SMTPAuth');  // use smpt auth
            $mail->SMTPSecure = config('mail.phpmailer.SMTPSecure');  // or ssl
            $mail->Host = config('mail.phpmailer.Host');
            $mail->Port = config('mail.phpmailer.Port'); // most likely something different for you. This is the mailtrap.io port i use for testing.
            $mail->Username = config('mail.phpmailer.Username');
            $mail->Password = config('mail.phpmailer.Password');
            $mail->setFrom(config('mail.phpmailer.From'), config('mail.phpmailer.FromName'));

            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            $mail->Subject = "Propuesta de negocio";


            //Agrego los archivos si es que los hay
            $mail->MsgHTML("Informamos que su pedido está siendo procesado.");

            $mail->addAddress("juanpablojacob1@gmail.com");

            $mail->send();
        } catch (\Exception $e) {
            die("error");
        }
        die( "enviado");


    }
}
