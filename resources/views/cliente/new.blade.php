@extends('layouts.app')

@section('content')

@include('admin.partials.mensajes')

<div id="page-nav">
    <ul id="page-subnav" style="text-align: left!important;">
        <li><a href="{{url("/contactos")}}" title="Listado Contacto"><span>Listado Contacto &raquo;</span></a></li>
        <li><a href="#" title="Crear Contacto"><span>Crear Contacto</span></a></li>
    </ul>
</div>
<div id="page-content">
    <form action="{{url("/contactos")}}" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="content-box">
            <h3 class="content-box-header bg-default">
                <i class="glyph-icon icon-elusive-basket"></i>
                Crear Contacto
            </h3>
            <div class="content-box-wrapper">

                <div class="form-group col-md-4">
                    <label>Nombre *</label>
                    <input type="text" class="form-control" name="nombre"> 
                </div>
                
                <div class="form-group col-md-4">
                    <label>Apellido *</label>
                    <input type="text" class="form-control" name="apellido"> 
                </div>
                
                <div class="form-group col-md-4">
                    <label>DNI *</label>
                    <input type="text" class="form-control" name="dni" > 
                </div>
                
                <div class="form-group col-md-4">
                    <label>Teléfono *</label>
                    <input type="text" class="form-control" name="telefono" > 
                </div>
                
                <div class="form-group col-md-4">
                    <label>Email</label>
                    <input type="email" class="form-control" name="email">
                </div>
                
                <div class="form-group col-md-4">
                    <label>Provincia</label>
                    <select id="provincia_id" name="provincia_id" class="form-control">
                        <option value="">&lt;Seleccione&gt;</option>
                        @foreach($provincias as $provincia)
                            <option value="{{$provincia->id}}" >{{$provincia->provincia}}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group col-md-4">
                    <label>Localidad</label>
                    <input type="text" class="form-control" name="localidad"> 
                </div>
                
                <div class="form-group col-md-4">
                    <label>Dirección</label>
                    <input type="text" class="form-control" name="direccion"> 
                </div>
                
                <div class="form-group col-md-4">
                    <label>Código Postal</label>
                    <input type="text" class="form-control" name="codigo_postal"> 
                </div>
                
                <div class="clearfix">&nbsp;</div>

                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp;Guardar</button>
                <a href="{{url("/contactos")}}" class="btn btn-danger"><i class="fa fa-arrow-left"></i>&nbsp;Volver</a>
            </div>
        </div>
    </form>
</div>
@endsection