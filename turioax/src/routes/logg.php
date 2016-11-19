<?php

get('/logg',function(){
    redirect('logg/index');
});

get('/logg/index', function(){
    header('Content-Type: text/plain');
    if (!defined('CA_LOG_FILE'))
        die('Logueo desabilitado');

    $log = CA_LOG_FILE;
    if (empty($log))
        die('Logueo desabilidado');

    $datos = file_get_contents(CA_LOG_FILE);
    $adatos = explode("\n", $datos);
    array_pop($adatos); // En blanco.

    $adatos = array_reverse($adatos);
    print_r($adatos);

});

?>
