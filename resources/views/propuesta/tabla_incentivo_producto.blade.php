<div class="clearfix">&nbsp;</div>
<div class="divider"></div>

<h4 class="font-gray font-size-16"><strong>Incentivos de productos</strong></h4>

<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered"
       id="dynamic-table-incentivo-producto">
    <thead>
    <tr>
        <th>Fecha Caducidad</th>
        <th>Porcentaje</th>
        <th>Condición excluyente</th>
        <th>
            &nbsp;
        </th>
    </tr>
    </thead>
    <tbody>
    @if(count($incentivos)>0)
        @foreach ($incentivos as $incentivo)
            <tr class="odd gradeX">
                <td>{{date('d/m/Y',strtotime($incentivo->fecha_caducidad))}}</td>
                <td>{{$incentivo->porcentaje}} %</td>
                <td>{{$incentivo->condicion_excluyente}}</td>
                <td class="center">
                    <div class="checkbox-inline">
                        <label>
                            <input type="checkbox" name="incentivos_id[]" data-value="{{$incentivo->porcentaje}}"
                                   value="{{$incentivo->id}}" id="incentivo_{{$incentivo->id}}"
                                   @if(!is_null($incentivo->cotizacion_incentivo_id))
                                   checked
                                   @endif
                                   class="custom-checkbox check_grilla">
                        </label>
                    </div>
                </td>
            </tr>
        @endforeach
    @endif
    </tbody>
</table>

<div class="clearfix">&nbsp;</div>
<div class="divider"></div>
<script>
    /**
     * Tabla Incentivos
     */
    $('input[type="checkbox"].custom-checkbox').uniform();
    $('.checker span').append('<i class="glyph-icon icon-check"></i>');

    $("#select_checkbox").change(function () {
        if (this.checked) {
            $(".custom-checkbox").prop('checked', true).uniform();
        } else {
            $(".custom-checkbox").prop('checked', false).uniform();
        }
    });


</script>