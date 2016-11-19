<?php
/**
 *
 *
 *
 */

/*
filter('wsclase', function ($clase) {
    if (isset($clase) &&
        is_subclass_of($clase, 'ActiveRecord\Model')){
            stash('clase', $clase);
    }else{
        show_error('Entidad del dominio inexistente: '.
            strval($clase));

    }
});
 */

function instancias($clase, $cond = ""){
    if (empty($cond)){
        $r = $clase::find('all');
    }else{
        $r = $clase::find(array('conditions' => $cond));
    }

    return $r;
}

function esentidad($clase){
    return is_subclass_of($clase, 'ActiveRecord\Model');
}

get('/ws/:wsclase', function($wsclase){
    if (isset($wsclase) && esentidad($wsclase)){
        $datos = instancias($wsclase);

        echo json_encode(array_map(function ($i){
            return $i->to_json();

        }, $datos), true);

    }else{
        show_error('Entidad del dominio inexistente: '.
            strval($wsclase));
    }
    
});

get('/ws/:wsclase/:wsfiltro', function($wsclase, $wsfiltro){
    if (isset($wsclase) && esentidad($wsclase)){
        $datos = instancias($wsclase, $wsfiltro);

        echo json_encode($datos, true);

    }else{
        show_error('Entidad del dominio inexistente: '.
            strval($wsclase));
    }
    
});

