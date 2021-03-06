<div class="content-box">
    <h3 class="content-box-header bg-default">
        Venta Producto <strong>Usado</strong>
    </h3>
    <div class="content-box-wrapper">
        <form role="form" id="form_venta_nuevo" action="{{url("/cotizacion")}}" method="post"
              enctype="multipart/form-data">
            {{ csrf_field() }}


            <input type="hidden" name="producto_id" id="producto_id" value="{{old('producto_id')}}">
            <input type="hidden" name="propuesta_negocio_id" value="{{$propuesta->id}}">
            <input type="hidden" name="tipo_propuesta_negocio_id" value="{{$propuesta->tipo_propuesta_negocio_id}}">



            <h4 class="font-gray font-size-16"><strong>Datos Producto *</strong></h4>
            <div id="div_datos_productos">
                <input type="hidden" id="costo_basico" name="costo_basico_producto">

                <div class="form-group col-md-4">
                    <label>Modelo</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="modelo"/>
                        <span class="input-group-btn">
                            <button class="btn btn-primary" id="btnAgregarProductoUsado"  type="button">Agregar Producto Usado</button>
                        </span>
                    </div>
                </div>

                {{--<div class="form-group col-md-4">--}}
                    {{--<a href="javascript:;" id="btnAgregarCliente" class="btn btn-default" tittle="Agregar"> <i--}}
                                {{--class="glyph-icon icon-elusive-plus"></i> Agregar Cliente</a>--}}
                {{--</div>--}}

                <div class="form-group col-md-4" style="display: none">
                    <label>Tipo de producto</label>
                    <input type="text" class="form-control" id="tipo_producto">
                </div>

                <div class="form-group col-md-4" style="display: none">
                    <label>Descripción</label>
                    <textarea name="observacion" id="descripcion_producto" placeholder="Ingrese descripción producto"
                              rows="3"
                              class="form-control textarea-sm">{{old("observacion")}}</textarea>
                </div>

                <div class="form-group col-md-4" style="display: none">
                    <label>Horas motor</label>
                    <input type="text" class="form-control" id="horas_motor">
                </div>

                <div class="form-group col-md-4" style="display: none">
                    <label>Horas trilla</label>
                    <input type="text" class="form-control" id="horas_trilla">
                </div>

                <div class="form-group col-md-4" style="display: none">
                    <label>Precio sin canje</label>
                    <input type="text" class="form-control" id="precio_sin_canje">
                </div>

                <div class="form-group col-md-4" style="display: none">
                    <label>Costo usado</label>
                    <input type="text" class="form-control" id="costo_usado">
                </div>


                <div class="clearfix">&nbsp;</div>

                <div class="form-group col-md-4" style="display: none">
                    <label>Precio Lista</label>
                    <input type="number" class="form-control" id="precio_lista" name="precio_lista_producto" step=0.01 >
                </div>

                <div class="form-group col-md-4" style="display: none">
                    <label>Descuento</label>
                    <input type="number" class="form-control" id="descuento" name="descuento" placeholder="1 % - 100 %" step=0.01 >
                </div>

                <div class="form-group col-md-4" style="display: none">
                    <label>Descripcion descuento</label>
                    <textarea name="descripcion_descuento" placeholder="Ingrese descripción por el descuento" rows="3"
                              class="form-control textarea-sm">{{old("descripcion_descuento")}}</textarea>
                </div>

                <div class="form-group col-md-4" style="display: none">
                    <label>Precio venta</label>
                    <input type="text" class="form-control" id="precio_venta" disabled>
                </div>

            </div>


            <div class="clearfix">&nbsp;</div>
            <div class="divider"></div>
            <div class="form-group col-md-12">
                <button type="submit" id="btnCrearCotizacion" style="display: none" class="btn btn-primary"><i
                            class="glyph-icon icon-save"></i>&nbsp;Crear Cotización
                </button>
            </div>
        </form>
    </div>

</div>


<script type="text/javascript">


    var actualizarDatosProducto = function (id) {
        if (parseInt(id) > 0) {
            var url = BASE_URL + '/producto/showJSON/' + id;
            var data = "";
            $.get(url, data, function (result) {

                if (result.result == false) {
                    alertify.error(result.msg);
                } else {
                    $("#modelo").val(result.modelo);
                    $("#costo_basico").val(result.costo_basico);
                    $("#tipo_producto").val(result.tipo_producto).prop("disabled", true);
                    $("#descripcion_producto").html(result.descripcion);
                    $("#precio_lista").val(result.precio_lista);
                    $("#costo").val(result.costo).prop("disabled", true);
                    $("#horas_motor").val(result.horas_motor).prop("disabled", true);
                    $("#horas_trilla").val(result.horas_trilla).prop("disabled", true);
                    $("#precio_sin_canje").val(result.precio_sin_canje).prop("disabled", true);
                    $("#costo_usado").val(result.costo_usado).prop("disabled", true);

                    $("#div_datos_productos .form-group.col-md-4").show();
                    $("#precio_venta").val(result.precio_lista);
                    $("#btnCrearCotizacion").show();
                }
            });
        } else {
            $("#modelo").val("");
            $("#costo_basico").val("");
            $("#precio_venta").val("");
            $("#tipo_producto").val("").prop("disabled", false);
            $("#descripcion_producto").html("");
            $("#precio_lista").val("");
            $("#costo").val("").prop("disabled", false);
            $("#div_datos_productos .form-group.col-md-4").hide();
            $("#horas_motor").val("").prop("disabled", true);
            $("#horas_trilla").val("").prop("disabled", true);
            $("#precio_sin_canje").val("").prop("disabled", true);
            $("#costo_usado").val("").prop("disabled", true);
            $("#btnCrearCotizacion").hide();
        }
    };


    var actualizarCosto = function () {
        var precio_lista = parseFloat($("#precio_lista").val());
        var descuento = isNaN(parseFloat($("#descuento").val())) ? 0 : parseFloat($("#descuento").val());

        if (precio_lista > 0 && descuento >= 0 && descuento <= 100) {
            var precio_venta = (100 - descuento) * precio_lista / 100;
            $("#precio_venta").val(precio_venta);
        }
    };


    $("#precio_lista, #descuento").blur(function () {
        actualizarCosto();
    });

    $(function () {
        if ($("#producto_id").val() != "") {
            actualizarDatosProducto($("#producto_id").val());
        }


        $("#btnAgregarProductoUsado").click(function () {
            $("#modal-create-producto").modal("toggle");
        });

        /**
         * Autosuggest de clientes
         */
        $("#modelo").autocomplete({
            source: function (request, response) {

                $.ajax({
                    url: BASE_URL + "/as/productosUsados",
                    data: {query: request.term},
                    success: function (data) {

                        var transformed = $.map(data, function (el) {
                            return {
                                label: el.modelo,
                                id: el.id
                            };
                        });
                        response(transformed);
                    },
                    error: function () {
                        response([]);
                        actualizarDatosProducto(0);
                    }
                });
            },
            select: function (e, ui) {
                if (parseInt(ui.item.id) > 0) {
                    $('#producto_id').val(ui.item.id);
                    actualizarDatosProducto(ui.item.id);
                } else {
                    $('#producto_id').val("");
                    actualizarDatosProducto(0);
                }
            }, change: function (event, ui) {
                if (ui.item === null) {
                    if ($('#producto_id').val() != "") {
                        $('#producto_id').val("");
                        actualizarDatosProducto(0);
                    }
                }
            }

        });


    });
</script>

@include('producto.new_producto_modal')
