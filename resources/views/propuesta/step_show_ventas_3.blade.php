<div class="content-box">
    <h3 class="content-box-header bg-default">
        Cotizaciones <strong>Producto Nuevo</strong>
    </h3>
    <div class="content-box-wrapper">
        <form role="form" id="form_lista_cotizaciones_venta" action="{{url("/cotizacion")}}" method="get"
              enctype="multipart/form-data">

            <input type="hidden" name="propuesta_negocio_id" value="{{$propuesta->id}}">
            <input type="hidden" name="is_toma" value="0">

        </form>



        <div id="div_tabla_cotizaciones_venta"></div>
    </div>

</div>

<script>
    /*
     * Function que rearma las tablas de cotizaciones en la venta
     */
    var getListCotizacionesVentas = function () {
        var $_form = $("#form_lista_cotizaciones_venta");


        $.ajax({
            url: $_form.attr("action"),
            data: $_form.serialize(),
            method: "GET",
            success: function (data) {
                if (data) {
                    $("#div_tabla_cotizaciones_venta").html(data);
                    /**
                     * Delegación de eventos para la tabla
                     */
                    $('#dynamic-table-cotizacion-venta').dataTable({"sort": false, "paging": false});
                    $("#dynamic-table-cotizacion-venta_length").hide();
                    $("#dynamic-table-cotizacion-venta_filter").hide();

                    $(".btn-borrar-cotizacion").click(function () {
                        var id = $(this).data("id");
                        $('#cotizacion-delete-form').attr('action', BASE_URL + '/cotizacion/' + id);
                        $('#modal-delete-cotizacion-confirmation').modal('toggle');
                    });

                    $(".btn-confirm-cotizacion-delete").click(function () {
                        $('#cotizacion-delete-form').submit();
                    });
                }
            },
            error: function (data) {
            }
        });

    };
    getListCotizacionesVentas();
</script>