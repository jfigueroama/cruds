<?php

/* Devuelve un text con todos los caracteres especiales pasados a su
 * representacion en html entities.
 */

if (! defined('ENT_HTML5'))
    define('ENT_HTML5', null);

function debuga($arr){
    echo '<pre>';
    print_r($arr);
    echo '</pre>';
}

function h($texto){
    $ntext = htmlentities($texto, ENT_HTML5, "UTF-8");
    if (empty($ntext)){
        // Al final todos los textos van a quedar con UTF-8 :-)
        $ntext = htmlentities($texto, ENT_HTML5 | ENT_IGNORE, "ISO-8859-1");
    }
    if (empty($ntext)){
        $ntext = $texto;
    }
    return $ntext;
    //return $texto;
}

// Contrario a h()
function dh($texto){
    $nt = iconv('UTF-8', 'UTF-8', $texto);
    if ($nt == ""){
        $nt = utf8_encode($texto);
    }
    return html_entity_decode($nt, ENT_HTML5, 'UTF-8');
}

// Formato para la fecha estandard
function ffecha(){
    return 'd/m/Y';
}

// Limpia caracteres raros convertidos de latin1 a utf8 sin proteccion.
function limpiar_raros($nombre){
    $nombre = preg_replace('/Ã¡/','á', $nombre);
    $nombre = preg_replace('/Ã©/','é', $nombre);
    $nombre = preg_replace('/Â/', 'í', $nombre);
    $nombre = preg_replace('/Ã³/','ó', $nombre);
    $nombre = preg_replace('/Ã/','í', $nombre);
    $nombre = preg_replace('/í/','Á', $nombre);
    $nombre = preg_replace('/±/','ñ', $nombre);
    $nombre = preg_replace('/íº/','ú', $nombre);
    $nombre = preg_replace('/í“/','Ó', $nombre);
    $nombre = preg_replace('/í¼/', 'ü', $nombre);
    $nombre = preg_replace('/Â/','', $nombre);
    
    return $nombre;
}

/**
 * Devuelve en un arreglo los elementos de una cadena separados por coma o
 * espacios o por cualquier otra cosa. Extrae las horas de uno o dos digitos.
 */
function extraer_horas($s){
    $horas = array();
    preg_match_all('/\d{1,2}/', $s, $horas);

    return $horas;
}

/**
 * Remueve los elementos de $aquitar de $target
 */
function array_remove($target, $aquitar = array()){
    return array_filter($target, function ($e) use ($aquitar){
        return (array_search($e, $aquitar) === false);
    });
}

function incluir($path){
    $frutas = dir($path);

    while (false !== ($fruta = $frutas->read())){
        if ($fruta != '.' && $fruta != '..'){
            $len = strlen($fruta);
            $afruta = $path.$fruta;
            if (!is_dir($afruta)){
                $extension = substr($fruta, $len - 3, 3);
                if ($extension == 'php'){   // solo php's
                    require_once($afruta);
                }
            }else{
                incluir($afruta.DS);
            }

        }   
    }
}



// Saca salida por json con cabeceras dadas y cors permitido.
function jrender($data, $headers = array(), $pp = true){
  $headers['Access-Control-Allow-Origin'] = '*';

  foreach ($headers as $k => $v){
    header("$k: $v");
  }

  if ($pp)
    echo json_encode($data, JSON_PRETTY_PRINT);
  else
    echo json_encode($data);
}

/**
 * Convierte una cadena de utf8 (las veces que sea) a otro.
 * TODO cambiar para que complete la codificacion ISO-8859-1.
 */
function _utf8_decode($string){
    $tmp = $string;
    $count = 0;
    while (mb_detect_encoding($tmp)=="UTF-8")
    {
        $tmp = utf8_decode($tmp);
        $count++;
    }

    for ($i = 0; $i < $count-1 ; $i++)
    {
        $string = utf8_decode($string);

    }


    return $string;

}

function limpiar($string){
    return limpiar_raros(dh(h($string)));
}


?>
