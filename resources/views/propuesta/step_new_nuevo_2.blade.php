<div class="content-box">
    <h3 class="content-box-header bg-default">
        Venta Producto Nuevo
    </h3>
    <div class="content-box-wrapper">
        <form role="form" id="form_venta_nuevo" action="{{url("/cotizacion")}}" method="post"
              enctype="multipart/form-data">
            {{ csrf_field() }}


            <input type="hidden" name="producto_id" id="producto_id" value="{{old('producto_id')}}">
            <input type="hidden" name="propuesta_id" value="{{$propuesta->id}}">


            <h4 class="font-gray font-size-16"><strong>Datos Cotización</strong></h4>
            <div class="form-group col-md-4">
                <label for="exampleInputEmail1">Fecha Entrega</label>
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

            <div class="form-group col-md-4">
                <label>Descuento</label>
                <input type="number" class="form-control" name="descuento" placeholder="1 % - 100 %">
            </div>

            <div class="form-group col-md-4">
                <label>Descripcion descuento</label>
                <textarea name="descripcion_descuento" placeholder="Ingrese descripción por el descuento" rows="3"
                          class="form-control textarea-sm">{{old("descripcion_descuento")}}</textarea>
            </div>




            <div class="clearfix">&nbsp;</div>
            <div class="divider"></div>

            <h4 class="font-gray font-size-16"><strong>Datos Producto *</strong></h4>
            <div id="div_datos_productos">
                <div class="form-group col-md-4">
                    <label>Modelo</label>
                    <input type="text" class="form-control" id="modelo" />
                </div>

                <div class="form-group col-md-4" id="div_razon_social" style="display: none">
                    <label>Tipo de producto</label>
                    <input type="text" class="form-control" id="tipo_producto">
                </div>

                <div class="form-group col-md-4" id="div_telefono" style="display: none">
                    <label>Descripción</label>
                    <textarea name="descripcion" id="descripcion_producto" placeholder="Ingrese descripción producto" rows="3"
                              class="form-control textarea-sm">{{old("descripcion")}}</textarea>
                </div>

                <div class="form-group col-md-4" id="div_email" style="display: none">
                    <label>Precio Lista</label>
                    <input type="email" class="form-control" id="precio_lista">
                </div>

                <div class="form-group col-md-4" id="div_provincia" style="display: none">
                    <label>Costo</label>
                    <input type="text" class="form-control" id="costo">
                </div>

            </div>

            <div id="div_table_incentivos_productos">

            </div>


            <div class="clearfix">&nbsp;</div>
            <div class="divider"></div>
            <div class="form-group col-md-12">
                <button type="submit" class="btn btn-primary"><i class="glyph-icon icon-save"></i>&nbsp;Crear Cotización
                </button>
            </div>
        </form>
    </div>

</div>

<form role="form" id="form_buscar_incentivos_productos" action="" method="get" enctype="multipart/form-data" onsubmit="return false;">

</form>

<script type="text/javascript">

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
                    $("#modelo").val(result.modelo).prop("disabled", true);
                    $("#tipo_producto").val(result.tipo_producto).prop("disabled", true);
                    $("#descripcion_producto").html(result.descripcion);
                    $("#precio_lista").val(result.precio_lista).prop("disabled", true);
                    $("#costo").val(result.costo).prop("disabled", true);
                    $("#div_datos_productos .form-group.col-md-4").show();
                    $('#form_buscar_incentivos_productos').attr('action', BASE_URL + '/getIncetivosProductos/' + result.id);
                    getListIncentivosProducto();
                }
            });
        } else {
            $("#modelo").val("").prop("disabled", false);
            $("#tipo_producto").val("").prop("disabled", false);
            $("#descripcion_producto").html("");
            $("#precio_lista").val("").prop("disabled", false);
            $("#costo").val("").prop("disabled", false);
            $("#div_datos_productos .form-group.col-md-4").hide();
            $('#form_buscar_incentivos_productos').attr('action', "");
            $("#div_table_incentivos_productos").html("");
        }
    };

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
