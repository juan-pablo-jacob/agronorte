<form action="{{url("/mail_propuesta_negocio/" . $mail_propuesta->id)}}" method="post" enctype="multipart/form-data">
    {{ csrf_field() }}
    {{ method_field('PUT') }}

    <input type="hidden" name="propuesta_negocio_id" value="{{$mail_propuesta->propuesta_negocio_id}}">

    <div class="content-box">
        <h3 class="content-box-header bg-default">
            <i class="glyph-icon icon-elusive-basket"></i>
            Editar Mail de propuesta de negocio
        </h3>
        <div class="content-box-wrapper">

            <h4 class="font-gray font-size-16"><strong>Datos Mail a enviar</strong></h4>

            <div class="form-group col-md-4">
                <label>Mail Cliente *</label>
                <input type="text" class="form-control" name="mail_cliente" value="{{$mail_propuesta->mail_cliente}}">
            </div>

            <div class="form-group col-md-4">
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

            <div class="clearfix">&nbsp;</div>

            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp;Guardar</button>
            <button id="btnSendMail" class="btn btn-success"><i class="icon icon-elusive-mail"></i>&nbsp;Enviar Mail
            </button>
        </div>
    </div>
</form>

<script>
    $(function () {

        $("#btnSendMail").click(function () {

        });
    });
</script>