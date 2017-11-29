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


            <h4 class="font-gray font-size-16"><strong>Datos Cotización</strong></h4>
            <div class="form-group col-md-4">
                <label for="exampleInputEmail1">Fecha</label>
                <div class="input-prepend input-group ">
                    <span class="add-on input-group-addon">
                        <i class="glyph-icon icon-calendar"></i>
                    </span>
                    @if(old("fecha_entrega") == "")
                        <input type="text" class="bootstrap-datepicker form-control" name="fecha_entrega"
                               placeholder="dd/mm/aaaa"
                               data-date-format="mm/dd/yy">
                    @else
                        <input type="text" class="bootstrap-datepicker form-control" name="fecha_entrega"
                               placeholder="dd/mm/aaaa"
                               data-date-format="mm/dd/yy" value="{{date('d/m/Y',strtotime(old("fecha_entrega")))}}">
                    @endif
                </div>
            </div>


            <div class="clearfix">&nbsp;</div>
            <div class="divider"></div>

            <h4 class="font-gray font-size-16"><strong>Datos Producto *</strong></h4>
            <div id="div_datos_productos">
                <input type="hidden" id="costo_basico" name="costo_basico_producto">

                <div class="form-group col-md-4">
                    <label>Modelo</label>
                    <input type="text" class="form-control" id="modelo"/>
                </div>

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
                    <input type="number" class="form-control" id="precio_lista" name="precio_lista_producto">
                </div>

                <div class="form-group col-md-4" style="display: none">
                    <label>Descuento</label>
                    <input type="number" class="form-control" id="descuento" name="descuento" placeholder="1 % - 100 %">
                </div>

                <div class="form-group col-md-4" style="display: none">
                    <label>Descripcion descuento</label>
                    <textarea name="descripcion_descuento" placeholder="Ingrese descripción por el descuento" rows="3"
                              class="form-control textarea-sm">{{old("descripcion_descuento")}}</textarea>
                </div>

                <div class="form-group col-md-4" style="display: none">
                    <label>Precio venta</label>
                    <input type="text" class="form-control" id="precio_venta">
                </div>

            </div>


            <div class="clearfix">&nbsp;</div>
            <div class="divider"></div>
            <div class="form-group col-md-12">
                <button type="submit" id="btnCrearCotizacion" style="display: none" class="btn btn-primary"><i class="glyph-icon icon-save"></i>&nbsp;Crear Cotización
                </button>
            </div>
        </form>
    </div>

</div>

<form role="form" id="form_buscar_incentivos_productos" action="" method="get" enctype="multipart/form-data"
      onsubmit="return false;">

</form>

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

    };



    $("#precio_lista, #descuento").blur(function () {
        actualizarCosto();
    });

    $(function () {
        if ($("#producto_id").val() != "") {
            actualizarDatosProducto($("#producto_id").val());
        }


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
