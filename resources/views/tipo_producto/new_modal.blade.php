<!--Modal Creación registro-->
<div class="modal fade" id="modal-create" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Crear Tipo Producto</h4>
            </div>
            <div class="modal-body">
                <form id="creacion_modal" action="{{url("/tipo_producto")}}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}

                    <div class="form-group col-md-12">
                        <label>Tipo Producto *</label>
                        <input type="text" class="form-control" name="tipo_producto">
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnNew">Guardar</button>
            </div>
        </div>
    </div>
</div>

<script>
    $("#btnNew").click(function (e) {
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
                alertify.success(result.msg);
                window.location.href = "";
            }
        });
    });

</script>