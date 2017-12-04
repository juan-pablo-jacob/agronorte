@extends('layouts.app')

@section('content')

    @include('admin.partials.mensajes')

    <div id="page-nav">
        <ul id="page-subnav" style="text-align: left!important;">
            <li><a href="{{url("/users")}}" title="Listado Usuarios"><span>Listado Usuarios &raquo;</span></a></li>
            <li><a href="#" title="Crear Usuario"><span>Editar Usuario</span></a></li>
        </ul>
    </div>
    <div id="page-content">
        <form action="{{url("/users/" . $user->id)}}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            {{ method_field('PUT') }}

            <div class="content-box">
                <h3 class="content-box-header bg-default">
                    <i class="glyph-icon icon-elusive-basket"></i>
                    Editar Usuario
                </h3>
                <div class="content-box-wrapper">
                    <h4 class="font-gray font-size-16"><strong>Datos Usuario</strong></h4>
                    <div class="form-group col-md-4">
                        <label>Nombre Usuario *</label>
                        <input type="text" class="form-control" name="name" value="{{$user->name}}">
                    </div>

                    <div class="form-group col-md-4">
                        <label>Email *</label>
                        <input type="email" class="form-control" name="email" value="{{$user->email}}">
                    </div>

                    <div class="form-group col-md-4">
                        <label>Nombre *</label>
                        <input type="text" class="form-control" name="nombre" value="{{$user->nombre}}">
                    </div>

                    <div class="form-group col-md-4">
                        <label>Apellido *</label>
                        <input type="text" class="form-control" name="apellido" value="{{$user->apellido}}">
                    </div>

                    <div class="form-group col-md-4">
                        <label>Password *</label>
                        <input type="password" class="form-control" name="password" value="">
                    </div>

                    <div class="form-group col-md-4">
                        <label>Celular</label>
                        <input type="text" class="form-control" name="cel_numero" id="cel_numero" placeholder="Número" value="{{$user->cel_numero}}">
                    </div>

                    <div class="form-group col-md-4">
                        <label>Tipo Usuario *</label>
                        <select id="tipo_usuario_id" name="tipo_usuario_id" class="form-control">
                            <option value="">&lt;Seleccione&gt;</option>
                            @foreach($tipos_usuario as $tipo_usuario)
                                @if($user->tipo_usuario_id == $tipo_usuario->id)
                                    <option value="{{$tipo_usuario->id}}" selected>{{$tipo_usuario->tipo_usuario}}</option>
                                @else
                                    <option value="{{$tipo_usuario->id}}">{{$tipo_usuario->tipo_usuario}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="clearfix">&nbsp;</div>
                    <div class="divider"></div>
                    <h4 class="font-gray font-size-16"><strong>Datos Ubicación</strong></h4>
                    <div class="form-group col-md-4">
                        <label>Provincia</label>
                        <select id="provincia_id" name="provincia_id" class="form-control">
                            <option value="">&lt;Seleccione&gt;</option>
                            @foreach($provincias as $provincia)
                                @if($user->provincia_id == $provincia->id)
                                    <option value="{{$provincia->id}}" selected>{{$provincia->provincia}}</option>
                                @else
                                    <option value="{{$provincia->id}}">{{$provincia->provincia}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label>Localidad</label>
                        <input type="text" class="form-control" name="localidad" value="{{$user->localidad}}">
                    </div>

                    <div class="form-group col-md-2">
                        <label>Dirección</label>
                        <input type="text" class="form-control" name="calle" placeholder="Nombre Calle"
                               value="{{$user->calle}}">
                    </div>

                    <div class="form-group col-md-2">
                        <label>&nbsp;</label>
                        <input type="text" class="form-control" name="numero_calle" placeholder="N° calle"
                               value="{{$user->numero_calle}}">
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

        });
    </script>
@endsection