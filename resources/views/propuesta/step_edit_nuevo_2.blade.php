<div class="content-box">
    <h3 class="content-box-header bg-default">
        Venta Producto Nuevo
    </h3>
    <div class="content-box-wrapper">
        <form role="form" id="form_venta_nuevo" action="" method="post"
              enctype="multipart/form-data">
            {{ csrf_field() }}

            <h4 class="font-gray font-size-16"><strong>Datos Producto *</strong></h4>
            <input type="hidden" name="producto_id" id="producto_id" value="{{old("producto_id")}}"/>

            <div id="div_datos_clientes">
                <div class="form-group col-md-4">
                    <label>Modelo</label>
                    <input type="text" class="form-control" id="CUIT" >
                </div>

                <div class="form-group col-md-4" id="div_razon_social" style="display: none">
                    <label>Razón social</label>
                    <input type="text" class="form-control" id="razon_social">
                </div>

                <div class="form-group col-md-4" id="div_telefono" style="display: none">
                    <label>Teléfono</label>
                    <input type="text" class="form-control" id="telefono">
                </div>

                <div class="form-group col-md-4" id="div_email" style="display: none">
                    <label>Email</label>
                    <input type="email" class="form-control" id="email">
                </div>

                <div class="form-group col-md-4" id="div_provincia" style="display: none">
                    <label>Provincia</label>
                    <input type="text" class="form-control" id="provincia">
                </div>

                <div class="form-group col-md-4" id="div_localidad" style="display: none">
                    <label>Localidad</label>
                    <input type="text" class="form-control" id="localidad">
                </div>

                <div class="form-group col-md-4" id="div_direccion" style="display: none">
                    <label>Dirección</label>
                    <input type="text" class="form-control" id="direccion">
                </div>

            </div>


            <div class="clearfix"></div>

            <div class="form-group col-md-12">
                <button type="submit" class="btn btn-primary"><i class="glyph-icon icon-save"></i>&nbsp;Guardar
                </button>
            </div>
        </form>
    </div>

</div>

<script type="text/javascript">

</script>
