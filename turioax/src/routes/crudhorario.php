<?php

get('/crudhorario', function(){
    echo "Indice principal para acceder a la edicion de horarios de forma
        practica";

    echo "<br/><br/>";

    $hs = Horario::find('all');

    foreach ($hs as $h){
        echo "<a href='".site_url()."?/crudhorario/$h->id'>$h->_name</a><br/>";
    }

});

filter('hid', function ($hid) {
    try{
        $obj   = Horario::find($hid);
        stash('h', $obj);

    }catch(Exception $e){
        show_error("No se pudo encontrar el horario con id $hid", $e);
    }
});

get('/crudhorario/:hid', function(){
//    $masignaciones = Multiasignacion::find('all');

    $h = stash('h');
    echo $h->_name;

    $hss = Horarioasignacion::find_by_sql(
        'SELECT * FROM horarioasignacion WHERE asignacion');

});

