<!--Modal Creación registro-->
<div class="modal fade" id="modal-create-cliente" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Crear Cliente</h4>
            </div>
            <div class="modal-body">
                <form id="creacion_modal" action="{{url("/cliente/creacion_agil")}}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}

                    <div class="form-group col-md-12">
                        <label>Razón Social *</label>
                        <input type="text" class="form-control" name="razon_social" >
                    </div>

                    <div class="form-group col-md-12">
                        <label>CUIT *</label>
                        <input type="text" class="form-control" id="CUIT_create" name="CUIT" >
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnNewCliente">Crear cliente</button>
            </div>
        </div>
    </div>
</div>

<script>
    $("#CUIT_create").inputmask({
        mask: function () {
            return ["99-99999999-9"];
        }
    });

    $("#btnNewCliente").click(function (e) {
        e.preventDefault();
        //Envío de formulario por AJAX
        var url = $("#creacion_modal").attr("action");
        var data = $("#creacion_modal").serialize();

        $.post(url, data, function (result) {
            if (result.result == false) {
                $.each(result.errors, function (index, value) {
                    alertify.error(value[0]);
                });
            } else {
                $("#modal-create-cliente").modal("toggle");
                alertify.success(result.msg);
                actualizarDatosCliente(result.cliente.id);
                $("#CUIT").val(result.cliente.razon_social + " - " + result.cliente.CUIT);

            }
        });
    });

</script>