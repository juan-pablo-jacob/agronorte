
<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered"
       id="dynamic-table-cotizacion-toma">
    <thead>
    <tr>
        <th>Tipo Producto</th>
        <th>Marca</th>
        <th>Modelo</th>
        <th>Precio Uª recibida</th>
        <th>Costo Usado</th>
        <th>
            &nbsp;
        </th>
    </tr>
    </thead>
    <tbody>
    @if(count($cotizaciones)>0)
        @php ($total_precio_toma = 0)
        @php ($total_costo_usado = 0)

        @foreach ($cotizaciones as $cotizacion)

            @php ($total_precio_toma += $cotizacion->precio_toma)
            @php ($total_costo_usado += $cotizacion->costo_real_producto)

            <tr class="odd gradeX">
                <td>{{$cotizacion->tipo_producto}}</td>
                <td>{{$cotizacion->marca}}</td>
                <td>{{$cotizacion->modelo}}</td>
                <td>{{number_format ($cotizacion->precio_toma, 2)}}</td>
                <td>{{number_format ($cotizacion->costo_real_producto, 2)}}</td>
                <td class="center">
                    <a href="{{url("propuesta/cotizacion/" . $cotizacion->id)}}" tittle="Editar"><i
                                class="glyph-icon icon-elusive-edit"></i></a>
                    <a href="javascript:;" data-id="{{$cotizacion->id}}" class="btn-borrar-cotizacion-toma" tittle="Eliminar"><i
                                class="glyph-icon icon-elusive-trash"></i></a>
                </td>
            </tr>
        @endforeach
        @if(count($cotizaciones)>1)
            <tr class="odd gradeX">
                <td>&nbsp;</td>
                <td><strong>Total:</strong></td>
                <td>&nbsp;</td>
                <td>{{number_format ($total_precio_toma, 2)}}</td>
                <td>{{number_format ($total_costo_usado, 2)}}</td>
                <td class="center">
                    &nbsp;
                </td>
            </tr>
        @endif
    @endif
    </tbody>
</table>


<!--Modal eliminación registro-->
<div class="modal fade bs-example-modal-sm" id="modal-delete-cotizacion-toma-confirmation" tabindex="-1" role="dialog"
     aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Eliminar cotización</h4>
            </div>
            <div class="modal-body">
                <p>Seguro que desea eliminar la cotización?</p>
                <form id="cotizacion-toma-delete-form" action="" style="display:none;" method="post">
                    {{ method_field('DELETE') }}
                    {{ csrf_field() }}
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary btn-confirm-cotizacion-toma-delete">Eliminar</button>
            </div>
        </div>
    </div>
</div>
