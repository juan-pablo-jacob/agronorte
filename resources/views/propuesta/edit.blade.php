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
                            <small>Datos de producto Venta</small>
                        </span>
                        </a>
                    </li>
                    <li>
                        <a href="#step-3" data-toggle="tab" id="go_step_3">
                            <label class="wizard-step">3</label>
                            <span class="wizard-description">
                            Precio
                            <small>Visualización Productos Ventas</small>
                        </span>
                        </a>
                    </li>
                    @if($propuesta->tipo_propuesta_negocio_id == 3 || $propuesta->tipo_propuesta_negocio_id == 4)
                        <li>
                            <a href="#step-4" data-toggle="tab" id="go_step_4">
                                <label class="wizard-step">4</label>
                                <span class="wizard-description">
                                Producto
                                <small>Datos de toma de productos</small>
                            </span>
                            </a>
                        </li>

                        <li>
                            <a href="#step-5" data-toggle="tab" id="go_step_5">
                                <label class="wizard-step">5</label>
                                <span class="wizard-description">
                                Precio
                                <small>Visualización Productos Tomados</small>
                            </span>
                            </a>
                        </li>

                        <li>
                            <a href="#step-6" data-toggle="tab" id="go_step_6">
                                <label class="wizard-step">6</label>
                                <span class="wizard-description">
                                    Finalización
                                    <small>Finalizar propuesta</small>
                                </span>
                            </a>
                        </li>
                    @elseif($propuesta->tipo_propuesta_negocio_id == 1 || $propuesta->tipo_propuesta_negocio_id == 2)
                        <li>
                            <a href="#step-4" data-toggle="tab" id="go_step_4">
                                <label class="wizard-step">4</label>
                                <span class="wizard-description">
                                    Finalización
                                    <small>Finalizar propuesta</small>
                                </span>
                            </a>
                        </li>
                    @endif
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="step-1">
                        @include('propuesta.step_edit_1')
                    </div>
                    @if($propuesta->tipo_propuesta_negocio_id == 1 || $propuesta->tipo_propuesta_negocio_id == 3)
                        <div class="tab-pane" id="step-2">
                            @if(is_null($cotizacion_edit))
                                @include('propuesta.step_new_nuevo_2')
                            @elseif(!is_null($cotizacion_edit) && $step == 2)
                                @include('propuesta.step_edit_nuevo_2')
                            @endif
                        </div>
                    @elseif($propuesta->tipo_propuesta_negocio_id == 2 || $propuesta->tipo_propuesta_negocio_id == 4)
                        <div class="tab-pane" id="step-2">
                            @if(is_null($cotizacion_edit))
                                @include('propuesta.step_new_usado_2')
                            @elseif(!is_null($cotizacion_edit) && $step == 2)
                                @include('propuesta.step_edit_usado_2')
                            @endif
                        </div>
                    @endif
                    <div class="tab-pane" id="step-3">
                        @include('propuesta.step_show_ventas_3')
                    </div>
                    @if($propuesta->tipo_propuesta_negocio_id == 3 || $propuesta->tipo_propuesta_negocio_id == 4)

                        <div class="tab-pane" id="step-4">
                            @if(is_null($cotizacion_toma_edit))
                                @include('propuesta.step_new_toma_usado')
                            @elseif(!is_null($cotizacion_toma_edit) && $step == 4)
                                @include('propuesta.step_edit_toma_usado')
                            @endif
                        </div>
                        <div class="tab-pane" id="step-5">
                            @include('propuesta.step_show_toma_usado')
                        </div>
                        <div class="tab-pane" id="step-6">
                            @include('propuesta.step_edit_finalizacion_toma')
                        </div>
                    @elseif($propuesta->tipo_propuesta_negocio_id == 1 || $propuesta->tipo_propuesta_negocio_id == 2)
                        <div class="tab-pane" id="step-4">
                            @if($propuesta->tipo_propuesta_negocio_id == 1 || $propuesta->tipo_propuesta_negocio_id == 2 )
                                @include('propuesta.step_edit_finalizacion_nuevo_4')
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if (isset($step) && $step != "")
        <input type="hidden" id="request_step" value="{{$step}}"/>
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