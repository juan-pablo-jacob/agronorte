<div class="content-box">
    <h3 class="content-box-header bg-default">
        Cotizaciones <strong>Productos Tomados</strong>
    </h3>
    <div class="content-box-wrapper">
        <form role="form" id="form_lista_cotizaciones_toma" action="{{url("/cotizacion")}}" method="get"
              enctype="multipart/form-data">

            <input type="hidden" name="propuesta_negocio_id" value="{{$propuesta->id}}">
            <input type="hidden" name="is_toma" value="1">

        </form>



        <div id="div_tabla_cotizaciones_toma"></div>
    </div>

</div>

<script>
    /*
     * Function que rearma las tablas de cotizaciones en la venta
     */
    var getListCotizacionesVentas = function () {
        var $_form = $("#form_lista_cotizaciones_toma");


        $.ajax({
            url: $_form.attr("action"),
            data: $_form.serialize(),
            method: "GET",
            success: function (data) {
                if (data) {
                    $("#div_tabla_cotizaciones_toma").html(data);
                    /**
                     * Delegación de eventos para la tabla
                     */
                    $('#dynamic-table-cotizacion-toma').dataTable({"sort": false, "paging": false});
                    $("#dynamic-table-cotizacion-toma_length").hide();
                    $("#dynamic-table-cotizacion-toma_filter").hide();

                    $(".btn-borrar-cotizacion-toma").click(function () {
                        var id = $(this).data("id");
                        $('#cotizacion-toma-delete-form').attr('action', BASE_URL + '/cotizacion/' + id);
                        $('#modal-delete-cotizacion-toma-confirmation').modal('toggle');
                    });

                    $(".btn-confirm-cotizacion-toma-delete").click(function () {
                        $('#cotizacion-toma-delete-form').submit();
                    });
                }
            },
            error: function (data) {
            }
        });

    };
    getListCotizacionesVentas();
</script>