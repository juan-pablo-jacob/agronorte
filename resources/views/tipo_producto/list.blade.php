@extends('layouts.app')

@section('content')

@include('admin.partials.mensajes')


<div id="page-nav">
    <ul id="page-subnav" style="text-align: left!important;">
        <li><a href="#" title="Listado Tipo Producto"><span>Listado Tipo Productos</span></a></li>
        <li><a href="javascript:;" id="btnCrear" title="Crear Tipo Producto"><span><i class="glyph-icon icon-elusive-plus"></i>Crear</span></a></li>
    </ul>
</div>

<div id="page-content">

    <!--    Inicio Tabla-->
    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="dynamic-table">
        <thead>
            <tr>
                <th>Tipo Producto</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @if(count($records)>0)
            @foreach ($records as $record)
            <tr class="odd gradeX">
                <td>{{$record->tipo_producto}}</td>
                <td class="center">
                    <a href="javascript:;" data-id="{{$record->id}}" data-toggle="tooltip" data-placement="top" class="btn-editar tooltip-button" title="Editar"><i class="glyph-icon icon-elusive-edit"></i></a>
                    <a href="javascript:;" data-id="{{$record->id}}" data-toggle="tooltip" data-placement="top" class="btn-borrar tooltip-button" title="Eliminar"><i class="glyph-icon icon-elusive-trash"></i></a>
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
                    <p>Seguro que desea eliminar el tipo de producto?</p>
                    <form id="registro-delete-form" action="" style="display:none;" method="post">
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

    <!--Inclusión Modal Presupuestos NEW-->
    @include('tipo_producto.new_modal')

    @include('tipo_producto.edit_modal')


</div>

<script type="text/javascript">

    /* Datatables init */

    $(document).ready(function () {

        $("#btnCrear").click(function () {
            $("#modal-create").modal("toggle");
        });

        $(".btn-editar").click(function () {
            var id = $(this).data("id");
            var url = BASE_URL + '/tipo_producto/showJSON/' + id;
            var data = "";
            $.get(url, data, function (result) {
//                console.log(result);

                if (result.result == false) {
                    alertify.error(result.msg);
                } else {
                    
                    load_values_form("actualizacion_modal", result);
                    $('#actualizacion_modal').attr('action', BASE_URL + '/tipo_producto/' + id);
                    $('#modal-update').modal('toggle');
                }
            });
        });

        $(".btn-borrar").click(function () {
            var id = $(this).data("id");
            $('#registro-delete-form').attr('action', BASE_URL + '/tipo_producto/' + id);
            $('#modal-delete-confirmation').modal('toggle');
        });

        $(".btn-confirm-delete").click(function () {
            $('#registro-delete-form').submit();
        });


        $('#dynamic-table').dataTable();
        $("#dynamic-table_length").hide();
        $("#dynamic-table_filter").hide();

        /* Add sorting icons */

        $("table.dataTable .sorting").append('<i class="glyph-icon"></i>');
        $("table.dataTable .sorting_asc").append('<i class="glyph-icon"></i>');
        $("table.dataTable .sorting_desc").append('<i class="glyph-icon"></i>');

    });

</script>

@endsection
