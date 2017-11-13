@extends('layouts.app')

@section('content')
    {{--    <script type="text/javascript" src="{{url('/assets/js-core/raphael.js')}}"></script>--}}
    {{--<script type="text/javascript" src="{{url('/assets/widgets/multi-upload/jquery.fileupload.js')}}"></script>--}}
    {{--<script type="text/javascript" src="{{url('/assets/widgets/multi-upload/main.js')}}"></script>--}}


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

            <form id="fileupload" action="{{url("producto/multi-upload")}}" method="POST"
                  enctype="multipart/form-data">

                {{ csrf_field() }}
                <div class="row fileupload-buttonbar">
                    <div class="col-lg-6">
                        <div class="float-left">
                  <span class="btn btn-md btn-success fileinput-button">
                        <i class="glyph-icon icon-plus"></i>
                        Agregar archivos...
                      <input type="file" name="files[]" multiple>
                  </span>
                            <button type="submit" class="btn btn-md btn-default start">
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
                    <tbody class="files"></tbody>
                </table>
            </form>


            <!-- The blueimp Gallery widget -->
            <div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls" data-filter=":even">
                <div class="slides"></div>
                <h3 class="title"></h3>
                <a class="prev">‹</a>
                <a class="next">›</a>
                <a class="close">×</a>
                <a class="play-pause"></a>
                <ol class="indicator"></ol>
            </div>


            <script id="template-upload" type="text/x-tmpl">
              {% for (var i=0, file; file=o.files[i]; i++) { %}
                  <tr class="template-upload fade">
                      <td>
                          <span class="preview"></span>
                      </td>
                      <td>
                          <p class="name">{%=file.name%}</p>
                          <strong class="error text-danger"></strong>
                      </td>
                      <td>
                          <p class="size">Procesando...</p>
                          <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success bg-green" style="width:0%;"></div></div>
                      </td>
                      <td>
                          {% if (!i && !o.options.autoUpload) { %}
                              <button class="btn btn-md btn-default start" disabled>
                                <span class="button-content">
                                  <i class="glyph-icon icon-upload"></i>
                                  Subir
                                </span>
                              </button>
                          {% } %}
                          {% if (!i) { %}
                              <button class="btn btn-md btn-warning cancel">
                                  <span class="button-content">
                                    <i class="glyph-icon icon-ban-circle"></i>
                                    Cancelar
                                  </span>
                              </button>
                          {% } %}
                      </td>
                  </tr>
              {% } %}



            </script>

            <script id="template-download" type="text/x-tmpl">
              {% for (var i=0, file; file=o.files[i]; i++) { %}
                  <tr class="template-download fade">
                      <td>
                          <span class="preview">
                              {% if (file.thumbnailUrl) { %}
                                  <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery><img src="{%=file.thumbnailUrl%}"></a>
                              {% } %}
                          </span>
                      </td>
                      <td>
                          <p class="name">
                              {% if (file.url) { %}
                                  <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
                              {% } else { %}
                                  <span>{%=file.name%}</span>
                              {% } %}
                          </p>
                          {% if (file.error) { %}
                              <div><span class="label label-danger">Error</span> {%=file.error%}</div>
                          {% } %}
                      </td>
                      <td>
                          <span class="size">{%=o.formatFileSize(file.size)%}</span>
                      </td>
                      <td>
                          {% if (file.deleteUrl) { %}
                              <button class="btn btn-md btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                                  <span class="button-content">
                                    <i class="glyph-icon icon-trash"></i>
                                    Eliminar
                                  </span>
                              </button>
                              <input type="checkbox" name="delete" value="1" class="toggle width-reset float-left">
                          {% } else { %}
                              <button class="btn btn-md btn-warning cancel">
                                  <span class="button-content">
                                    <i class="glyph-icon icon-ban-circle"></i>
                                    Cancelar
                                  </span>
                              </button>
                          {% } %}
                      </td>
                  </tr>
              {% } %}



            </script>


            <div class="example-html">
			<pre>
				<code class="language-markup">

                    <form id="fileupload" action="//jquery-file-upload.appspot.com/" method="POST"
                          enctype="multipart/form-data">
                        <div class="row fileupload-buttonbar">
                            <div class="col-lg-6">
                                <div class="float-left">
                                  <span class="btn btn-md btn-success fileinput-button">
                                        <i class="glyph-icon icon-plus"></i>
                                        Agregar Archivos...
                                      <input type="file" name="files[]" multiple>
                                  </span>
                                    <button type="submit" class="btn btn-md btn-default start">
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
                        <table role="presentation" class="table table-striped"><tbody class="files"></tbody></table>
                    </form>

                    <!-- The blueimp Gallery widget -->
                    <div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls" data-filter=":even">
                        <div class="slides"></div>
                        <h3 class="title"></h3>
                        <a class="prev">‹</a>
                        <a class="next">›</a>
                        <a class="close">×</a>
                        <a class="play-pause"></a>
                        <ol class="indicator"></ol>
                    </div>
                    <!-- The template to display files available for upload -->
                    <script id="template-upload" type="text/x-tmpl">
                      {% for (var i=0, file; file=o.files[i]; i++) { %}
                          <tr class="template-upload fade">
                              <td>
                                  <span class="preview"></span>
                              </td>
                              <td>
                                  <p class="name">{%=file.name%}</p>
                                  <strong class="error text-danger"></strong>
                              </td>
                              <td>
                                  <p class="size">Procesando...</p>
                                  <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success bg-green" style="width:0%;"></div></div>
                              </td>
                              <td>
                                  {% if (!i && !o.options.autoUpload) { %}
                                      <button class="btn btn-md btn-default start" disabled>
                                        <span class="button-content">
                                          <i class="glyph-icon icon-upload"></i>
                                          Subir
                                        </span>
                                      </button>
                                  {% } %}
                                  {% if (!i) { %}
                                      <button class="btn btn-md btn-warning cancel">
                                          <span class="button-content">
                                            <i class="glyph-icon icon-ban-circle"></i>
                                            Cancelar
                                          </span>
                                      </button>
                                  {% } %}
                              </td>
                          </tr>
                      {% } %}
                      </script>
                    <!-- The template to display files available for download -->
                    <script id="template-download" type="text/x-tmpl">
                      {% for (var i=0, file; file=o.files[i]; i++) { %}
                          <tr class="template-download fade">
                              <td>
                                  <span class="preview">
                                      {% if (file.thumbnailUrl) { %}
                                          <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery><img src="{%=file.thumbnailUrl%}"></a>
                                      {% } %}
                                  </span>
                              </td>
                              <td>
                                  <p class="name">
                                      {% if (file.url) { %}
                                          <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
                                      {% } else { %}
                                          <span>{%=file.name%}</span>
                                      {% } %}
                                  </p>
                                  {% if (file.error) { %}
                                      <div><span class="label label-danger">Error</span> {%=file.error%}</div>
                                  {% } %}
                              </td>
                              <td>
                                  <span class="size">{%=o.formatFileSize(file.size)%}</span>
                              </td>
                              <td>
                                  {% if (file.deleteUrl) { %}
                                      <button class="btn btn-md btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                                          <span class="button-content">
                                            <i class="glyph-icon icon-trash"></i>
                                            Eliminar
                                          </span>
                                      </button>
                                      <input type="checkbox" name="delete" value="1" class="toggle width-reset float-left">
                                  {% } else { %}
                                      <button class="btn btn-md btn-warning cancel">
                                          <span class="button-content">
                                            <i class="glyph-icon icon-ban-circle"></i>
                                            Cancelar
                                          </span>
                                      </button>
                                  {% } %}
                              </td>
                          </tr>
                      {% } %}
                      </script>


                    <script src="{{url('/assets/blueimp/JavaScript-Load-Image-master/js/load-image.all.min.js')}}"></script>

                    <!-- The Templates plugin is included to render the upload/download listings -->
                    <script src="{{url('/assets/blueimp/tmpl.min.js')}}"></script>

                    <script src="https://blueimp.github.io/JavaScript-Load-Image/js/load-image.js"></script>
                    {{--<script src="https://blueimp.github.io/JavaScript-Load-Image/js/load-image-ios.js"></script>--}}
                    <script src="https://blueimp.github.io/JavaScript-Load-Image/js/load-image-orientation.js"></script>
                    <script src="https://blueimp.github.io/JavaScript-Load-Image/js/load-image-meta.js"></script>
                    <script src="https://blueimp.github.io/JavaScript-Load-Image/js/load-image-exif.js"></script>
                    <script src="https://blueimp.github.io/JavaScript-Load-Image/js/load-image-exif-map.js"></script>

                    <!-- The Canvas to Blob plugin is included for image resizing functionality -->
                    <script src="{{url('/assets/blueimp/canvas-to-blob.min.js')}}"></script>
                    <!-- Bootstrap JS is not required, but included for the responsive demo navigation -->
                    <!-- blueimp Gallery script -->
                    <script src="{{url('/assets/blueimp/jquery.blueimp-gallery.min.js')}}"></script>
                    <!-- The Iframe Transport is required for browsers without support for XHR file uploads -->


                    <script type="text/javascript" src="{{url('/assets/js-core/raphael.js')}}"></script>

                    <script type="text/javascript"
                            src="{{url('/assets/widgets/multi-upload/jquery.iframe-transport.js')}}"></script>

                    <script type="text/javascript"
                            src="{{url('/assets/widgets/multi-upload/jquery.fileupload.js')}}"></script>

                    <script type="text/javascript"
                            src="{{url('/assets/widgets/multi-upload/jquery.fileupload-process.js')}}"></script>

                    <script type="text/javascript"
                            src="{{url('/assets/widgets/multi-upload/jquery.fileupload-image.js')}}"></script>

                    <script type="text/javascript"
                            src="{{url('/assets/widgets/multi-upload/jquery.fileupload-audio.js')}}"></script>

                    <script type="text/javascript"
                            src="{{url('/assets/widgets/multi-upload/jquery.fileupload-video.js')}}"></script>

                    <script type="text/javascript"
                            src="{{url('/assets/widgets/multi-upload/jquery.fileupload-validate.js')}}"></script>

                    <script type="text/javascript"
                            src="{{url('/assets/widgets/multi-upload/jquery.fileupload-ui.js')}}"></script>

                    <script type="text/javascript" src="{{url('/assets/widgets/multi-upload/main.js')}}"></script>

                    <script type="text/javascript"
                            src="{{url('/assets/widgets/multi-upload/cors/jquery.xdr-transport.js')}}"></script>


                </code>
			</pre>
            </div>


        </div>
    </div>
    <script type="text/javascript">
        $(function () {

        });
    </script>


@endsection