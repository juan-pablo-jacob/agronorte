@extends('layouts.app')

@section('content')

    @include('admin.partials.mensajes')


    <div id="page-nav">
        <ul id="page-subnav" style="text-align: left!important;">
            <li><a href="{{url("incentivo")}}" title="Listado Productos"><span>Listado Incentivos</span></a></li>
            <li><a href="#" title="Listado Productos de incentivos"><span>Asignar productos a incentivos</span></a></li>
        </ul>
    </div>

    <div id="page-content">
        <div class="content-box">
            <h3 class="content-box-header bg-gray">
                Datos Incentivo
            </h3>


            <div class="content-box-wrapper bg-white">
                <div class="form-group col-md-4">
                    <label for="exampleInputEmail1">Porcentaje</label>
                    <input type="text" class="form-control col-md-2" value="{{$incentivo->porcentaje}}" disabled>
                </div>
                <div class="form-group col-md-4">
                    <label for="exampleInputEmail1">Fecha Caducidad</label>
                    <input type="text" class="form-control col-md-2"
                           value="{{date('d/m/Y',strtotime($incentivo->fecha_caducidad))}}" disabled>
                </div>
                <div class="form-group col-md-4">
                    <label for="exampleInputEmail1">Condicion Excluyente</label>
                    <input type="text" class="form-control col-md-2" value="{{$incentivo->condicion_excluyente}}"
                           disabled>
                </div>
            </div>
            <div class="clearfix">&nbsp;</div>
        </div>

        <!--    Inicio Filtro Búsqueda-->
        <div class="content-box">
            <h3 class="content-box-header bg-white">
                Filtro búsqueda de productos
            </h3>

            <div class="content-box-wrapper bg-white">
                <form id="form_busqueda" action="{{url("incentivo/" . $incentivo->id . "/productos")}}" method="get"
                      enctype="multipart/form-data">
                    <div class="form-group col-md-3">
                        <label for="exampleInputEmail1">Modelo</label>
                        <input type="text" class="form-control col-md-2" id="modelo" value="{{$request->modelo}}"
                               name="modelo" placeholder="Modelo Producto">
                    </div>
                    <div class="form-group col-md-3">
                        <label>Marca</label>
                        <select id="marca_id" name="marca_id" class="form-control">
                            <option value="">&lt;Seleccione&gt;</option>
                            @foreach($marcas as $marca)
                                @if($marca->id == $request->marca_id)
                                    <option value="{{$marca->id}}" selected>{{$marca->marca}}</option>
                                @else
                                    <option value="{{$marca->id}}">{{$marca->marca}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-3">
                        <label>Tipo Producto</label>
                        <select id="tipo_producto_id" name="tipo_producto_id" class="form-control">
                            <option value="">&lt;Seleccione&gt;</option>
                            @foreach($tipo_productos as $tipo_producto)
                                @if($tipo_producto->id == $request->tipo_producto_id)
                                    <option value="{{$tipo_producto->id}}"
                                            selected>{{$tipo_producto->tipo_producto}}</option>
                                @else
                                    <option value="{{$tipo_producto->id}}">{{$tipo_producto->tipo_producto}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-3">
                        <label>Asignados</label>
                        <select id="asignado" name="asignado" class="form-control">

                            <option value="1" @if($request->asignado == 1) selected @endif>Productos Asignados</option>
                            <option value="2" @if($request->asignado != 1)  selected @endif>Productos No Asignados
                            </option>
                        </select>
                    </div>

                    <div class="clearfix">&nbsp;</div>

                    <button type="submit" class="btn btn-success"><i class="glyph-icon icon-elusive-search"></i>&nbsp;Buscar
                    </button>
                    <a href="{{url("incentivo/" . $incentivo->id . "/productos")}}" class="btn btn-success"><i
                                class="glyph-icon icon-refresh"></i>&nbsp;Limpiar</a>

                    @if($request->asignado != 1)
                        <button class="btn btn-gray" id="btnAgregar"><i class="glyph-icon icon-elusive-plus"></i>&nbsp;Asignar
                            productos
                        </button>
                    @else
                        <button class="btn btn-danger" id="btnQuitar"><i class="glyph-icon icon-elusive-minus"></i>&nbsp;Quitar
                            productos
                        </button>
                    @endif
                </form>
            </div>
        </div>
        <!--    Fin Filtro Búsqueda-->


        <!--    Inicio Tabla-->

        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered"
               id="dynamic-table-producto">
            <thead>
            <tr>
                <th>Tipo Producto</th>
                <th>Marca</th>
                <th>Modelo</th>
                <th>Descripcion</th>
                <th>Nuevo/Usado</th>
                <th>Precio Lista ($)</th>
                <th>&nbsp;
                    <div class="checkbox-inline">
                        <label>
                            <input type="checkbox" id="select_checkbox" class="custom-checkbox">
                        </label>
                    </div>
                </th>
            </tr>
            </thead>
            <tbody>
            @if(count($productos)>0)
                @foreach ($productos as $producto)
                    <tr class="odd gradeX">
                        <td>{{$producto->tipo_producto}}</td>
                        <td>{{$producto->marca}}</td>
                        <td>{{$producto->modelo}}</td>
                        <td>{{$producto->descripcion}}</td>
                        <td>
                            @if($producto->is_nuevo == 1)
                                Nuevo
                            @else
                                Usado
                            @endif
                        </td>
                        <td class="center">{{$producto->precio_lista}}</td>
                        <td class="center">
                            <div class="checkbox-inline">
                                <label>
                                    <input type="checkbox" value="{{$producto->id}}" id="{{$producto->id}}"
                                           class="custom-checkbox check_grilla">
                                </label>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
        <!--    Fin Tabla-->

    </div>

    <form id="form_procesar" action="" method="post" enctype="multipart/form-data" style="display: none">
        {{ csrf_field() }}
        <input type="hidden" name="ids" id="ids_input"/>
        <input type="hidden" id="incentivo_id" name="incentivo_id" value="{{$incentivo->id}}"/>
    </form>

    <script type="text/javascript">

        /* Datatables init */

        $(document).ready(function () {

            var getChecked = function () {
                var array_id = [];
                $.each($(".check_grilla:checked"), function (index, value) {
                    array_id[index] = $(this).val();
                });
                return array_id;
            };

            var sendForm = function (action) {
                $('#form_procesar').attr('action', action);

                var array_id = getChecked();

                if (array_id.length > 0) {
                    $("#ids_input").val(array_id.join(','));
                } else {
                    alertify.error("Seleccione al menos un producto");
                    return false;
                }

                $('#form_procesar').submit();
            }


            $("#btnAgregar").click(function (e) {
                e.preventDefault();

                sendForm(BASE_URL + '/incentivo/agregar_productos');
            });

            $("#btnQuitar").click(function (e) {
                e.preventDefault();

                sendForm(BASE_URL + '/incentivo/quitar_productos');
            });


            $('input[type="checkbox"].custom-checkbox').uniform();
            $('.checker span').append('<i class="glyph-icon icon-check"></i>');


            $(".btn-borrar").click(function () {
                var idproducto = $(this).data("id");
                $('#producto-delete-form').attr('action', BASE_URL + '/producto/' + idproducto);
                $('#modal-delete-confirmation').modal('toggle');
            });

            $(".btn-confirm-delete").click(function () {
                $('#producto-delete-form').submit();
            });


            $("#select_checkbox").change(function () {
                if (this.checked) {
                    $(".custom-checkbox").prop('checked', true).uniform();
                } else {
                    $(".custom-checkbox").prop('checked', false).uniform();
                }
            });

            $('#dynamic-table-producto').dataTable({"sort": false});
            $("#dynamic-table-producto_length").hide();
            $("#dynamic-table-producto_filter").hide();

            /* Add sorting icons */

            $("table.dataTable .sorting").append('<i class="glyph-icon"></i>');
            $("table.dataTable .sorting_asc").append('<i class="glyph-icon"></i>');
            $("table.dataTable .sorting_desc").append('<i class="glyph-icon"></i>');

        });

    </script>

@endsection
