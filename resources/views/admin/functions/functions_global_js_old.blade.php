<script>
    /**
     * Método que va a cargar los valores de un formulario
     * @param {type} form : Formulario al que se le van a cargar los valores
     * @param {type} values : Valores
     * @param {type} extra_params : Parámetros extras, objeto JS
     * @returns {undefined}
     */
    var load_values_form = function (form, values, extra_params) {

        $.each($("#" + form + " input"), function (index, value) {
            if (typeof values[$(this).attr("name")] != "undefined") {
                $(this).val(values[$(this).attr("name")]);
            }
        });
    };


    var ajax_form = function (params) {
        //Busco los datos de la venta
        $.ajax({
            url: params.url,
            data: params.data,
            method: params.method,
            success: params.function_success,
            error: params.function_error
        });
    }


    var combo_related = function (params) {

        if (parseInt(params.id) > 0) {
            //Mando a buscar el combo relacionado
            var params_send = {
                url: BASE_URL + "/" + params.url + "/" + params.id,
                data: "",
                method: "GET",
                function_success: function (data) {
                    $("#" + params.id_related).prop("disabled", false);
                    if (data.result) {
                        $("#" + params.id_related).html("");
                        $("#" + params.id_related).append("<option value=\"\">&lt;Seleccione&gt;</option>");

                        $.each(data.rows, function (key, value) {
                            $("#" + params.id_related).append("<option value=\"" + value.id + "\">" + value.value + "</option>");
                        });
                        $("#" + params.id_related).change();
                    } else {
                        alertify.error("Error. No hay registros");
                    }
                },
                function_error: function (data) {
                    alertify.error("Error.");
                },
                beforeSend: function () {
                    $("#" + params.id_related).prop("disabled", true);
                }
            };
            ajax_form(params_send);
        } else {
            $("#" + params.id_related).html("");
            $("#" + params.id_related).append("<option value=\"\">&lt;Seleccione&gt;</option>");
            $("#" + params.id_related).change();
        }
    };

    var get_info_menu = function () {
        var params = {
            url: BASE_URL + "/get_info_menu_email",
            data: "",
            method: "GET",
            function_success: function (data) {
                if (data.result) {
                    if (parseInt(data.info.cantidad_no_leidos) > 0) {
                        $("#span_cantidad_emails").html(data.info.cantidad_no_leidos);
                    }
                }
            },
            function_error: function (data) {
            }
        };

        ajax_form(params);
    }


    /**
     * Funciones corrridas al final de todo
     */

    $(document).ready(function () {
        get_info_menu();
    });

</script>