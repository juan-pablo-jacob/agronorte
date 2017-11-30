<table class="table table-bordered">
    <thead>
    <tr>
        <th style="background: #80808047;">&nbsp;</th>
        <th style="background: #80808047;">Unidad</th>
        <th style="background: #80808047;">Descripción</
        >
        <th style="background: #80808047;">Precio sin IVA</th>
        <th style="background: #80808047;">IVA</th>
        <th style="background: #80808047;">Prcio IVA Incluído</th>
    </tr>
    </thead>
    <tbody>
    @if(count($cotizaciones) > 0)
        @foreach($cotizaciones as $key => $cotizacion)
            <tr>
                <td style="background: rgba(149,224,55,0.28);" rowspan="2"><strong>Venta</strong> (producto {{$key + 1}})</td>
                <td style="background: rgba(149,224,55,0.28);">{{$cotizacion->modelo}}</td>
                <td>{{$cotizacion->observacion}}</td>
                <td align="right">USD {{number_format ($cotizacion->precio_venta * 0.79, 2)}}</td>
                <td align="right">USD {{number_format ($cotizacion->precio_venta * 0.21, 2)}}</td>
                <td align="right">USD {{number_format ($cotizacion->precio_venta, 2)}}</td>
            </tr>
            <tr>
                <td colspan="4"><strong>Total VENTA nuevo:</strong></td>
                <td align="right">USD {{number_format ($cotizacion->precio_venta, 2)}}</td>
            </tr>
            <tr>
                <td>
                    <strong>
                        @if((float)$cotizacion->descuento > 0)
                            Descuento {{$cotizacion->descuento}}%
                        @else
                            No hay descuentos
                        @endif
                    </strong>
                </td>
                <td colspan="4">
                    @if((float)$cotizacion->descuento > 0)
                        {{utf8_decode($cotizacion->descripcion_descuento)}}
                    @else
                        &nbsp;
                    @endif
                </td>
                <td align="right">
                    @if((float)$cotizacion->descuento > 0)
                        USD {{$cotizacion->precio_lista_producto * $cotizacion->descuento / 100}}
                    @else
                        &nbsp;
                    @endif
                </td>
            </tr>
        @endforeach
    @endif
    </tbody>
</table>
<div class="clearfix">&nbsp;</div>