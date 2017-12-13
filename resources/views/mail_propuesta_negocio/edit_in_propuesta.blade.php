<form action="{{url("/mail_propuesta_negocio/" . $mail_propuesta->id)}}" method="post" enctype="multipart/form-data">
    {{ csrf_field() }}
    {{ method_field('PUT') }}

    <input type="hidden" name="propuesta_negocio_id" value="{{$mail_propuesta->propuesta_negocio_id}}">
    <input type="hidden" id="mail_propuesta_negocio_id" value="{{$mail_propuesta->id}}">

    <div class="content-box">
        <h3 class="content-box-header bg-default">
            <i class="glyph-icon icon-elusive-basket"></i>
            Editar Mail de propuesta de negocio
        </h3>
        <div class="content-box-wrapper">

            <h4 class="font-gray font-size-16"><strong>Datos Mail a enviar</strong></h4>

            <div class="form-group col-md-3">
                <label>Enviar a cliente</label>
                <div class="checkbox-inline">
                    <label>
                        <input type="checkbox" name="enviar_cliente" id="select_enviar_cliente" value="1"
                               class="form-control custom-checkbox-cliente">
                    </label>
                </div>
            </div>

            <div class="form-group col-md-3" id="div_mail_cliente" style="display: none">
                <label>Mail Cliente *</label>
                <input type="text" class="form-control" name="mail_cliente" value="{{$mail_propuesta->mail_cliente}}">
            </div>

            <div class="form-group col-md-3">
                <label>Mail vendedor *</label>
                <select name="mail_vendedores" class="form-control">
                    <option value="">&lt;No enviar a ningún vendedor&gt;</option>
                    @foreach($vendedores as $vendedor)
                        @if($mail_propuesta->mail_vendedores == $vendedor->email)
                            <option value="{{$vendedor->email}}"
                                    selected>{{$vendedor->nombre}} {{$vendedor->apellido}} ( {{$vendedor->email}} )</option>
                        @else
                            <option value="{{$vendedor->email}}">{{$vendedor->nombre}} {{$vendedor->apellido}} ( {{$vendedor->email}} )</option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div class="form-group col-md-3">
                <label>Información IVA *</label>
                <select name="is_iva_incluido" class="form-control">
                    <option value="0" @if($mail_propuesta->is_iva_incluido == 0)  selected @endif>IVA Discriminado</option>
                    <option value="1" @if($mail_propuesta->is_iva_incluido == 1) selected @endif>IVA Incluido</option>
                </select>
            </div>

            <div class="clearfix">&nbsp;</div>

            <button class="btn btn-success"><i class="icon icon-elusive-mail"></i>&nbsp;Enviar Propuesta
            </button>
        </div>
    </div>
</form>

<script>
    $(function () {

        $('input[type="checkbox"].custom-checkbox-cliente').uniform();
        $('.checker span').append('<i class="glyph-icon icon-check"></i>');

        $("#select_enviar_cliente").change(function () {
            if (this.checked) {
                $("#div_mail_cliente").show();
            } else {
                $("#div_mail_cliente").hide();
            }
        });

        $("#btnSendMail").click(function () {
            $('#loading').show();
            $.ajax({
                url: BASE_URL + "/mail_propuesta_negocio/send/" + $("#mail_propuesta_negocio_id").val(),
                data: "",
                method: "GET",
                success: function (result) {
                    $('#loading').hide();
                    if (result.result == false) {
                        $.each(result.errors, function (index, value) {
                            alertify.error(value[0]);
                        });
                    } else {
                        alertify.success(result.msg);
                    }
                },
                error: function (data) {
                    $('#loading').hide();
                    alertify.error("Se produjo un error inesperado. No se enviaron los mails");
                }
            });
        });
    });
</script>