<?php

function view(){
    $clase = stash('clase');
    $obj = stash('obj');

 //   $eliminar = isset($_POST['eliminar']) ? intval($_POST['eliminar']) : 0;
 //   if ( $eliminar != 0){
 //       // eliminar
 //       $obj->delete();
 //   }

    render("domain/view", array('obj' => $obj, 'clase' => $clase));
}

function view_params(){
    $params = stash('params');

    echo "View con parametros:\n";
    print_r($params);
    print_r($_GET);
}

function view_partial(){
    echo "view parcial";
}

?>
