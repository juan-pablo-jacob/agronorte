<div class="content-box">
    <h3 class="content-box-header bg-default">
        Edición de producto <strong>Tomado</strong>
    </h3>
    <div class="content-box-wrapper">
        <form role="form" id="form_toma_producto" action="{{url("/cotizacion/" . $cotizacion_toma_edit->id)}}"
              method="post"
              enctype="multipart/form-data">
            {{ csrf_field() }}
            {{ method_field('PUT') }}

            <input type="hidden" id="cotizacion_toma_id" name="cotizacion_id" value="{{$cotizacion_toma_edit->id}}">
            <input type="hidden" name="is_toma" value="1">
            <input type="hidden" name="step" value="4">
            <input type="hidden" name="is_nuevo" value="0">
            <input type="hidden" name="producto_id" value="{{$cotizacion_toma_edit->producto_id}}">
            <input type="hidden" name="propuesta_negocio_id" value="{{$propuesta->id}}">
            <input type="hidden" name="tipo_propuesta_negocio_id" value="{{$propuesta->tipo_propuesta_negocio_id}}">

            <h4 class="font-gray font-size-16"><strong>Datos Producto *</strong></h4>

            <div class="form-group col-md-4">
                <label>Marca</label>
                <select id="marca_id_toma" class="form-control" disabled>
                    <option value="">&lt;Seleccione&gt;</option>
                    @foreach($marcas as $marca)
                        @if($marca->id == $cotizacion_toma_edit->marca_id)
                            <option value="{{$marca->id}}" selected>{{$marca->marca}}</option>
                        @else
                            <option value="{{$marca->id}}">{{$marca->marca}}</option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div class="form-group col-md-4">
                <label>Tipo Producto *</label>
                <select id="tipo_producto_id_toma" class="form-control" disabled>
                    <option value="">&lt;Seleccione&gt;</option>
                    @foreach($tipo_productos as $tipo_producto)
                        @if($tipo_producto->id == $cotizacion_toma_edit->tipo_producto_id)
                            <option value="{{$tipo_producto->id}}"
                                    selected>{{$tipo_producto->tipo_producto}}</option>
                        @else
                            <option value="{{$tipo_producto->id}}">{{$tipo_producto->tipo_producto}}</option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div class="form-group col-md-4">
                <label>Modelo *</label>
                <input type="text" class="form-control" value="{{$cotizacion_toma_edit->modelo}}" disabled>
            </div>

            <div class="form-group col-md-4">
                <label>Descripción</label>
                <textarea id="descripcion_toma" placeholder="Ingrese descripción" rows="3"
                          class="form-control textarea-sm" disabled>{{$cotizacion_toma_edit->observacion}}</textarea>
            </div>

            <div class="form-group col-md-4">
                <label>Año</label>
                <input type="number" class="form-control" value="{{$cotizacion_toma_edit->anio}}" disabled>
            </div>

            <div class="form-group col-md-4">
                <label>Horas motor</label>
                <input type="number" class="form-control" value="{{$cotizacion_toma_edit->costo_usado}}" disabled>
            </div>

            <div class="form-group col-md-4">
                <label>Horas Trilla</label>
                <input type="number" class="form-control"
                       value="{{$cotizacion_toma_edit->horas_trilla}}" disabled>
            </div>

            <div class="form-group col-md-4">
                <label>Tracción</label>
                <input type="text" class="form-control" value="{{$cotizacion_toma_edit->traccion}}" disabled>
            </div>

            <div class="form-group col-md-4">
                <label>Recolector</label>
                <input type="text" class="form-control" value="{{$cotizacion_toma_edit->recolector}}" disabled>
            </div>

            <div class="form-group col-md-4">
                <label>Piloto Mapeo</label>
                <input type="text" class="form-control" value="{{$cotizacion_toma_edit->piloto_mapeo}}" disabled>
            </div>

            <div class="form-group col-md-4">
                <label>Ex Usuario</label>
                <input type="text" class="form-control" value="{{$cotizacion_toma_edit->ex_usuario}}" disabled>
            </div>

            <div class="form-group col-md-4">
                <label>Ubicación</label>
                <input type="text" class="form-control" value="{{$cotizacion_toma_edit->ubicacion}}" disabled>
            </div>

            <div class="form-group col-md-4">
                <label>Estado</label>
                <input type="text" class="form-control" value="{{$cotizacion_toma_edit->estado}}" disabled>
            </div>

            <div class="form-group col-md-4">
                <label>Disponible</label>
                <input type="text" class="form-control" value="{{$cotizacion_toma_edit->disponible}}" disabled>
            </div>

            <div class="clearfix">&nbsp;</div>
            <div class="divider"></div>

            <h4 class="font-gray font-size-16"><strong>Detalle Precio Producto</strong></h4>

            <div class="form-group col-md-4 div_producto_usado">
                <label>Precio toma *</label>
                <input type="number" class="form-control" name="precio_toma"
                       value="{{$cotizacion_toma_edit->precio_toma}}">
            </div>


            <div class="clearfix">&nbsp;</div>
            <div class="divider"></div>
            <div class="form-group col-md-12">
                <button type="submit" id="btnActualizarCotizacionToma" class="btn btn-primary"><i
                            class="glyph-icon icon-save"></i>&nbsp;Modificar Cotización
                </button>
                <button id="btnCancelarActuzalicionToma" class="btn btn-success"><i
                            class="glyph-icon icon-elusive-cancel"></i>&nbsp;Cancelar
                </button>
            </div>
        </form>
    </div>

</div>


<script type="text/javascript">

    $(function () {
        $("#btnCancelarActuzalicionToma").click(function () {
            window.location.href = BASE_URL + "/propuesta/" + $("#propuesta_id").val() + "/edit"
        });

    });
</script>

