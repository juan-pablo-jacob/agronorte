<div class="content-box">
    <h3 class="content-box-header bg-default">
        Venta Producto <strong>Usado</strong>
    </h3>
    <div class="content-box-wrapper">
        <form role="form" id="form_toma_producto" action="{{url("/cotizacion")}}" method="post"
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

            <input type="hidden" id="costo_basico" name="costo_basico_producto">

            <div class="form-group col-md-4">
                <label>Marca</label>
                <select id="marca_id" name="marca_id" class="form-control">
                    <option value="">&lt;Seleccione&gt;</option>
                    @foreach($marcas as $marca)
                        @if($marca->id == old('marca_id'))
                            <option value="{{$marca->id}}" selected>{{$marca->marca}}</option>
                        @else
                            <option value="{{$marca->id}}">{{$marca->marca}}</option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div class="form-group col-md-4">
                <label>Tipo Producto</label>
                <select id="tipo_producto_id" name="tipo_producto_id" class="form-control">
                    <option value="">&lt;Seleccione&gt;</option>
                    @foreach($tipo_productos as $tipo_producto)
                        @if($tipo_producto->id == old("tipo_producto_id"))
                            <option value="{{$tipo_producto->id}}"
                                    selected>{{$tipo_producto->tipo_producto}}</option>
                        @else
                            <option value="{{$tipo_producto->id}}">{{$tipo_producto->tipo_producto}}</option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div class="form-group col-md-4">
                <label>Modelo</label>
                <input type="text" class="form-control" name="modelo" value="{{old('modelo')}}">
            </div>

            <div class="form-group col-md-4">
                <label>Descripción</label>
                <textarea name="descripcion" id="descripcion" placeholder="Ingrese descripción" rows="3"
                          class="form-control textarea-sm">{{old("descripcion")}}</textarea>
            </div>

            <div class="form-group col-md-4">
                <label>Año</label>
                <input type="number" class="form-control" name="anio" value="{{old('anio')}}">
            </div>

            <div class="form-group col-md-4">
                <label>Horas motor</label>
                <input type="number" class="form-control" name="horas_motor" value="{{old('costo_usado')}}">
            </div>

            <div class="form-group col-md-4">
                <label>Horas Trilla</label>
                <input type="number" class="form-control" name="horas_trilla"
                       value="{{old('horas_trilla')}}">
            </div>

            <div class="form-group col-md-4">
                <label>Tracción</label>
                <input type="text" class="form-control" name="traccion" value="{{old('traccion')}}">
            </div>

            <div class="form-group col-md-4">
                <label>Recolector</label>
                <input type="text" class="form-control" name="recolector" value="{{old('recolector')}}">
            </div>

            <div class="form-group col-md-4">
                <label>Piloto Mapeo</label>
                <input type="text" class="form-control" name="piloto_mapeo" value="{{old('piloto_mapeo')}}">
            </div>

            <div class="form-group col-md-4">
                <label>Ex Usuario</label>
                <input type="text" class="form-control" name="ex_usuario" value="{{old('ex_usuario')}}">
            </div>

            <div class="form-group col-md-4">
                <label>Ubicación</label>
                <input type="text" class="form-control" name="ubicacion" value="{{old('ubicacion')}}">
            </div>

            <div class="form-group col-md-4">
                <label>Estado</label>
                <input type="text" class="form-control" name="estado" value="{{old('estado')}}">
            </div>

            <div class="form-group col-md-4">
                <label>Disponible</label>
                <input type="text" class="form-control" name="disponible" value="{{old('disponible')}}">
            </div>

            <div class="clearfix">&nbsp;</div>
            <div class="divider"></div>

            <h4 class="font-gray font-size-16"><strong>Detalle Precio Producto</strong></h4>

            <div class="form-group col-md-4">
                <label>Precio Lista</label>
                <input type="number" class="form-control" name="precio_lista" value="{{old('precio_lista')}}">
            </div>

            <div class="form-group col-md-4 div_producto_usado">
                <label>Costo Usado</label>
                <input type="number" class="form-control" name="costo_usado" value="{{old('costo_usado')}}">
            </div>

            <div class="form-group col-md-4 div_producto_usado">
                <label>Precio sin canje</label>
                <input type="number" class="form-control" name="precio_sin_canje"
                       value="{{old('precio_sin_canje')}}">
            </div>

            <div class="clearfix">&nbsp;</div>
            <div class="divider"></div>
            <div class="form-group col-md-12">
                <button type="submit" id="btnCrearCotizacion" style="display: none" class="btn btn-primary"><i
                            class="glyph-icon icon-save"></i>&nbsp;Crear Cotización
                </button>
            </div>
        </form>
    </div>

</div>


<script type="text/javascript">

    var actualizarCosto = function () {
        var precio_lista = parseFloat($("#precio_lista").val());
        var descuento = isNaN(parseFloat($("#descuento").val())) ? 0 : parseFloat($("#descuento").val());

        if (precio_lista > 0 && descuento >= 0 && descuento <= 100) {
            var precio_venta = (100 - descuento) * precio_lista / 100;
            $("#precio_venta").val(precio_venta);
        }
    };


    $("#precio_lista, #descuento").blur(function () {
        actualizarCosto();
    });

    $(function () {


        $("#btnAgregarProductoUsado").click(function () {
            $("#modal-create-producto").modal("toggle");
        });


    });
</script>

@include('producto.new_producto_modal')
