@extends('layouts.app')

@section('content')

    @include('admin.partials.mensajes')


    <div id="page-nav">
        <ul id="page-subnav" style="text-align: left!important;">
            <li><a href="#" title="Listado Productos"><span>Listado Productos</span></a></li>
            <li><a href="{{url("producto/create")}}" title="Listado Productos"><span>Crear Producto</span></a></li>
        </ul>
    </div>

    <div id="page-content">
        <!--    Inicio Filtro Búsqueda-->
        <div class="content-box">
            <h3 class="content-box-header bg-white">
                Filtro búsqueda de productos
            </h3>

            <div class="content-box-wrapper bg-white">
                <form action="{{url("/producto")}}" method="get" enctype="multipart/form-data">
                    <div class="form-group col-md-3">
                        <label for="exampleInputEmail1">Modelo</label>
                        <input type="text" class="form-control col-md-2" id="modelo" value="{{$request->modelo}}" name="modelo" placeholder="Modelo Producto">
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
                                    <option value="{{$tipo_producto->id}}" selected>{{$tipo_producto->tipo_producto}}</option>
                                @else
                                    <option value="{{$tipo_producto->id}}">{{$tipo_producto->tipo_producto}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="clearfix">&nbsp;</div>

                    <button type="submit" class="btn btn-success"><i class="glyph-icon icon-elusive-search"></i>&nbsp;Buscar</button>
                    <a href="{{url("producto")}}" class="btn btn-success"><i class="glyph-icon icon-refresh"></i>&nbsp;Limpiar</a>
                </form>
            </div>
        </div>
        <!--    Fin Filtro Búsqueda-->


        <!--    Inicio Tabla-->

        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="dynamic-table-producto">
            <thead>
            <tr>
                <th>Tipo Producto</th>
                <th>Marca</th>
                <th>Modelo</th>
                <th>Descripcion</th>
                <th>Nuevo/Usado</th>
                <th>Precio Lista ($)</th>
                <th>&nbsp;</th>
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
                            <a href="{{url("producto/" . $producto->id . "/edit")}}" tittle="Editar"><i class="glyph-icon icon-elusive-edit"></i></a>
                            <a href="javascript:;" data-id="{{$producto->id}}" class="btn-borrar" tittle="Eliminar"><i class="glyph-icon icon-elusive-trash"></i></a>
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
                        <p>Seguro que desea eliminar el producto?</p>
                        <form id="producto-delete-form" action="" style="display:none;" method="post">
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
                var idproducto = $(this).data("id");
                $('#producto-delete-form').attr('action', BASE_URL + '/producto/' + idproducto);
                $('#modal-delete-confirmation').modal('toggle');
            });

            $(".btn-confirm-delete").click(function () {
                $('#producto-delete-form').submit();
            });


            $('#dynamic-table-producto').dataTable();
            $("#dynamic-table-producto_length").hide();
            $("#dynamic-table-producto_filter").hide();

            /* Add sorting icons */

            $("table.dataTable .sorting").append('<i class="glyph-icon"></i>');
            $("table.dataTable .sorting_asc").append('<i class="glyph-icon"></i>');
            $("table.dataTable .sorting_desc").append('<i class="glyph-icon"></i>');

        });

    </script>

@endsection
