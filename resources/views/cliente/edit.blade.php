@extends('layouts.app')

@section('content')

@include('admin.partials.mensajes')

<div id="page-nav">
    <ul id="page-subnav" style="text-align: left!important;">
        <li><a href="{{url("/cliente")}}" title="Listado Clientes"><span>Listado Clientes &raquo;</span></a></li>
        <li><a href="#" title="Editar Cliente"><span>Editar Cliente</span></a></li>
    </ul>
</div>
<div id="page-content">
    <form action="{{url("/cliente/" . $cliente->id)}}" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}
        {{ method_field('PUT') }}
        
        <div class="content-box">
            <h3 class="content-box-header bg-default">
                <i class="glyph-icon icon-elusive-basket"></i>
                Editar Cliente
            </h3>
            <div class="content-box-wrapper">

                <h4 class="font-gray font-size-16"><strong>Datos Clientes</strong></h4>
                
                <div class="form-group col-md-4">
                    <label>Razón Social *</label>
                    <input type="text" class="form-control" name="nombre" value="{{$cliente->razon_social}}">
                </div>
                
                <div class="form-group col-md-4">
                    <label>CUIT *</label>
                    <input type="text" class="form-control" name="CUIT" value="{{$cliente->CUIT}}">
                </div>
                
                <div class="form-group col-md-4">
                    <label>Email</label>
                    <input type="email" class="form-control" name="email" value="{{$cliente->email}}">
                </div>
                
                <div class="form-group col-md-4">
                    <label>Teléfono</label>
                    <input type="text" class="form-control" name="telefono" value="{{$cliente->telefono}}">
                </div>

                <div class="form-group col-md-4">
                    <label>Fax</label>
                    <input type="text" class="form-control" name="fax" value="{{$cliente->fax}}">
                </div>

                <div class="form-group col-md-4">
                    <label>Celular</label>
                    <input type="text" class="form-control" name="celular" value="{{$cliente->celular}}">
                </div>


                <div class="form-group col-md-4">
                    <label>Categoria IVA</label>
                    <select id="categoria_iva" name="categoria_iva" class="form-control">
                        <option value="">&lt;Seleccione&gt;</option>
                        @foreach($condiciones_iva as $condicion)
                            @if($condicion->condicion_iva == $cliente->categoria_iva)
                                <option value="{{$condicion->condicion_iva}}" selected>{{$condicion->condicion_iva}}</option>
                            @else
                                <option value="{{$condicion->condicion_iva}}" >{{$condicion->condicion_iva}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div class="clearfix">&nbsp;</div>
                <div class="divider"></div>

                <h4 class="font-gray font-size-16"><strong>Datos Localización Cliente</strong></h4>
                
                <div class="form-group col-md-4">
                    <label>Provincia</label>
                    <select id="provincia_id" name="provincia_id" class="form-control">
                        <option value="">&lt;Seleccione&gt;</option>
                        @foreach($provincias as $provincia)
                            @if($provincia->id == $cliente->provincia_id)
                            <option value="{{$provincia->id}}" selected>{{$provincia->provincia}}</option>
                            @else
                            <option value="{{$provincia->id}}" >{{$provincia->provincia}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group col-md-4">
                    <label>Localidad</label>
                    <input type="text" class="form-control" name="localidad" value="{{$cliente->localidad}}">
                </div>
                
                <div class="form-group col-md-4">
                    <label>Dirección</label>
                    <input type="text" class="form-control" name="direccion" value="{{$cliente->direccion}}">
                </div>
                
                <div class="form-group col-md-4">
                    <label>Código Postal</label>
                    <input type="text" class="form-control" name="codigo_postal" value="{{$cliente->codigo_postal}}">
                </div>

                <div class="clearfix">&nbsp;</div>
                <div class="divider"></div>

                <h4 class="font-gray font-size-16"><strong>Datos Localización Comercial Cliente</strong></h4>

                <div class="form-group col-md-4">
                    <label>Provincia</label>
                    <select id="provincia_comercial_id" name="provincia_comercial_id" class="form-control">
                        <option value="">&lt;Seleccione&gt;</option>
                        @foreach($provincias as $provincia)
                            @if($provincia->id == $cliente->provincia_comercial_id)
                                <option value="{{$provincia->id}}" selected>{{$provincia->provincia}}</option>
                            @else
                                <option value="{{$provincia->id}}" >{{$provincia->provincia}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-md-4">
                    <label>Localidad Comercial</label>
                    <input type="text" class="form-control" name="localidad_comercial" value="{{$cliente->localidad_comercial}}">
                </div>

                <div class="form-group col-md-4">
                    <label>Dirección</label>
                    <input type="text" class="form-control" name="direccion_comercial" value="{{$cliente->direccion_comercial}}">
                </div>

                <div class="form-group col-md-4">
                    <label>Código Postal</label>
                    <input type="text" class="form-control" name="codigo_postal_comercial" value="{{$cliente->codigo_postal_comercial}}">
                </div>

                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp;Guardar</button>
                <a href="{{url("/contactos")}}" class="btn btn-danger"><i class="fa fa-arrow-left"></i>&nbsp;Volver</a>
            </div>
        </div>
    </form>
</div>
@endsection