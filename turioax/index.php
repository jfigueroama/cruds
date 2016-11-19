<?php
require_once("config.php");

incluir(CA_RT_PATH);    // Incluye todas las rutas y subrutas.

get('', function() {
    echo "Sistema temporal de captura del Sistema de Turismo.";
});

get('/403-error', function() {
    echo "A ocurrido un error: \n\n";
    echo '<pre>';
    print_r(stash('error'));
    echo '</pre>';

});


dispatch();

?>
