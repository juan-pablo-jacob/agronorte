@extends('layouts.app')

@section('content')


    @include('admin.partials.mensajes')

    <div id="page-nav">
        <ul id="page-subnav" style="text-align: left!important;">
            <li><a href="{{url("/producto")}}" title="Listado productos"><span>Listado Productos &raquo;</span></a></li>
            <li><a href="#" title="Crear producto"><span>Crear Producto</span></a></li>
        </ul>
    </div>
    <div id="page-content">
        <form action="{{url("/producto")}}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="content-box">
                <h3 class="content-box-header bg-default">
                    <i class="glyph-icon icon-elusive-basket"></i>
                    Editar producto
                </h3>
                <div class="content-box-wrapper">

                    <h4 class="font-gray font-size-16"><strong>Información Producto</strong></h4>

                    <div class="form-group col-md-4">
                        <label>Nuevo/Usado</label>
                        <select id="is_nuevo" name="is_nuevo" class="form-control">
                            @if(is_null(old('is_nuevo')) || old('is_nuevo') == 1)
                                <option value="1" selected>Nuevo</option>
                                <option value="0">Usado</option>
                            @elseif(!is_null(old('is_nuevo')) || old('is_nuevo') == 0)
                                <option value="1">Nuevo</option>
                                <option value="0" selected>Usado</option>
                            @endif
                        </select>
                    </div>


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

                    <!-- Detalles Usados-->

                    <div class="div_producto_usado">

                        <div class="clearfix">&nbsp;</div>
                        <div class="divider"></div>

                        <h4 class="font-gray font-size-16"><strong>Detalle producto usado</strong></h4>

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


                        <div class="form-group col-md-4">
                            <label>Vendedor</label>
                            <select id="usuario_vendedor_id" name="usuario_vendedor_id" class="form-control">
                                <option value="">&lt;Seleccione&gt;</option>
                                @foreach($usuarios_vendedores as $vendedor)
                                    @if($vendedor->id == old("usuario_vendedor_id"))
                                        <option value="{{$vendedor->id}}"
                                                selected>{{$vendedor->nombre}} {{$vendedor->apellido}}</option>
                                    @else
                                        <option value="{{$vendedor->id}}">{{$vendedor->nombre}} {{$vendedor->apellido}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- FIN Detalles Usados-->

                    <div class="clearfix">&nbsp;</div>
                    <div class="divider"></div>

                    <h4 class="font-gray font-size-16"><strong>Detalle Precio Producto</strong></h4>

                    <div class="form-group col-md-4">
                        <label>Precio Lista</label>
                        <input type="number" class="form-control" name="precio_lista" value="{{old('razon_social')}}">
                    </div>

                    <div class="form-group col-md-4 div_producto_nuevo">
                        <label>Bonificación Básica</label>
                        @if(old("bonificacion_basica") != "")
                            <input type="number" class="form-control" name="bonificacion_basica"
                                   value="{{old('bonificacion_basica')}}">
                        @else
                            <input type="number" class="form-control" name="bonificacion_basica"
                                   value="{{$parametros_sistema->bonificacion_basica}}">
                        @endif
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

                    @include('admin.upload.upload_new')

                    <div class="clearfix">&nbsp;</div>

                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp;Guardar</button>
                    <a href="{{url("/producto")}}" class="btn btn-danger"><i
                                class="fa fa-arrow-left"></i>&nbsp;Volver</a>
                </div>
            </div>
        </form>
    </div>

    <script>
        var checkIsNuevo = function(){
            if ($("#is_nuevo").val() == 1) {
                $(".div_producto_usado").hide();
                $(".div_producto_nuevo").show();
            } else {
                $(".div_producto_usado").show();
                $(".div_producto_nuevo").hide();
            }
        }

        $("#is_nuevo").change(function () {
            checkIsNuevo();
        });

        checkIsNuevo();

    </script>

@endsection