<?php

namespace App\Http\Controllers;

use App\ArchivoPropuesta;
use App\Cotizacion;
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
            $this->sendMail($rdo->id, $request);
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
        $rdo_envio_mail = $this->sendMail($id, $request);

        if (!$rdo_envio_mail) {
            $errors = new MessageBag(['error' => ['Error. No se pudo enviar al mail']]);
            return Redirect::back()->withErrors($errors)->withInput();
        }


        return Redirect::back()->with('message', 'Propuesta enviada con éxito');
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
    public function sendMail($id, Request $request)
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
                $mail->Subject = "Propuesta de negocio Agronorte S.R.L.";


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

                $string_cuadro = $this->setCuadroPropuestaHTML($mail_propuesta_negocio);
                if ($string_cuadro == "") {

                    return false;
                }

                $plantilla = file_get_contents(resource_path("/views/mail_propuesta_negocio/mail_propuesta.html"));
                $plantilla = str_replace('{{cuadro_propuesta}}', $string_cuadro, $plantilla);
                $plantilla = str_replace('{{path_logo}}', url("logo_agronorte.jpg"), $plantilla);


                $mail->MsgHTML($plantilla);

                if ($mail_propuesta_negocio->mail_cliente != "" && (int)$request->get("enviar_cliente") == 1) {
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
        die("enviado");


    }

    /**
     * Método utilizado para armar la tabla y reemplazarla en el texto del mail
     * @param MailPropuestaNegocio $mail_propuesta_negocio
     * @return string
     */
    private function setCuadroPropuestaHTML(MailPropuestaNegocio $mail_propuesta_negocio)
    {
        $string_cuadro = '<table align="center" border="1" cellpadding="0" cellspacing="0" width="1200">
                            <thead>
                            <tr>
                                <th style="background: #80808047;">&nbsp;</th>
                                <th style="background: #80808047;">Unidad</th>
                                <th style="background: #80808047;">Descripción</th>
                                <th style="background: #80808047;">Precio sin IVA</th>
                                <th style="background: #80808047;">IVA</th>
                                <th style="background: #80808047;">Prcio IVA Incluído</th>
                            </tr>
                            </thead>
                            <tbody>';

        $cotizaciones =
            Cotizacion::select("cotizacion.*", "producto.modelo", "marca.marca", "tipo_producto.tipo_producto")
                ->leftJoin("producto", "producto.id", "=", "cotizacion.producto_id")
                ->leftJoin("marca", "producto.marca_id", "=", "marca.id")
                ->leftJoin("tipo_producto", "producto.tipo_producto_id", "=", "tipo_producto.id")
                ->where("cotizacion.propuesta_negocio_id", $mail_propuesta_negocio->propuesta_negocio_id)
                ->where("cotizacion.active", 1)
//                ->where("cotizacion.is_toma", $request->get("is_toma"))
                ->orderBy('producto.modelo', 'DESC')
                ->get(200);

        if (count($cotizaciones) > 0) {
            $cot_total_venta = 0;
            $cot_total_toma = 0;
            $cot_total_descuento = 0;
            foreach ($cotizaciones as $key => $cotizacion) {
                if ($cotizacion->is_toma == 0) {
                    $cot_total_venta += $cotizacion->precio_venta_iva;
                    $iteracion = $key + 1;


                    $precio_venta = number_format($cotizacion->precio_venta, 2);
                    $precio_iva = number_format($cotizacion->precio_venta_iva - $cotizacion->precio_venta, 2);
                    $precio_venta_iva = number_format($cotizacion->precio_venta_iva, 2);
                    $str_descuento = "";
                    if ((float)$cotizacion->descuento > 0) {

                        $cot_total_descuento += $cotizacion->precio_lista_producto * $cotizacion->descuento / 100;
                        $precio_descuento = number_format($cotizacion->precio_lista_producto * $cotizacion->descuento / 100, 2);
                        $str_descuento = "<tr>
                                            <td>
                                                <strong>
                                                    Descuento {$cotizacion->descuento}%
                                                </strong>
                                            </td>
                                            <td colspan=\"3\">
                                                {$cotizacion->descripcion_descuento}
                                            </td>
                                            <td align=\"right\">
                                                USD {$precio_descuento}
                                            </td>
                                        </tr>";
                    }

                    $string_cuadro .= "<tr>
                                        <td style=\"background: rgba(149,224,55,0.28);\" ><strong>Venta</strong>
                                            (producto {$iteracion})
                                        </td>
                                        <td style=\"background: rgba(149,224,55,0.28);\">{$cotizacion->modelo}</td>
                                        <td>{$cotizacion->observacion}</td>
                                        <td align=\"right\">USD {$precio_venta}</td>
                                        <td align=\"right\">USD {$precio_iva}</td>
                                        <td align=\"right\">USD {$precio_venta_iva}</td>
                                    </tr>
                                    {$str_descuento}
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td colspan=\"4\"><strong>Total VENTA nuevo:</strong></td>
                                        <td align=\"right\">USD {$precio_venta_iva}</td>
                                    </tr>";
                }
            }
            $string_cuadro .= "<tr>
                                    <td colspan=\"6\"></td>
                                </tr>";

            foreach ($cotizaciones as $key => $cotizacion) {
                if ($cotizacion->is_toma == 1) {
                    $cot_total_toma += $cotizacion->precio_toma_iva;
                    $iteracion = $key + 1;

                    $precio_toma = number_format($cotizacion->precio_toma, 2);
                    $precio_iva = number_format($cotizacion->precio_toma_iva - $cotizacion->precio_toma, 2);
                    $precio_toma_iva = number_format($cotizacion->precio_toma_iva, 2);

                    $string_cuadro .= "<tr>
                                            <td style=\"background: rgba(149,224,55,0.28);\" rowspan=\"2\"><strong>Toma</strong>
                                                (producto {$iteracion})
                                            </td>
                                            <td style=\"background: rgba(149,224,55,0.28);\">{$cotizacion->modelo}</td>
                                            <td>{$cotizacion->observacion}</td>
                                            <td align=\"right\">USD {$precio_toma}</td>
                                            <td align=\"right\">USD {$precio_iva}</td>
                                            <td align=\"right\">USD {$precio_toma_iva}</td>
                                        </tr>
                                        <tr>
                                            <td colspan=\"4\"><strong>Total TOMA usado:</strong></td>
                                            <td align=\"right\">USD {$precio_toma_iva}</td>
                                        </tr>";
                }
            }
            $dif = number_format($cot_total_venta - $cot_total_toma - $cot_total_descuento, 2);
            $string_cuadro .= "<tr>
                                    <td colspan=\"4\"></td>
                                    <td><strong>A pagar por el cliente</strong></td>
                                    <td style=\"background: rgba(149,224,55,0.28);\" align=\"right\">USD {$dif}</td>
                                </tr></table>";

            return $string_cuadro;
        } else {
            return "";
        }
    }
}
