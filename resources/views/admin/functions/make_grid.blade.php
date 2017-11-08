<script type="text/javascript">
    /**
     * Función encargada de hacer las  grillas "on the fly"
     * @param {type} params
     * @returns {undefined}
     */
    var make_grid = function (params)
    {

        console.log(params);

        /* Datatables init */


        $('#dynamic-table-user').dataTable();
        $("#dynamic-table-user_length").hide();
        $("#dynamic-table-user_filter").hide();

        /* Add sorting icons */

        $("table.dataTable .sorting").append('<i class="glyph-icon"></i>');
        $("table.dataTable .sorting_asc").append('<i class="glyph-icon"></i>');
        $("table.dataTable .sorting_desc").append('<i class="glyph-icon"></i>');




        
    }

</script>