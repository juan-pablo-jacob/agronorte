<h4 class="font-gray font-size-16"><strong>Subir Archivo</strong></h4>

<link rel="stylesheet" href="{{url('/assets/blueimp/blueimp-gallery.min.css')}}">
<link rel="stylesheet" href="{{url('/assets/widgets/multi-upload/fileupload.css')}}">


<div class="row fileupload-buttonbar">
    <div class="col-lg-6">
        <div class="float-left">
                  <span class="btn btn-md btn-success fileinput-button">
                        <i class="glyph-icon icon-plus"></i>
                        Agregar archivos...
                      <input type="file" id="files_input" name="files[]" multiple>
                  </span>

            <button type="button" id="btn_delete_files" class="btn btn-md btn-danger delete">
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


<script type="text/javascript">
    $(function () {

        $("#btn_delete_files").click(function () {
            var $el = $('#files_input');
            $el.wrap('<form>').closest('form').get(0).reset();
            $el.unwrap();
            $("#p_nombres_archivos").html("");
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

    });
</script>
