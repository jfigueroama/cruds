/* Apoyo al indice del scaffolder domain/:clase/index
 *
 */

$(window).load(function () {

     $( "#confirmar-borrar" ).dialog({
         resizable: false,
         height:140,
         modal: true,
         buttons: {
            "<b>Cancelar</b>": function() {
                $(this).dialog( "close" );
                },
            "Elimnar": function() {
                alert('Borraste!');
                $(this).dialog( "close" );
                }}
    });
});
