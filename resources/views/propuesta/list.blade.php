@extends('layouts.app')

@section('content')

    @include('admin.partials.mensajes')


    <div id="page-nav">
        <ul id="page-subnav" style="text-align: left!important;">
            <li><a href="#" title="Listado propuestas"><span>Listado propuestas</span></a></li>
            <li><a href="{{url("propuesta/create")}}" title="Listado propuestas"><span>Crear propuesta</span></a></li>
        </ul>
    </div>

    <div id="page-content">
        <!--    Inicio Filtro Búsqueda-->
        <div class="content-box">
            <h3 class="content-box-header bg-white">
                Filtro búsqueda de Propuestas
            </h3>

            <div class="content-box-wrapper bg-white">
                <form action="{{url("/propuesta")}}" method="get" enctype="multipart/form-data">


                    <div class="form-group col-md-3">
                        {{--<label for="exampleInputEmail1">Modelo</label>--}}
                        <input type="text" class="form-control col-md-2" id="modelo" value="{{$request->modelo}}"
                               name="modelo" placeholder="Modelo propuesta">
                    </div>
                    <div class="form-group col-md-3">
                        {{--<label>Vendedor</label>--}}
                        <select id="users_id" name="users_id" class="form-control">
                            <option value="">&lt;Seleccione Vendedor&gt;</option>
                            @foreach($vendedores as $vendedor)
                                @if($vendedor->id == $request->users_id)
                                    <option value="{{$vendedor->id}}"
                                            selected>{{$vendedor->nombre}} {{$vendedor->apellido}}</option>
                                @else
                                    <option value="{{$vendedor->id}}">{{$vendedor->nombre}} {{$vendedor->apellido}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-3">
                        {{--<label>Tipo propuesta</label>--}}
                        <select id="tipo_propuesta_negocio_id" name="tipo_propuesta_negocio_id" class="form-control">
                            <option value="">&lt;Seleccione tipo propuesta&gt;</option>
                            @foreach($tipo_propuestas as $tipo_propuesta)
                                @if($tipo_propuesta->id == $request->tipo_propuesta_negocio_id)
                                    <option value="{{$tipo_propuesta->id}}"
                                            selected>{{$tipo_propuesta->tipo_propuesta_negocio}}</option>
                                @else
                                    <option value="{{$tipo_propuesta->id}}">{{$tipo_propuesta->tipo_propuesta_negocio}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-3">
                        {{--<label>Estados *</label>--}}
                        <select id="estados" name="estados" class="form-control">
                            <option value="" @if($request->estados == "") selected @endif>&lt;Todos los
                                estados&gt;</option>
                            <option value="1" @if($request->estados == 1) selected @endif>Abierta</option>
                            <option value="2" @if($request->estados == 2) selected @endif>Negociación</option>
                            <option value="3" @if($request->estados == 3) selected @endif>Aceptada</option>
                            <option value="4" @if($request->estados == 4) selected @endif>Rechazada</option>
                        </select>
                    </div>

                    <div class="form-group col-md-3">
                        {{--<label for="exampleInputEmail1">Fecha Desde</label>--}}
                        <div class="input-prepend input-group ">
                            <span class="add-on input-group-addon">
                                <i class="glyph-icon icon-calendar"></i>
                            </span>
                            <input type="text" class="bootstrap-datepicker form-control" name="fecha_desde"
                                   placeholder="Fecha Desde (dd/mm/aaaa)"
                                   data-date-format="mm/dd/yy"
                                   @if($request->fecha_desde != "")
                                   value="{{$request->fecha_desde}}"
                                   @else
                                   value=""
                                    @endif
                            >
                        </div>
                    </div>

                    <div class="form-group col-md-3">
                        {{--<label for="exampleInputEmail1">Fecha Hasta</label>--}}
                        <div class="input-prepend input-group ">
                            <span class="add-on input-group-addon">
                                <i class="glyph-icon icon-calendar"></i>
                            </span>
                            <input type="text" class="bootstrap-datepicker form-control" name="fecha_hasta"
                                   placeholder="Fecha Hasta (dd/mm/aaaa)"
                                   data-date-format="mm/dd/yy"
                                   @if($request->fecha_hasta != "")
                                   value="{{$request->fecha_hasta}}"
                                   @else
                                   value=""
                                    @endif
                            >
                        </div>
                    </div>

                    <div class="clearfix">&nbsp;</div>

                    <button type="submit" class="btn btn-success"><i class="glyph-icon icon-elusive-search"></i>&nbsp;Buscar
                    </button>
                    <a href="{{url("propuesta")}}" class="btn btn-success"><i class="glyph-icon icon-refresh"></i>&nbsp;Limpiar</a>
                </form>
            </div>
        </div>
        <!--    Fin Filtro Búsqueda-->


        <!--    Inicio Tabla-->

        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered"
               id="dynamic-table-propuesta">
            <thead>
            <tr>
                <th>Fecha</th>
                <th>Tipo Propuesta</th>
                <th>Vendedor</th>
                <th>Cliente</th>
                <th>Estado</th>
                <th>&nbsp;</th>
            </tr>
            </thead>
            <tbody>
            @if(count($propuestas)>0)
                @foreach ($propuestas as $propuesta)
                    <tr class="odd gradeX">
                        <td>{{date('d/m/Y',strtotime($propuesta->fecha))}}</td>
                        <td>{{$propuesta->tipo_propuesta_negocio}}</td>
                        <td>{{$propuesta->nombre}} {{$propuesta->apellido}}</td>
                        <td>{{$propuesta->razon_social}}</td>
                        <td>
                            @if ($propuesta->estados == 1)
                                Abierto
                            @elseif ($propuesta->estados == 2)
                                Negociación
                            @elseif ($propuesta->estados == 3)
                                Aceptada
                            @elseif ($propuesta->estados == 4)
                                Rechazada
                            @endif
                        </td>
                        <td class="center">
                            <a href="{{url("propuesta/" . $propuesta->id . "/edit")}}" tittle="Editar"><i
                                        class="glyph-icon icon-elusive-edit"></i></a>
                            <a href="javascript:;" data-id="{{$propuesta->id}}" class="btn-borrar" tittle="Eliminar"><i
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
                        <p>Seguro que desea eliminar la propuesta?</p>
                        <form id="propuesta-delete-form" action="" style="display:none;" method="post">
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

            $('.bootstrap-datepicker').bsdatepicker({
                format: 'dd/mm/yyyy'
            });

            $(".btn-borrar").click(function () {
                var idpropuesta = $(this).data("id");
                $('#propuesta-delete-form').attr('action', BASE_URL + '/propuesta/' + idpropuesta);
                $('#modal-delete-confirmation').modal('toggle');
            });

            $(".btn-confirm-delete").click(function () {
                $('#propuesta-delete-form').submit();
            });

            $('#dynamic-table-propuesta').dataTable({sort: false});
            $("#dynamic-table-propuesta_length").hide();
            $("#dynamic-table-propuesta_filter").hide();

            /* Add sorting icons */

            $("table.dataTable .sorting").append('<i class="glyph-icon"></i>');
            $("table.dataTable .sorting_asc").append('<i class="glyph-icon"></i>');
            $("table.dataTable .sorting_desc").append('<i class="glyph-icon"></i>');

        });

    </script>

@endsection
