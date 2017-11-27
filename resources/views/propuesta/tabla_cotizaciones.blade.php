
<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered"
       id="dynamic-table-cotizacion-venta">
    <thead>
    <tr>
        <th>Modelo</th>
        <th>Fecha Entrega</th>
        <th>Precio Venta</th>
        <th>Precio Lista</th>
        <th>Costo Real</th>
        <th>Costo Básico</th>
        <th>Rent. vs Costo real</th>
        <th>Rent. vs Precio venta</th>
        <th>
            &nbsp;
        </th>
    </tr>
    </thead>
    <tbody>
    @if(count($cotizaciones)>0)
        @php ($total_precio_venta = 0)
        @php ($total_precio_lista_producto = 0)
        @php ($total_costo_real_producto = 0)
        @php ($total_costo_basico_producto = 0)
        @php ($total_rentabilidad_vs_costo_real = 0)
        @php ($total_rentabilidad_vs_precio_venta = 0)

        @foreach ($cotizaciones as $cotizacion)

            @php ($total_precio_venta += $cotizacion->precio_venta)
            @php ($total_precio_lista_producto += $cotizacion->precio_lista_producto)
            @php ($total_costo_real_producto += $cotizacion->costo_real_producto)
            @php ($total_costo_basico_producto += $cotizacion->costo_basico_producto)
            @php ($total_rentabilidad_vs_costo_real += $cotizacion->rentabilidad_vs_costo_real)
            @php ($total_rentabilidad_vs_precio_venta += $cotizacion->rentabilidad_vs_precio_venta)

            <tr class="odd gradeX">
                <td>{{$cotizacion->modelo}}</td>
                <td>{{date('d/m/Y',strtotime($cotizacion->fecha_entrega))}}</td>
                <td>{{number_format ($cotizacion->precio_venta, 2)}}</td>
                <td>{{number_format ($cotizacion->precio_lista_producto, 2)}}</td>
                <td>{{number_format ($cotizacion->costo_real_producto, 2)}}</td>
                <td>{{number_format ($cotizacion->costo_basico_producto, 2)}}</td>
                <td>{{number_format ($cotizacion->rentabilidad_vs_costo_real, 2)}}</td>
                <td>{{number_format ($cotizacion->rentabilidad_vs_precio_venta, 2)}}</td>
                <td class="center">
                    <a href="{{url("propuesta/cotizacion/" . $cotizacion->id)}}" tittle="Editar"><i
                                class="glyph-icon icon-elusive-edit"></i></a>
                    <a href="javascript:;" data-id="{{$cotizacion->id}}" class="btn-borrar-cotizacion" tittle="Eliminar"><i
                                class="glyph-icon icon-elusive-trash"></i></a>
                </td>
            </tr>
        @endforeach
        @if(count($cotizaciones)>1)
        <tr class="odd gradeX">
            <td>&nbsp;</td>
            <td><strong>Total:</strong></td>
            <td>{{number_format ($total_precio_venta, 2)}}</td>
            <td>{{number_format ($total_precio_lista_producto, 2)}}</td>
            <td>{{number_format ($total_costo_real_producto, 2)}}</td>
            <td>{{number_format ($total_costo_basico_producto, 2)}}</td>
            <td>{{number_format ($total_rentabilidad_vs_costo_real  / count($cotizaciones), 2)}}</td>
            <td>{{number_format ($total_rentabilidad_vs_precio_venta / count($cotizaciones), 2)}}</td>
            <td class="center">
                &nbsp;
            </td>
        </tr>
        @endif
    @endif
    </tbody>
</table>


<!--Modal eliminación registro-->
<div class="modal fade bs-example-modal-sm" id="modal-delete-cotizacion-confirmation" tabindex="-1" role="dialog"
     aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Eliminar cotización</h4>
            </div>
            <div class="modal-body">
                <p>Seguro que desea eliminar la cotización?</p>
                <form id="cotizacion-delete-form" action="" style="display:none;" method="post">
                    {{ method_field('DELETE') }}
                    {{ csrf_field() }}
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary btn-confirm-cotizacion-delete">Eliminar</button>
            </div>
        </div>
    </div>
</div>
