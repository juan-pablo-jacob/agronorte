<div class="content-box">
    <h3 class="content-box-header bg-default">
        Datos generales propuesta
    </h3>
    <div class="content-box-wrapper">
        <form role="form" id="form_editar_propuesta" action="{{url("/propuesta/" . $propuesta->id)}}" method="post"
              enctype="multipart/form-data">
            {{ csrf_field() }}
            {{ method_field('PUT') }}

            <h4 class="font-gray font-size-16"><strong>Datos Cliente *</strong></h4>
            <input type="hidden" id="propuesta_id" value="{{$propuesta->id}}"/>
            <input type="hidden" name="cliente_id" id="cliente_id" value="{{$propuesta->cliente_id}}"/>

            <div id="div_datos_clientes">
                <div class="form-group col-md-4">
                    <label>CUIT</label>
                    <input type="text" class="form-control" id="CUIT" value="{{$cliente->CUIT}}">
                </div>

                <div class="form-group col-md-4" id="div_razon_social" style="display: none">
                    <label>Razón social</label>
                    <input type="text" class="form-control" id="razon_social" value="{{$cliente->CUIT}}">
                </div>

                <div class="form-group col-md-4" id="div_telefono" style="display: none">
                    <label>Teléfono</label>
                    <input type="text" class="form-control" id="telefono" value="{{$cliente->CUIT}}">
                </div>

                <div class="form-group col-md-4" id="div_email" style="display: none">
                    <label>Email</label>
                    <input type="email" class="form-control" id="email" value="{{$cliente->CUIT}}">
                </div>

                <div class="form-group col-md-4" id="div_provincia" style="display: none">
                    <label>Provincia</label>
                    <input type="text" class="form-control" id="provincia" value="{{$cliente->CUIT}}">
                </div>

                <div class="form-group col-md-4" id="div_localidad" style="display: none">
                    <label>Localidad</label>
                    <input type="text" class="form-control" id="localidad" value="{{$cliente->CUIT}}">
                </div>

                <div class="form-group col-md-4" id="div_direccion" style="display: none">
                    <label>Dirección</label>
                    <input type="text" class="form-control" id="direccion" value="{{$cliente->CUIT}}">
                </div>

                <div class="clearfix">&nbsp;</div>
                <div class="form-group col-md-12">
                    <a href="javascript:;" id="btnAgregarCliente" class="btn btn-default" tittle="Agregar"> <i
                                class="glyph-icon icon-elusive-plus"></i> Agregar Cliente</a>
                </div>
            </div>


            <div class="clearfix">&nbsp;</div>
            <div class="divider"></div>
            <h4 class="font-gray font-size-16"><strong>Datos Propuesta</strong></h4>
            <div class="form-group col-md-4">
                <label for="exampleInputEmail1">Fecha Propuesta *</label>
                <div class="input-prepend input-group ">
                    <span class="add-on input-group-addon">
                        <i class="glyph-icon icon-calendar"></i>
                    </span>

                    <input type="text" class="bootstrap-datepicker form-control" name="fecha"
                           placeholder="dd/mm/aaaa"
                           data-date-format="mm/dd/yy" value="{{date('d/m/Y',strtotime($propuesta->fecha))}}">

                </div>
            </div>

            <div class="form-group col-md-4">
                <label>Seleccione tipo propuesta *</label>
                <select id="tipo_propuesta_negocio_id" name="tipo_propuesta_negocio_id" class="form-control">
                    <option value="">&lt;Seleccione&gt;</option>
                    @foreach($tipos_propuestas_negocios as $tipo)
                        @if($propuesta->tipo_propuesta_negocio_id == $tipo->id)
                            <option value="{{$tipo->id}}" selected>{{$tipo->tipo_propuesta_negocio}}</option>
                        @else
                            <option value="{{$tipo->id}}">{{$tipo->tipo_propuesta_negocio}}</option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div class="form-group col-md-4">
                <label>Seleccione Vendedor *</label>
                <select id="users_id" name="users_id" class="form-control">
                    <option value="">&lt;Seleccione&gt;</option>
                    @foreach($vendedores as $vendedor)
                        @if($propuesta->users_id == $vendedor->id)
                            <option value="{{$vendedor->id}}"
                                    selected>{{$vendedor->nombre}} {{$vendedor->apellido}}</option>
                        @else
                            <option value="{{$vendedor->id}}">{{$vendedor->nombre}} {{$vendedor->apellido}}</option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div class="clearfix"></div>

            <div class="form-group col-md-12">
                <button type="submit" class="btn btn-primary"><i class="glyph-icon icon-save"></i>&nbsp;Guardar
                </button>
            </div>
        </form>
    </div>

</div>

<script type="text/javascript">

    var actualizarDatosCliente = function (id) {
        if (parseInt(id) > 0) {
            var url = BASE_URL + '/cliente/showJSON/' + id;
            var data = "";
            $.get(url, data, function (result) {

                if (result.result == false) {
                    alertify.error(result.msg);
                } else {

                    $.each($("#div_datos_clientes input"), function (index, value) {
                        if (typeof result[$(this).attr("id")] != "undefined") {
                            if ($(this).attr("id") != "CUIT") {
                                $(this).val(result[$(this).attr("id")]).prop("disabled", true);
                                $("#div_" + $(this).attr("id")).show();
                            } else {
                                $(this).val(result["CUIT"]);
                            }
                        }
                    });

                }
            });
        } else {
            $.each($("#div_datos_clientes input"), function (index, value) {
                if ($(this).attr("id") != "CUIT") {
                    $(this).val("").prop("disabled", false);
                    $("#div_" + $(this).attr("id")).hide();
                }
            });
        }
    };


    $(function () {

        $("#btnAgregarCliente").click(function () {
            $("#modal-create-cliente").modal("toggle");
        });

        if ($("#cliente_id").val() != "") {
            actualizarDatosCliente($("#cliente_id").val());
        }
        /**
         * Autosuggest de clientes
         */
        $("#CUIT").autocomplete({
            source: function (request, response) {

                $.ajax({
                    url: BASE_URL + "/as/cliente",
                    data: {query: request.term},
                    success: function (data) {

                        var transformed = $.map(data, function (el) {
                            return {
                                label: el.cliente,
                                id: el.id
                            };
                        });
                        response(transformed);
                    },
                    error: function () {
                        response([]);
                        actualizarDatosCliente(0);
                    }
                });
            },
            select: function (e, ui) {
                if (parseInt(ui.item.id) > 0) {
                    $('#cliente_id').val(ui.item.id);
                    actualizarDatosCliente(ui.item.id);
                } else {
                    $('#cliente_id').val("");
                    actualizarDatosCliente(0);
                }
            }, change: function (event, ui) {
                if (ui.item === null) {
                    if ($('#cliente_id').val() != "") {
                        $('#cliente_id').val("");
                        actualizarDatosCliente(0);
                    }
                }
            }

        });
    });
</script>
@include('propuesta.new_cliente_modal')