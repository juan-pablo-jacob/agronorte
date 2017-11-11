@extends('layouts.app')

@section('content')

    @include('admin.partials.mensajes')

    <div id="page-nav">
        <ul id="page-subnav" style="text-align: left!important;">
            <li><a href="{{url("/cliente")}}" title="Listado Clientes"><span>Listado Clientes &raquo;</span></a></li>
            <li><a href="#" title="Subir XLS"><span>Carga Masiva Cliente</span></a></li>
        </ul>
    </div>
    <div id="page-content">
        <form action="{{url("/cliente/upload_xls")}}" id="form_id" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}

            <div class="content-box">
                <h3 class="content-box-header bg-default">
                    <i class="glyph-icon icon-elusive-basket"></i>
                    Editar Cliente
                </h3>
                <div class="content-box-wrapper">

                    <h4 class="font-gray font-size-16"><strong>Cargar clientes de forma masiva</strong></h4>
                    <div class="form-group col-md-12">
                        <label>Inserte XLS con los contactos *</label>
                        <input type="file" name="fileinput" id="fileinput" value="{{$file}}"/>
                    </div>

                    @if($file != "")
                        <div class="divider"></div>
                        <h4 class="font-gray font-size-16"><strong>Archivo Cargado</strong></h4>

                        <div class="mail-toolbar clearfix">
                            <ul class="files-box col-md-10">
                                <li>
                                    <i class="files-icon glyph-icon icon-file-excel-o"></i>

                                    <div class="files-buttons">
                                        <a href="javascript:;" id="btnParserClient" class="btn btn-primary"><i
                                                    class="glyphicon-plus"></i>&nbsp;Agregar
                                            clientes del archivo</a>
                                        <a href="{{url("cliente/getArchivo")}}" target="_blank"
                                           class="btn hover-info tooltip-button" data-placement="right"
                                           title="Descargar">
                                            Descargar
                                            <i class="glyph-icon icon-cloud-download"></i>
                                        </a>
                                    </div>
                                </li>
                                <li class="divider"></li>
                            </ul>
                        </div>
                    @endif

                    <div class="clearfix">&nbsp;</div>

                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp;Subir Archivo</button>

                    <a href="{{url("/cliente")}}" class="btn btn-danger"><i
                                class="fa fa-arrow-left"></i>&nbsp;Volver</a>
                </div>
            </div>
        </form>
    </div>

    <script type="text/javascript">

        $("#btnParserClient").click(function (e) {
            $(function () {
                $('#loading').show();

                var data = $("#form_id").serialize();
                $.post(BASE_URL + "/cliente/agregar_clientes", data, function (result) {
                    $('#loading').hide();
                    if (result.result == false) {
                        $.each(result.errors, function (index, value) {
                            alertify.error(value[0]);
                        });
                    } else {
                        alertify.success(result.msg);
                    }
                });
            });
        });
    </script>
@endsection

