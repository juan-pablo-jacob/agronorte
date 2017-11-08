@extends('layouts.app')

@section('content')

    @include('admin.partials.mensajes')

    <div id="page-nav">
        <ul id="page-subnav" style="text-align: left!important;">
            <li><a href="{{url("/users")}}" title="Listado Usuarios"><span>Listado Usuarios &raquo;</span></a></li>
            <li><a href="#" title="Crear Usuario"><span>Crear Usuario</span></a></li>
        </ul>
    </div>
    <div id="page-content">
        <form action="{{url("/users")}}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="content-box">
                <h3 class="content-box-header bg-default">
                    <i class="glyph-icon icon-elusive-basket"></i>
                    Crear Usuario
                </h3>
                <div class="content-box-wrapper">
                    <h4 class="font-gray font-size-16"><strong>Datos Usuario</strong></h4>
                    <div class="form-group col-md-4">
                        <label>Nombre Usuario</label>
                        <input type="text" class="form-control" name="name" value="{{old('name')}}">
                    </div>

                    <div class="form-group col-md-4">
                        <label>Email</label>
                        <input type="email" class="form-control" name="email" value="{{old('email')}}">
                    </div>

                    <div class="form-group col-md-4">
                        <label>Nombre</label>
                        <input type="text" class="form-control" name="nombre" value="{{old('nombre')}}">
                    </div>

                    <div class="form-group col-md-4">
                        <label>Apellido</label>
                        <input type="text" class="form-control" name="apellido" value="{{old('apellido')}}">
                    </div>

                    <div class="form-group col-md-4">
                        <label>Password</label>
                        <input type="password" class="form-control" name="password" value="">
                    </div>

                    <div class="form-group col-md-2">
                        <label>Celular</label>
                        <input type="text" class="form-control" name="cel_codigo_area" id="cel_codigo_area" placeholder="Característica" value="{{old('cel_codigo_area')}}">
                    </div>
                    <div class="form-group col-md-2">
                        <label>&nbsp;</label>
                        <input type="text" class="form-control" name="cel_numero" id="cel_numero" placeholder="Número" value="{{old('cel_numero')}}">
                    </div>

                    <div class="clearfix">&nbsp;</div>
                    <div class="divider"></div>
                    <h4 class="font-gray font-size-16"><strong>Datos Ubicación</strong></h4>
                    <div class="form-group col-md-4">
                        <label>Provincia</label>
                        <select id="provincia_id" name="provincia_id" class="form-control">
                            <option value="">&lt;Seleccione&gt;</option>
                            @foreach($provincias as $provincia)
                                @if(old('provincia_id') == $provincia->id)
                                    <option value="{{$provincia->id}}" selected>{{$provincia->provincia}}</option>
                                @else
                                    <option value="{{$provincia->id}}">{{$provincia->provincia}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label>Localidad</label>
                        <input type="text" class="form-control" name="localidad" value="{{old('localidad')}}">
                    </div>

                    <div class="form-group col-md-2">
                        <label>Dirección</label>
                        <input type="text" class="form-control" name="calle" placeholder="Nombre Calle" value="{{old('calle')}}">
                    </div>

                    <div class="form-group col-md-2">
                        <label>&nbsp;</label>
                        <input type="text" class="form-control" name="numero_calle" placeholder="N° calle" value="{{old('numero_calle')}}">
                    </div>

                    <div class="form-group col-md-4">
                        <label>Administrador</label>
                        <input type="checkbox" name="is_admin" class="form-control input-switch" checked data-size="large" data-on-text="Si" data-off-text="No">
                    </div>

                    <div class="clearfix">&nbsp;</div>

                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp;Guardar</button>
                    <a href="{{url("/users")}}" class="btn btn-danger"><i class="fa fa-arrow-left"></i>&nbsp;Volver</a>
                </div>
            </div>
        </form>
    </div>

    <script type="text/javascript">
        $(function () {
            $('.input-switch').bootstrapSwitch();

            $("#cel_codigo_area").inputmask({
                mask: function () {
                    return ["999[9]"];
                }
            });

            $("#cel_numero").inputmask({
                mask: function () {
                    return ["999999[9]"];
                }
            });
        });
    </script>
@endsection