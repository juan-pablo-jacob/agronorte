
<!--Modal Actualización registro-->
<div class="modal fade" id="modal-update" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Editar Marca</h4>
            </div>
            <div class="modal-body">
                <form id="actualizacion_modal" action="" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}

                    <div class="form-group col-md-12">
                        <label>Marca *</label>
                        <input type="text" class="form-control" name="marca"> 
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnUpdate">Guardar</button>
            </div>
        </div>
    </div>
</div>

<script>
    $("#btnUpdate").click(function (e) {
        e.preventDefault();
        //Envío de formulario por AJAX
        var url = $("#actualizacion_modal").attr("action");
        var data = $("#actualizacion_modal").serialize();

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