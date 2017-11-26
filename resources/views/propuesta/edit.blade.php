@extends('layouts.app')

@section('content')

    @include('admin.partials.mensajes')

    <div id="page-nav">
        <ul id="page-subnav" style="text-align: left!important;">
            <li><a href="{{url("/propuesta")}}"
                   title="Listado Services / Reparación"><span>Listado Propuestas &raquo;</span></a></li>
            <li><a href="#" title="Editar Propuestas"><span>Formulario Propuestas</span></a></li>
        </ul>
    </div>

    <!-- #page-content INICIO -->
    <div id="page-content">
        <div class="content-box">
            <h3 class="content-box-header bg-default">
                <i class="glyph-icon icon-elusive-wrench"></i>
                Editar Propuesta <strong>({{$tipo_propuesta->tipo_propuesta_negocio}})</strong>
            </h3>
            <div id="form-wizard-3"
                 style="position: relative;margin-bottom: 40px;padding: 25px 25px 0;border-width: 1px;border-radius: 6px;"
                 class="form-wizard">
                <ul>
                    <li>
                        <a href="#step-1" data-toggle="tab" id="go_step_1">
                            <label class="wizard-step">1</label>
                            <span class="wizard-description">
                            Propuesta Inicio
                            <small>Carga datos generales</small>
                        </span>
                        </a>
                    </li>
                    <li>
                        <a href="#step-2" data-toggle="tab" id="go_step_2">
                            <label class="wizard-step">2</label>
                            <span class="wizard-description">
                            Producto
                            <small>Datos de producto</small>
                        </span>
                        </a>
                    </li>
                    <li>
                        <a href="#step-3" data-toggle="tab" id="go_step_3">
                            <label class="wizard-step">3</label>
                            <span class="wizard-description">
                            Precio
                            <small>Visualización Costos / Ganancias</small>
                        </span>
                        </a>
                    </li>
                    <li>
                        <a href="#step-4" data-toggle="tab" id="go_step_4">
                            <label class="wizard-step">4</label>
                            <span class="wizard-description">
                            Finalización
                            <small>Finalizar propuesta</small>
                        </span>
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="step-1">
                        @include('propuesta.step_edit_1')
                    </div>
                    <div class="tab-pane" id="step-2">
                        @if($propuesta->tipo_propuesta_negocio_id == 1)
                            @include('propuesta.step_new_nuevo_2')
                        @endif
                    </div>
                    <div class="tab-pane" id="step-3">
                        {{--@include('service_reparacion.step_edit_3')--}}
                    </div>
                    <div class="tab-pane" id="step-4">
                        {{--@include('service_reparacion.step_edit_4')--}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (isset($step) && $step != "")
        <input type="hidden" id="request_step" value="{{$step}}" />
    @endif

    <script type="text/javascript">

        $("#btn_next_step_2").click(function () {
            $("#go_step_2").click();
        });


        if ($("#request_step").val() != "") {
            $("#go_step_" + $("#request_step").val()).click();
        }

        /* Datepicker bootstrap */

        $(function () {
            $('.bootstrap-datepicker').bsdatepicker({
                format: 'dd/mm/yyyy'
            });

            $('.input-switch').bootstrapSwitch();
        });
    </script>
@endsection