<div class="content-box">
    <h3 class="content-box-header bg-default">
        @if($propuesta->tipo_propuesta_negocio_id == 1)
            Finalización producto <strong>Nuevo</strong>
        @elseif($propuesta->tipo_propuesta_negocio_id == 2)
            Finalización producto <strong>Usado</strong>
        @endif
    </h3>
    <div class="content-box-wrapper">
        <div class="clearfix">&nbsp;</div>
        <h4 class="font-gray font-size-16"><strong>Resumen cotizaciones</strong></h4>
        <div class="clearfix">&nbsp;</div>
        <div id="div_visualizacion_cotizaciones"></div>

        <div class="clearfix">&nbsp;</div>

        <input type="hidden" id="object_id" value="{{$propuesta->id}}"/>
        <input type="hidden" id="entity_id" value="propuesta"/>
        @include('admin.upload.upload_edit')
    </div>
</div>

<form role="form" id="form_get_tabla_cotizaciones_step_4" action="{{url("cotizacion/getTablaCotizaciones")}}"
      method="get" enctype="multipart/form-data"
      onsubmit="return false;">
    <input type="hidden" name="propuesta_negocio_id" value="{{$propuesta->id}}">
    <input type="hidden" name="is_toma" value="0">
</form>

@if(!is_null($mail_propuesta))
    @include('mail_propuesta_negocio.edit_in_propuesta')
@else
    @include('mail_propuesta_negocio.new_in_propuesta')
@endif


<script>
    /*
     * Function que rearma la tabla de las visualizaciones
     */
    var getTablaCotizaciones = function (form_id, div_id) {
        var $_form = $("#" + form_id);

        $.ajax({
            url: $_form.attr("action"),
            data: $_form.serialize(),
            method: "GET",
            success: function (data) {
                if (data) {
                    $("#" + div_id).html(data);
                }
            },
            error: function (data) {
            }
        });
    };

    getTablaCotizaciones("form_get_tabla_cotizaciones_step_4", "div_visualizacion_cotizaciones");

</script>