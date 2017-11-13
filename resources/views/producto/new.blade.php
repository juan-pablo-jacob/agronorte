@extends('layouts.app')

@section('content')


    @include('admin.partials.mensajes')

    <div id="page-nav">
        <ul id="page-subnav" style="text-align: left!important;">
            <li><a href="{{url("/producto")}}" title="Listado productos"><span>Listado Productos &raquo;</span></a></li>
            <li><a href="#" title="Crear producto"><span>Crear Producto</span></a></li>
        </ul>
    </div>
    <div id="page-content">
        <form action="{{url("/producto")}}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="content-box">
                <h3 class="content-box-header bg-default">
                    <i class="glyph-icon icon-elusive-basket"></i>
                    Editar producto
                </h3>
                <div class="content-box-wrapper">

                    <h4 class="font-gray font-size-16"><strong>Información Producto</strong></h4>

                    <div class="form-group col-md-4">
                        <label>Nuevo/Usado</label>
                        <select id="is_nuevo" name="is_nuevo" class="form-control">
                            @if(is_null(old('is_nuevo')))
                                <option value="" selected>&lt;Seleccione&gt;</option>
                                <option value="1">Nuevo</option>
                                <option value="0">Usado</option>
                            @elseif(old('is_nuevo') == 1)
                                <option value="">&lt;Seleccione&gt;</option>
                                <option value="1" selected>Nuevo</option>
                                <option value="0">Usado</option>
                            @elseif(!is_null(old('is_nuevo')))
                                <option value="">&lt;Seleccione&gt;</option>
                                <option value="1">Nuevo</option>
                                <option value="0" selected>Usado</option>
                            @endif
                        </select>
                    </div>


                    <div class="form-group col-md-4">
                        <label>Marca</label>
                        <select id="marca_id" name="marca_id" class="form-control">
                            <option value="">&lt;Seleccione&gt;</option>
                            @foreach($marcas as $marca)
                                @if($marca->id == old('marca_id'))
                                    <option value="{{$marca->id}}" selected>{{$marca->marca}}</option>
                                @else
                                    <option value="{{$marca->id}}">{{$marca->marca}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label>Tipo Producto</label>
                        <select id="tipo_producto_id" name="tipo_producto_id" class="form-control">
                            <option value="">&lt;Seleccione&gt;</option>
                            @foreach($tipo_productos as $tipo_producto)
                                @if($tipo_producto->id == old("tipo_producto_id"))
                                    <option value="{{$tipo_producto->id}}"
                                            selected>{{$tipo_producto->tipo_producto}}</option>
                                @else
                                    <option value="{{$tipo_producto->id}}">{{$tipo_producto->tipo_producto}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label>Modelo</label>
                        <input type="text" class="form-control" name="modelo" value="{{old('modelo')}}">
                    </div>

                    <div class="form-group col-md-4">
                        <label>Descripción</label>
                        <textarea name="descripcion" id="descripcion" placeholder="Ingrese descripción" rows="3"
                                  class="form-control textarea-sm">{{old("descripcion")}}</textarea>
                    </div>


                    <div class="clearfix">&nbsp;</div>
                    <div class="divider"></div>

                    <h4 class="font-gray font-size-16"><strong>Detalle Precio Producto</strong></h4>

                    <div class="form-group col-md-4">
                        <label>Precio Lista</label>
                        <input type="number" class="form-control" name="precio_lista" value="{{old('razon_social')}}">
                    </div>

                    <div class="form-group col-md-4">
                        <label>Bonificación Básica</label>
                        @if(old("bonificacion_basica") != "")
                            <input type="number" class="form-control" name="bonificacion_basica"
                                   value="{{old('bonificacion_basica')}}">
                        @else
                            <input type="number" class="form-control" name="bonificacion_basica"
                                   value="{{$parametros_sistema->bonificacion_basica}}">
                        @endif
                    </div>

                    <div class="clearfix">&nbsp;</div>

                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp;Guardar</button>
                    <a href="{{url("/producto")}}" class="btn btn-danger"><i
                                class="fa fa-arrow-left"></i>&nbsp;Volver</a>
                </div>
            </div>


        </form>


        <div class="content-box">

            <link rel="stylesheet" href="{{url('/assets/blueimp/blueimp-gallery.min.css')}}">
            <link rel="stylesheet" href="{{url('/assets/widgets/multi-upload/fileupload.css')}}">

            <link rel="stylesheet" href="{{url('/assets/widgets/progressbar/progressbar.css')}}">


            <form id="fileupload" target="_blank" action="{{url("producto/multi-upload")}}" method="POST"
                  enctype="multipart/form-data">

                {{ csrf_field() }}
                <div class="row fileupload-buttonbar">
                    <div class="col-lg-6">
                        <div class="float-left">
                  <span class="btn btn-md btn-success fileinput-button">
                        <i class="glyph-icon icon-plus"></i>
                        Agregar archivos...
                      <input type="file" id="files_input" name="files[]" multiple>
                  </span>
                            <button type="submit" id="btnSendFiles" class="btn btn-md btn-default start">
                                <i class="glyph-icon icon-upload"></i>
                                Subir
                            </button>
                            <button type="reset" class="btn btn-md btn-warning cancel">
                                <i class="glyph-icon icon-ban"></i>
                                Cancelar
                            </button>
                            <button type="button" style="display: none" class="btn btn-md btn-danger delete">
                                <i class="glyph-icon icon-trash-o"></i>
                                Delete
                            </button>
                        </div>
                        <!-- The global file processing state -->
                        <span class="fileupload-process"></span>
                    </div>
                    <!-- The global progress state -->
                    <div class="col-lg-6 fileupload-progress fade">
                        <!-- The global progress bar -->

                        <div class="progress progress-striped active" role="progressbar" aria-valuemin="0"
                             aria-valuemax="100">
                            <div class="progress-bar progress-bar-success bg-green">
                                <div class="progressbar-overlay"></div>
                            </div>
                        </div>
                        <!-- The extended global progress state -->
                        <div class="progress-extended">&nbsp;</div>
                    </div>
                </div>


                <!-- The table listing the files available for upload/download -->
                <table role="presentation" class="table table-striped">
                    <tbody class="files">

                    </tbody>
                </table>
            </form>

        </div>
    </div>



    <script type="text/javascript">
        $(function () {

            /**
             * click botón subir
             */
            $("#btnSendFiles").click(function (e) {
                e.preventDefault();
                $("#fileupload").submit();


                var data = $("#fileupload").serialize();
                var url = $("#fileupload").attr("action");
                $.post(url, data, function (result) {
                    if (result.result == false) {
                        $.each(result.errors, function (index, value) {
                            alertify.error(value[0]);
                        });
                    } else {
                        alertify.success(result.msg);
                    }
                });

            });


            $(".btnDeleteFile").click(function () {
                var id = $(this).data("id");

                if (parseInt(id) > 0) {
                    //Enviar a eliminar archivo
                }
            });


            /**
             * Función utilizada para cargar archivos existentes
             * @param params
             */
            var cargarArchivosExistentes = function (params) {
                var form = $("#" + params.form_id);

                var url = form.attr("action");
                var data = form.serialize();

                $.get(url, data, function (result) {
                    if (result.result == false) {
                        $.each(result.errors, function (index, value) {
                            alertify.error(value[0]);
                        });
                    } else {
                        if (result.files.length > 0) {
                            $.each(result.files, function (index, value) {
                                var append = "<tr class=\"template-upload fade processing in\">"
                                    + "                        <td>"
                                    + "                            <span class=\"preview\"></span>"
                                    + "                        </td>"
                                    + "                        <td>"
                                    + "                            <p class=\"name\">" + value.nombre_archivo + "." + value.ext + "</p>"
                                    + "                            <strong class=\"error text-danger\"></strong>"
                                    + "                        </td>"
                                    + "                        <td>"
                                    + "                            <p class=\"size\">Subido</p>"
                                    + "                            <div class=\"progress progress-striped active\" role=\"progressbar\" aria-valuemin=\"0\""
                                    + "                                 aria-valuemax=\"100\" aria-valuenow=\"0\">"
                                    + "                                <div class=\"progress-bar progress-bar-success bg-green\" style=\"width:100%;\"></div>"
                                    + "                          </div>"
                                    + "                     </td>"
                                    + "                     <td>"
                                    + "                         <a href='javascript:;' type='button' data-id='" + value.id + "' class=\"btn btn-md btn-danger delete btnDeleteFile\">"
                                    + "                             <span class=\"button-content\">"
                                    + "                               <i class=\"glyph-icon icon-trash-o\"></i>"
                                    + "                               Borrar"
                                    + "                             </span>"
                                    + "                       </a>"
                                    + "                   </td>"
                                    + "               </tr>";

                                $(append).appendTo(".files");
                            });
                        }

                    }
                });
            }


            var params_carga_archivos = {
                "form_id": "fileupload"
            };

        });
    </script>


@endsection