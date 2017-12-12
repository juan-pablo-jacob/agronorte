@extends('layouts.app')

@section('content')

    @include('admin.partials.mensajes')


    <div id="page-nav">
        <ul id="page-subnav" style="text-align: left!important;">
            <li><a href="#" title="Listado Incentivos"><span>Listado Incentivos</span></a></li>
            <li><a href="javascript:;" id="btnCrear" title="Listado Usuarios"><span>Crear Incentivo</span></a></li>
        </ul>
    </div>

    <div id="page-content">
        <!--    Inicio Filtro Búsqueda-->
        <div class="content-box">
            <h3 class="content-box-header bg-white">
                Filtro búsqueda de Incentivo
            </h3>

            <div class="content-box-wrapper bg-white">
                <form action="{{url("/incentivo")}}" method="get" enctype="multipart/form-data">
                    <div class="form-group col-md-3">
                        <label for="exampleInputEmail1">Incentivo</label>
                        <input type="text" class="form-control col-md-2" id="incentivo_busqueda"
                               value="{{$request->incentivo}}"
                               name="incentivo" placeholder="Incentivo">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="exampleInputPassword1">Estado</label>
                        <select id="no_caducados" name="no_caducados" class="form-control">
                            <option value="">&lt;Todos&gt;</option>
                            <option value="2" @if($request->no_caducados == 2) selected @endif>Caducados</option>
                            <option value="1" @if($request->no_caducados == 1) selected @endif>No Caducados</option>
                        </select>
                    </div>

                    <div class="clearfix"></div>

                    <button type="submit" class="btn btn-success"><i class="glyph-icon icon-elusive-search"></i>&nbsp;Buscar
                    </button>
                    <a href="{{url("incentivo")}}" class="btn btn-success"><i class="glyph-icon icon-refresh"></i>&nbsp;Limpiar</a>
                </form>
            </div>
        </div>
        <!--    Fin Filtro Búsqueda-->


        <!--    Inicio Tabla-->

        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered"
               id="dynamic-table-user">
            <thead>
            <tr>
                <th>Incentivo</th>
                <th>Fecha Caducidad</th>
                <th>Porcentaje</th>
                <th>Condición excluyente</th>
                <th>Boletín</th>
                <th>Cantidad Productos</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            @if(count($incentivos)>0)
                @foreach ($incentivos as $incentivo)
                    <tr class="odd gradeX">
                        <td>{{$incentivo->incentivo}}</td>
                        <td>{{date('d/m/Y',strtotime($incentivo->fecha_caducidad))}}</td>
                        <td>{{$incentivo->porcentaje}}</td>
                        <td>{{$incentivo->condicion_excluyente}}</td>
                        <td>{{$incentivo->boletin}}</td>
                        <td>{{$incentivo->cantidad_productos}}</td>
                        <td class="center">
                            <a href="{{url("incentivo/" . $incentivo->id . "/productos?asignado=1")}}" data-toggle="tooltip" data-placement="top"  title="Asignar productos a incentivo"  class="tooltip-button" ><i
                                        class="glyph-icon icon-gears tooltip-button"></i></a>
                            <a href="javascript:;" data-id="{{$incentivo->id}}"  data-toggle="tooltip" data-placement="top" class="btn-editar tooltip-button"  title="Editar"><i
                                        class="glyph-icon icon-elusive-edit"></i></a>
                            <a href="javascript:;" data-id="{{$incentivo->id}}" data-toggle="tooltip" data-placement="top"  class="btn-borrar tooltip-button" title="Eliminar"><i
                                        class="glyph-icon icon-elusive-trash"></i></a>
                        </td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
        <!--    Fin Tabla-->

        <!--Modal eliminación registro-->
        <div class="modal fade bs-example-modal-sm" id="modal-delete-confirmation" tabindex="-1" role="dialog"
             aria-labelledby="mySmallModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Eliminar registro</h4>
                    </div>
                    <div class="modal-body">
                        <p>Seguro que desea eliminar el incentivo?</p>
                        <form id="incentivo-delete-form" action="" style="display:none;" method="post">
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


        @include('incentivo.new_modal')

        @include('incentivo.edit_modal')
    </div>

    <script type="text/javascript">

        /* Datatables init */

        $(document).ready(function () {

            $(".btn-borrar").click(function () {
                var id = $(this).data("id");
                $('#incentivo-delete-form').attr('action', BASE_URL + '/incentivo/' + id);
                $('#modal-delete-confirmation').modal('toggle');
            });

            $(".btn-confirm-delete").click(function () {
                $('#incentivo-delete-form').submit();
            });


            $("#btnCrear").click(function () {
                $("#modal-create").modal("toggle");
            });

            $(".btn-editar").click(function () {
                var id = $(this).data("id");
                var url = BASE_URL + '/incentivo/showJSON/' + id;
                var data = "";
                $.get(url, data, function (result) {
//                console.log(result);

                    if (result.result == false) {
                        alertify.error(result.msg);
                    } else {

                        load_values_form("actualizacion_modal", result);
                        $("#fecha_caducidad").val(result.fecha_caducidad_format);
                        $('#actualizacion_modal').attr('action', BASE_URL + '/incentivo/' + id);
                        $('#modal-update').modal('toggle');
                    }
                });
            });


            $('#dynamic-table-user').dataTable({"sort": false});
            $("#dynamic-table-user_length").hide();
            $("#dynamic-table-user_filter").hide();

            /* Add sorting icons */

            $("table.dataTable .sorting").append('<i class="glyph-icon"></i>');
            $("table.dataTable .sorting_asc").append('<i class="glyph-icon"></i>');
            $("table.dataTable .sorting_desc").append('<i class="glyph-icon"></i>');



            $('.bootstrap-datepicker').bsdatepicker({
                format: 'dd/mm/yyyy'
            });
        });

    </script>

@endsection
