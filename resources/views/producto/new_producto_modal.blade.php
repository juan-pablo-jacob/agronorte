<!--Modal Creación registro-->
<div class="modal fade" id="modal-create-producto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Crear Producto Usado</h4>
            </div>
            <div class="modal-body">
                <form id="creacion_modal_producto" action="{{url("/producto/creacion_agil")}}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}

                    <input type="hidden" name="is_nuevo" value="0">

                    <div class="form-group col-md-12">
                        <label>Marca</label>
                        <select name="marca_id" class="form-control">
                            <option value="">&lt;Seleccione&gt;</option>
                            @foreach($marcas as $marca)
                                <option value="{{$marca->id}}">{{$marca->marca}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-12">
                        <label>Tipo Producto *</label>
                        <select name="tipo_producto_id" class="form-control">
                            <option value="">&lt;Seleccione&gt;</option>
                            @foreach($tipo_productos as $tipo_producto)
                                <option value="{{$tipo_producto->id}}">{{$tipo_producto->tipo_producto}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-12">
                        <label>Modelo *</label>
                        <input type="text" class="form-control" name="modelo" >
                    </div>

                    <div class="form-group col-md-12">
                        <label>Precio Lista *</label>
                        <input type="text" class="form-control" name="precio_lista" >
                    </div>

                    <div class="form-group col-md-12">
                        <label>Costo Usado</label>
                        <input type="text" class="form-control" name="costo_usado" >
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnNewProductoUsado">Crear producto</button>
            </div>
        </div>
    </div>
</div>

<script>


    $("#btnNewProductoUsado").click(function (e) {
        e.preventDefault();
        //Envío de formulario por AJAX
        var url = $("#creacion_modal_producto").attr("action");
        var data = $("#creacion_modal_producto").serialize();

        $.post(url, data, function (result) {
            if (result.result == false) {
                $.each(result.errors, function (index, value) {
                    alertify.error(value[0]);
                });
            } else {
                $("#modal-create-producto").modal("toggle");
                alertify.success(result.msg);
                actualizarDatosProducto(result.producto.id);
                $("#modelo").val(result.producto.modelo);
                $("#producto_id").val(result.producto.id);

            }
        });
    });

</script>