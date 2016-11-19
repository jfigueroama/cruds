<?php
define('SIN_FILTRO', -1);

function used_filters($clase){
    $fs = array();

    if (isset($clase::$used_filters)){
        return $clase::$used_filters;
    }
    return $fs;
}

function add_filters($filtros = array()){
    if (!isset($_SESSION['filters']))
        $_SESSION['filters'] = array();

    foreach ($filtros as $f => $v){
        $_SESSION['filters'][$f] = $v;
    }
}

function get_filters(){
    if (!isset($_SESSION['filters']))
        $_SESSION['filters'] = array();

    return $_SESSION['filters'];
}

function get_filter($name){
    $filters = get_filters();
    if (isset($filters[$name]))
        return $filters[$name];
    else
        return null;
}

/**
 * Construye un filtro html usando un valor default.
 */
function build_html_filter($clase, $filtro, $nombre, $valor = SIN_FILTRO){
    $f = '';
    $f .= "<label for='filters[$filtro]'>
        $nombre</label>&nbsp;
        <select name='filters[$filtro]' onchange='this.parentNode.submit()'>";

    $f.= "<option value='".SIN_FILTRO."'>--</option>\n";
//               <option value='0'>-- Sin $nombre --</option>\n";

    $instancias = $clase::find('all');  // TODO sustituir por index($filtros)
        // usort TODO
    usort($instancias, function($a, $b){
        return $a->_name > $b->_name;
    });
    foreach ($instancias as $i){
        $selected = '';

        if ($valor == $i->id){
            $selected =  ' selected="selected"';
        }

        $f.= "<option value='".$i->id."' $selected>". $i->_name.'</option>';
    }
    $f.= "</select>\n";
    return $f;
}

function build_html_plan_filter($valor){
    $sql = 'SELECT DISTINCT plan FROM asignatura ORDER BY plan';
    $instancias = Asignatura::find_by_sql($sql);

    $f = '';
    $f .= "
        <br/>
        <label for='filters[plan]'>
        Plan</label>&nbsp;
        <select name='filters[plan]' onchange='this.parentNode.submit()'>";

    $f.= "<option value='".SIN_FILTRO."'>--</option>";

            // usort TODO
    foreach ($instancias as $i){
        $selected = '';

        if ($valor == $i->plan){
            $selected =  ' selected="selected"';
        }

        $f.= "<option value='$i->plan' $selected>$i->plan</option>";
    }
    $f.= "</select>\n";

    return $f;
}

function build_html_semester_filter($valor){
    $instancias = Asignatura::$meta['semestre']['values'];
    $f = '';
    $f .= "
        <br/>
        <label for='filters[semestre]'>
        Semestre</label>&nbsp;
        <select name='filters[semestre]' onchange='this.parentNode.submit()'>";

    $f .= "<option value='".SIN_FILTRO."'>--</option>";

            // usort TODO
    foreach ($instancias as $va){
        $selected = '';
        $value = $va[0];
        $nom   = $va[1];

        if ($valor == $value){
            $selected =  ' selected="selected"';
        }

        $f.= "<option value='$value' $selected>$nom</option>";
    }
    $f.= "</select>\n";

    return $f;
}

function build_html_group_filter($valor, $filtros){
    $f = '';
    $f.= "
        <label for='filters[grupo_id]'>
        Grupo</label>&nbsp;
        <select name='filters[grupo_id]' onchange='this.parentNode.submit()'>";

    $f.= "<option value='".SIN_FILTRO."'>--</option>";

    $cond = array("1=1");
    if ($filtros['carrera_id'] != SIN_FILTRO){
        $cond[0] .= " AND carrera_id=? ";
        $cond[] = $filtros['carrera_id'];
    }
    if ($filtros['anio'] != SIN_FILTRO){
        $cond[0] .= " AND anio=? ";
        $cond[] = $filtros['anio'];
    }
    if ($filtros['periodo'] != SIN_FILTRO){
        $cond[0] .= " AND periodo=? ";
        $cond[] = $filtros['periodo'];
    }
    if ($filtros['semestre'] != SIN_FILTRO){
        $cond[0] .= " AND semestre=? ";
        $cond[] = $filtros['semestre'];
    }

    $instancias = Grupo::find('all', array('conditions' => $cond));

    // usort TODO
    foreach ($instancias as $i){
        $selected = '';

        if ($valor == $i->id){
            $selected =  ' selected="selected"';
        }

        $f.= "<option value='".$i->id."' $selected>". $i->_name.'</option>';
    }
    $f.= "</select>\n";

    return $f;
}

/**
 * Devuelte el valor del filtro si es que esta en el arreglo de filtros
 * enviado. Si no existe se manda el valor de SIN_FILTRO.
 */ 
function get_cfilter($filtro, $cfiltros = array()){
    return isset($cfiltros[$filtro]) ? $cfiltros[$filtro] : SIN_FILTRO;
}

function build_html_filters($clase, $cfilters = array(), $ofilters = null){
    $fs = used_filters($clase);

    $f = '';
    $f.= "<form method='post' action='' name='formfiltros'>";

    if (! (array_search('instituto_id', $fs) === false)){
        $f.= build_html_filter(Instituto, 'instituto_id',
                'Instituto', get_cfilter('instituto_id', $cfilters));
    }

    if (! (array_search('profesor_id', $fs) === false)){
        $f.= build_html_filter(Profesor, 'profesor_id',
                'Profesor', get_cfilter('profesor_id', $cfilters));
    }

    if (! (array_search('carrera_id', $fs) === false)){
        $f.= "<br/>";
        $cid = get_cfilter('carrera_id', $cfilters);
        $ocid = get_cfilter('carrera_id', $ofilters);
        
        $f.= build_html_filter(Carrera, 'carrera_id',
                'Carrera', $cid);
        
        if ($cid != $ocid && $ofilters){
            $cfilters['grupo_id'] = SIN_FILTRO;
            add_filters($cfilters);
        }
    }

    if (! (array_search('anio', $fs) === false)){
        $f.= "<br/>";
        $anio = get_cfilter('anio', $cfilters);
        $oanio = get_cfilter('anio', $ofilters);
        
        $f.= build_html_filter(Anio, 'anio',
            'A&ntilde;o', $anio);
        
        if ($anio != $oanio && $ofilters){
            $cfilters['grupo_id'] = SIN_FILTRO;
            add_filters($cfilters);
        }
    }

    if (! (array_search('periodo', $fs) === false)){
        $f.= "<br/>";
        
        $peri = get_cfilter('periodo', $cfilters);
        $operi = get_cfilter('periodo', $ofilters);
        
        $f.= build_html_filter(Periodo, 'periodo',
            'Periodo', $peri);
        
        if ($peri != $operi && $ofilters){
            $cfilters['grupo_id'] = SIN_FILTRO;
            add_filters($cfilters);
        }
    }

    if (! (array_search('plan', $fs) === false)){
        $plan = get_cfilter('plan', $cfilters);
        $oplan = get_cfilter('plan', $ofilters);
        
        $f.= build_html_plan_filter(get_cfilter('plan', $cfilters));
    }

    if (! (array_search('semestre', $fs) === false)){
        $seme = get_cfilter('semestre', $cfilters);
        $oseme = get_cfilter('semestre', $ofilters);
        
        $f.= build_html_semester_filter(get_cfilter('semestre', $cfilters));
        
        if ($seme != $oseme && $ofilters){
            $cfilters['grupo_id'] = SIN_FILTRO;
            add_filters($cfilters);
        }
    }
    
    if (! (array_search('grupo_id', $fs) === false)){
        $f.= "<br/>";
        
        $f.= build_html_group_filter(get_cfilter('grupo_id', $cfilters),
            array('carrera_id'=> get_cfilter('carrera_id', $cfilters),
                  'anio' => get_cfilter('anio', $cfilters),
                  'periodo' => get_cfilter('periodo', $cfilters),
                  'semestre' => get_cfilter('semestre', $cfilters)));
    }

    $f.= "</form>";

    return $f;
}

function set_meta_defaults($clase, $filtros = array()){
    if (method_exists($clase, 'set_defaults')){
        $clase::set_defaults($filtros);
    }
}

function set_meta_default($clase, $nombre, $filtros){
    $valor = isset($filtros[$nombre]) ? $filtros[$nombre] : SIN_FILTRO;
    
    if (isset($clase::$meta) &&
        isset($clase::$meta[$nombre]) &&
        $valor != SIN_FILTRO){

        $clase::$meta[$nombre]['default'] = $valor;
    }
}



/**
 * Devuelve los filtros inicializados si no existen en el arreglo de filtros
 * actuales. Osea, los filtros que yo mande en $filtros simpre van a tener un
 * valor.
 */
function get_init_filters($filtros = array(), $valores = array()){
    $res = array();
    foreach ($filtros as $filtro){
        $res[$filtro] =  get_cfilter($filtro, $valores);
    }
    return $res;
}


/**
 * Devuelve si el valor enviado es un filtro. Osea, que tiene valor
 * diferente a SIN_FILTRO.
 */
function es_filtro($vfiltro){
    $res = false;
    if ($vfiltro != SIN_FILTRO)
        $res = true;
    return $res;
}

/**
 * Sirve para comparar el valor de un filtro con otro valor.
 * Si el filtro no tiene valor (SIN_FILTRO) se devuelve un valor default que no
 * afecte un predicado con AND (true) u OR (false).
 *
 */
function filter_equal_to($vfiltro, $valor, $default = true){
    $res = $default;
    if ($vfiltro != SIN_FILTRO)
        $res = ($vfiltro == $valor);
    return $res;
}

function and_filter_equal_to($vfiltro, $valor){
    return filter_equal_to($vfiltro, $valor, true);
}

function or_filter_equal_to($vfiltro, $valor){
    return filter_equal_to($vfiltro, $valor, false);
}

?>
