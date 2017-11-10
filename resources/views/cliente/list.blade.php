@extends('layouts.app')

@section('content')

@include('admin.partials.mensajes')


<div id="page-nav">
    <ul id="page-subnav" style="text-align: left!important;">
        <li><a href="#" title="Listado Clientes"><span>Listado Clientes</span></a></li>
        <li><a href="{{url("cliente/create")}}" title="Listado Clientes"><span>Crear Clientes</span></a></li>
        <li><a href="{{url("cliente/upload_xls")}}" title="Agregar Clientes XLS"><span>Agregar clientes XLS</span></a></li>
    </ul>
</div>

<div id="page-content">
    <!--    Inicio Filtro Búsqueda-->
    <div class="content-box">
        <h3 class="content-box-header bg-white">
            Filtro búsqueda de clientes
        </h3>

        <div class="content-box-wrapper bg-white">
            <form action="{{url("/cliente")}}" method="get" enctype="multipart/form-data">
                <div class="form-group col-md-6">
                    <label for="exampleInputEmail1">Razón Social</label>
                    <input type="text" class="form-control col-md-2" id="nombre_busqueda" value="{{$request->nombre}}" name="nombre" placeholder="Razón social">
                </div>
                <div class="form-group col-md-6">
                    <label for="exampleInputPassword1">CUIT</label>
                    <input type="text" class="form-control" id="cuit_busqueda" name="CUIT" value="{{$request->CUIT}}" placeholder="CUIT">
                </div>

                <button type="submit" class="btn btn-success"><i class="glyph-icon icon-elusive-search"></i>&nbsp;Buscar</button>
                <a href="{{url("cliente")}}" class="btn btn-success"><i class="glyph-icon icon-refresh"></i>&nbsp;Limpiar</a>
            </form>
        </div>
    </div>
    <!--    Fin Filtro Búsqueda-->


    <!--    Inicio Tabla-->

    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="dynamic-table-cliente">
        <thead>
            <tr>
                <th>Razón Social</th>
                <th>Teléfono</th>
                <th>Celular</th>
                <th>CUIT</th>
                <th>Email</th>
                <th>Localidad</th>
                <th>Domicilio</th>
                <th>&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            @if(count($clientes)>0)
            @foreach ($clientes as $cliente)
            <tr class="odd gradeX">
                <td>{{$cliente->razon_social}}</td>
                <td>{{$cliente->telefono}}</td>
                <td>{{$cliente->celular}}</td>
                <td>{{$cliente->CUIT}}</td>
                <td>{{$cliente->email}}</td>
                <td>{{$cliente->localidad}}</td>
                <td>{{$cliente->domicilio}}</td>
                <td class="center">
                    <a href="{{url("cliente/" . $cliente->id . "/edit")}}" tittle="Editar"><i class="glyph-icon icon-elusive-edit"></i></a>
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
                    <p>Seguro que desea eliminar el cliente?</p>
                    <form id="cliente-delete-form" action="" style="display:none;" method="post">
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
            var idcliente = $(this).data("id");
            $('#cliente-delete-form').attr('action', BASE_URL + '/cliente/' + idcliente);
            $('#modal-delete-confirmation').modal('toggle');
        });

        $(".btn-confirm-delete").click(function () {
            $('#cliente-delete-form').submit();
        });


        $('#dynamic-table-cliente').dataTable();
        $("#dynamic-table-cliente_length").hide();
        $("#dynamic-table-cliente_filter").hide();

        /* Add sorting icons */

        $("table.dataTable .sorting").append('<i class="glyph-icon"></i>');
        $("table.dataTable .sorting_asc").append('<i class="glyph-icon"></i>');
        $("table.dataTable .sorting_desc").append('<i class="glyph-icon"></i>');

    });

</script>

@endsection
