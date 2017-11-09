@extends('layouts.app')

@section('content')

@include('admin.partials.mensajes')


<div id="page-nav">
    <ul id="page-subnav" style="text-align: left!important;">
        <li><a href="#" title="Listado Contactos"><span>Listado Contacto</span></a></li>
        <li><a href="{{url("contactos/create")}}" title="Listado Contactos"><span>Crear Contacto</span></a></li>
    </ul>
</div>

<div id="page-content">
    <!--    Inicio Filtro Búsqueda-->
    <div class="content-box">
        <h3 class="content-box-header bg-white">
            Filtro búsqueda de contactos
        </h3>

        <div class="content-box-wrapper bg-white">
            <form action="{{url("/contactos")}}" method="get" enctype="multipart/form-data">
                <div class="form-group col-md-6">
                    <label for="exampleInputEmail1">Nombre Contacto</label>
                    <input type="text" class="form-control col-md-2" id="nombre_busqueda" value="{{$request->nombre}}" name="nombre" placeholder="Nombre o apellido">
                </div>
                <div class="form-group col-md-6">
                    <label for="exampleInputPassword1">DNI</label>
                    <input type="text" class="form-control" id="dni_busqueda" name="dni" value="{{$request->dni}}" placeholder="DNI">
                </div>

                <button type="submit" class="btn btn-success"><i class="glyph-icon icon-elusive-search"></i>&nbsp;Buscar</button>
                <a href="{{url("contactos")}}" class="btn btn-success"><i class="glyph-icon icon-refresh"></i>&nbsp;Limpiar</a>
            </form>
        </div>
    </div>
    <!--    Fin Filtro Búsqueda-->


    <!--    Inicio Tabla-->

    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="dynamic-table-contacto">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>DNI</th>
                <th>Email</th>
                <th>Dirección</th>
                <th>&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            @if(count($clientes)>0)
            @foreach ($clientes as $cliente)
            <tr class="odd gradeX">
                <td>{{$cliente->nombre}}</td>
                <td>{{$cliente->apellido}}</td>
                <td>{{$cliente->dni}}</td>
                <td>{{$cliente->email}}</td>
                <td>{{$cliente->direccion}}</td>
                <td class="center">
                    <a href="{{url("contactos/" . $cliente->id . "/edit")}}" tittle="Editar"><i class="glyph-icon icon-elusive-edit"></i></a>
                    <a href="javascript:;" data-id="{{$cliente->id}}" class="btn-borrar" tittle="Eliminar"><i class="glyph-icon icon-elusive-trash"></i></a>
                </td>
            </tr>
            @endforeach
            @endif
        </tbody>
    </table>
    <!--    Fin Tabla-->

    <!--Modal eliminación registro-->
    <div class="modal fade bs-example-modal-sm" id="modal-delete-confirmation" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Eliminar registro</h4>
                </div>
                <div class="modal-body">
                    <p>Seguro que desea eliminar el contacto?</p>
                    <form id="contacto-delete-form" action="" style="display:none;" method="post">
                        {{ method_field('DELETE') }}
                        {{ csrf_field() }}
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary btn-confirm-delete">Eliminar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    
    /* Datatables init */

    $(document).ready(function () {

        $(".btn-borrar").click(function () {
            var idcontacto = $(this).data("id");
            $('#contacto-delete-form').attr('action', BASE_URL + '/contactos/' + idcontacto);
            $('#modal-delete-confirmation').modal('toggle');
        });

        $(".btn-confirm-delete").click(function () {
            $('#contacto-delete-form').submit();
        });


        $('#dynamic-table-contacto').dataTable();
        $("#dynamic-table-contacto_length").hide();
        $("#dynamic-table-contacto_filter").hide();

        /* Add sorting icons */

        $("table.dataTable .sorting").append('<i class="glyph-icon"></i>');
        $("table.dataTable .sorting_asc").append('<i class="glyph-icon"></i>');
        $("table.dataTable .sorting_desc").append('<i class="glyph-icon"></i>');

    });

</script>

@endsection
