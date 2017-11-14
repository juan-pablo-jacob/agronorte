<div class="content-box">

    <link rel="stylesheet" href="{{url('/assets/blueimp/blueimp-gallery.min.css')}}">
    <link rel="stylesheet" href="{{url('/assets/widgets/multi-upload/fileupload.css')}}">

    <link rel="stylesheet" href="{{url('/assets/widgets/progressbar/progressbar.css')}}">


    <form id="fileupload" target="_blank" action="{{url("producto/multi-upload")}}" method="POST"
          enctype="multipart/form-data">

        <input type="hidden" name="object_id" id="object_id_form" value="" />
        <input type="hidden" name="entity_id" id="entity_id_form" value="" />

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
        <p id="p_nombres_archivos">
        </p>


        <!-- The table listing the files available for upload/download -->
        <table role="presentation" class="table table-striped">
            <tbody class="files">

            </tbody>
        </table>
    </form>

</div>

<script type="text/javascript">
    $(function () {

        /**
         * click botón subir
         */
        $("#btnSendFiles").click(function (e) {

            //Extra_params = object_id + ruta alternativa (folders a partir del public)
            var object_id = $("#object_id").val();

            var data = $("#fileupload").serialize() + "&id=" + object_id;
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


        $("#files_input").change(function () {
            input = document.getElementById("files_input");
            if (input.files.length > 0) {
                $.each(input.files, function (index, value) {
                    var append = value.name + "<br>";
                    $("#p_nombres_archivos").append(append);
                });
            } else {
                $("#p_nombres_archivos").html("");
            }
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

            $("#" + params.form_id).attr('action', BASE_URL + '/producto/multi-upload');

            $("#object_id_form").val(params.object_id);
            $("#entity_id_form").val(params.entity_id);
            var form = $("#" + params.form_id);

            var url = form.attr("action");
            var data = form.serialize();

            $.get(url, data, function (result) {
                if (result.result != false) {

                    console.log(result.files);
                    if (result.files) {
                        $.each(result.files, function (index, value) {
                            var append = "<tr class=\"template-upload fade processing in\">"
                                + "                        <td>"
                                + "                            <span class=\"preview\"></span>"
                                + "                        </td>"
                                + "                        <td>"
                                + "                            <p class=\"name\">" + value.nombre_archivo + "</p>"
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
            "form_id": "fileupload",
            "object_id": $("#object_id").val(),
            "entity_id": $("#entity_id").val()
        };


        cargarArchivosExistentes(params_carga_archivos);

    });
</script>
