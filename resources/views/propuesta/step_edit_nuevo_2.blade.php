<div class="content-box">
    <h3 class="content-box-header bg-default">
        Venta Producto <strong>Nuevo</strong>
    </h3>
    <div class="content-box-wrapper">
        <form role="form" id="form_edit_venta_nuevo" action="{{url("/cotizacion/" . $cotizacion_edit->id)}}" method="post"
              enctype="multipart/form-data">
            {{ csrf_field() }}
            {{ method_field('PUT') }}

            <input type="hidden" id="cotizacion_id" value="{{$cotizacion_edit->id}}">

            <input type="hidden" name="producto_id" id="producto_id" value="{{$cotizacion_edit->producto_id}}">
            <input type="hidden" name="propuesta_negocio_id" value="{{$propuesta->id}}">
            <input type="hidden" name="tipo_propuesta_negocio_id" value="{{$propuesta->tipo_propuesta_negocio_id}}">


            <h4 class="font-gray font-size-16"><strong>Datos Producto *</strong></h4>
            <div id="div_datos_productos">
                <input type="hidden" id="costo_basico" name="costo_basico_producto">

                <div class="form-group col-md-4">
                    <label>Modelo</label>
                    <input type="text" class="form-control" id="modelo"/>
                </div>

                <div class="form-group col-md-4" style="display: none">
                    <label>Tipo de producto</label>
                    <input type="text" class="form-control" id="tipo_producto" >
                </div>

                <div class="form-group col-md-4" style="display: none">
                    <label>Descripción</label>
                    <textarea name="observacion" id="descripcion_producto" placeholder="Ingrese descripción producto"
                              rows="3"
                              class="form-control textarea-sm">{{$cotizacion_edit->observacion}}</textarea>
                </div>

                <div id="div_table_incentivos_productos">

                </div>

                <div class="form-group col-md-4" style="display: none">
                    <label>Precio Lista</label>
                    <input type="number" class="form-control" id="precio_lista" name="precio_lista_producto" value="{{$cotizacion_edit->precio_lista_producto}}">
                </div>

                <div class="form-group col-md-4" style="display: none">
                    <label>Descuento</label>
                    <input type="number" class="form-control" id="descuento" name="descuento" placeholder="1 % - 100 %" value="{{$cotizacion_edit->descuento}}">
                </div>

                <div class="form-group col-md-4" style="display: none">
                    <label>Descripcion descuento</label>
                    <textarea name="descripcion_descuento" placeholder="Ingrese descripción por el descuento" rows="3"
                              class="form-control textarea-sm">{{$cotizacion_edit->descripcion_descuento}}</textarea>
                </div>

                <div class="form-group col-md-4" style="display: none">
                    <label>Precio venta</label>
                    <input type="text" class="form-control" id="precio_venta" value="{{$cotizacion_edit->precio_venta}}" disabled>
                </div>

            </div>


            <div class="clearfix">&nbsp;</div>
            <div class="divider"></div>
            <div class="form-group col-md-12">
                <button type="submit" id="btnEditarCotizacion"  class="btn btn-primary"><i class="glyph-icon icon-save"></i>&nbsp;Editar Cotización
                </button>
                <button id="btnCancelarActuzalicionVenta" class="btn btn-success"><i
                            class="glyph-icon icon-elusive-cancel"></i>&nbsp;Cancelar
                </button>
            </div>
        </form>
    </div>

</div>

<form role="form" id="form_buscar_incentivos_productos" action="" method="get" enctype="multipart/form-data"
      onsubmit="return false;">

</form>

<script type="text/javascript">

    $("#btnCancelarActuzalicionVenta").click(function () {
        window.location.href = BASE_URL + "/propuesta/" + $("#propuesta_id").val() + "/edit"
    });

    /*
     * Function que rearma las tablas de incentivos de prodcutos
     */
    var getListIncentivosProducto = function () {
        var $_form = $("#form_buscar_incentivos_productos");

        var params = {
            url: $_form.attr("action"),
            data: $_form.serialize(),
            method: "GET",
            function_success: function (data) {
                if (data) {
                    $("#div_table_incentivos_productos").html(data);
                    /**
                     * Delegación de eventos para la tabla
                     */
                    $('#dynamic-table-incentivo-producto').dataTable({"sort": false, "paging": false});
                    $("#dynamic-table-incentivo-producto_length").hide();
                    $("#dynamic-table-incentivo-producto_filter").hide();

                    $(".check_grilla").click(function () {
                        actualizarCosto();
                    });
                    actualizarCosto();
                }
            },
            function_error: function (data) {
            }
        };

        ajax_form(params);
    };


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
                    $("#div_datos_productos .form-group.col-md-4").show();
                    $('#form_buscar_incentivos_productos').attr('action', BASE_URL + '/getIncetivosProductosEdit/' + result.id + '-' + $("#cotizacion_id").val());
                    $("#precio_venta").val(result.precio_lista);
                    getListIncentivosProducto();

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
            $('#form_buscar_incentivos_productos').attr('action', "");
            $("#div_table_incentivos_productos").html("");
        }
    };

    var getChecked = function () {
        var array_id = [];
        $.each($(".check_grilla:checked"), function (index, value) {
            array_id[index] = $(this).data("value");
        });
        return array_id;
    };

    var actualizarCosto = function () {
        var precio_lista = parseFloat($("#precio_lista").val());
        var descuento = isNaN(parseFloat($("#descuento").val())) ? 0 : parseFloat($("#descuento").val());
        var costo_basico = isNaN(parseFloat($("#costo_basico").val())) ? 0 : parseFloat($("#costo_basico").val());
        var porcentajes_incentivos = getChecked();

        if (precio_lista > 0) {
            var precio_venta = (100 - descuento) * precio_lista / 100;
            for (var i = 0; i < porcentajes_incentivos.length; i++) {
                var porc_inc = isNaN(parseFloat(porcentajes_incentivos[i])) ? 0 : parseFloat(porcentajes_incentivos[i]);
                precio_venta = precio_venta - (porc_inc) * costo_basico / 100;
            }

            $("#precio_venta").val(precio_venta);
        } else {
            alertify.error("El precio de lista debe ser mayor a Cero");
        }
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
                    url: BASE_URL + "/as/productosNuevos",
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
