@extends('layouts.app')

@section('content')

@include('admin.partials.mensajes')


<div id="page-nav">
    <ul id="page-subnav" style="text-align: left!important;">
        <li><a href="#" title="Listado Marcas"><span>Listado Marcas</span></a></li>
        <li><a href="javascript:;" id="btnCrear" title="Crear Marca"><span><i class="glyph-icon icon-elusive-plus"></i>Crear</span></a></li>
    </ul>
</div>

<div id="page-content">

    <!--    Inicio Tabla-->
    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="dynamic-table">
        <thead>
            <tr>
                <th>Marca</th>
                <th>&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            @if(count($records)>0)
            @foreach ($records as $record)
            <tr class="odd gradeX">
                <td>{{$record->marca}}</td>
                <td class="center">
                    <a href="javascript:;" data-id="{{$record->id}}" data-toggle="tooltip" data-placement="top" title="Editar Marca" class="btn-editar tooltip-button"><i class="glyph-icon icon-elusive-edit"></i></a>
                    <a href="javascript:;" data-id="{{$record->id}}"  data-toggle="tooltip" data-placement="top" title="Borrar Marca" class="btn-borrar tooltip-button"><i class="glyph-icon icon-elusive-trash"></i></a>
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
                    <p>Seguro que desea eliminar la marca?</p>
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
    @include('marca.new_modal')
    
    @include('marca.edit_modal')


</div>

<script type="text/javascript">

    /* Datatables init */

    $(document).ready(function () {


        $("#btnCrear").click(function () {
            $("#modal-create").modal("toggle");
        });

        $(".btn-editar").click(function () {
            var id = $(this).data("id");
            var url = BASE_URL + '/marca/showJSON/' + id;
            var data = "";
            $.get(url, data, function (result) {
//                console.log(result);

                if (result.result == false) {
                    alertify.error(result.msg);
                } else {
                    
                    load_values_form("actualizacion_modal", result);
                    $('#actualizacion_modal').attr('action', BASE_URL + '/marca/' + id);
                    $('#modal-update').modal('toggle');
                }
            });
        });

        $(".btn-borrar").click(function () {
            var id = $(this).data("id");
            $('#registro-delete-form').attr('action', BASE_URL + '/marca/' + id);
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
