<?php
/**
 * 
 * TODO separar en archivos en una subcarpeta en lib y unificar en este archivo.
 * @requires Activerecord models
 *
 *
 *
 * Defaults importantes:
 * - anio_actual
 * - periodo_actual
 * - horas_clase_default
 * - restriccionh_default
 * - chorario_default
 * - gespacio_default
 * - dias_laborales
 * - sobrepoblacion_max
 *
 */
function configuracion($clave){
    $o = Configuracion::first(array("conditions" =>
        array("clave=?", $clave)));
    if (!$o){
        throw new Exception("Debe definir una configuraci&oacute;n '$clave'.");
    }

    return $o->valor;
}

/**
 * Busca el valor de un parametro de planificacion de horarios en los
 * parametros enviados.
 */
function parametro_horario($param, $parametros = null){
    $res = null;

    if (isset($parametros))
        if (isset($parametros[$param]))
            $res = $parametros[$param];

    return $res;
}



function get_pdo_conn(){
    return Configuracion::connection()->connection;
}

function ids_bysql($sql, $params = null){
    $conn = get_pdo_conn();

    if (is_null($params)){
        $stm = $conn->query($sql);
    }else{
        $stm = $conn->prepare($sql);
        $stm->execute($params);
    }

    $objs = $stm->fetchAll(PDO::FETCH_NUM);

    $ids = array(0);  // Default 0 para no generar un error por SQL " id IN ()"
    foreach ($objs as $obj){
        $ids[] = $obj[0];  // Columna cero para recibir cualquier tipo de id.
    }

    return implode(', ', array_unique($ids));
}

function intval_or_null($val){
  if ($val === null){
    return $val;
  }else{
    return intval($val);
  }
}

function context_addbysql(&$ctx, $clave, $sql, $params = null, $converts = null, $defaults = null){
    $conn = get_pdo_conn();

    if (is_null($params)){
        $stm = $conn->query($sql);
    }else{
        $stm = $conn->prepare($sql);
        $stm->execute($params);
    }

    $objs = $stm->fetchAll(PDO::FETCH_ASSOC);
    $lc = count($objs);

    // convirtiendo id en entero y claves foraneas en integero null.
    for ($i=0; $i < $lc; $i++){
      ($objs[$i])['id'] = intval(($objs[$i])['id']);

      foreach ($objs[$i] as $k => $v){
        if (substr($k, -3) == '_id'){
          ($objs[$i])[$k] = intval_or_null($v);
        }
        if ($k == 'anio'){
          ($objs[$i])[$k] = intval($v);
        }

      }
    }

    // aplicar defaults
    for ($i=0; $i< $lc; $i++){
      if (!is_null($defaults)){
        foreach ($defaults as $attr => $dvalue){
          if (empty(($objs[$i])[$attr]) || is_null(($objs[$i])[$attr])){
            ($objs[$i])[$attr] = $dvalue;
          }
        }
      }
    }


    // convertir valores
    for ($i=0; $i< $lc; $i++){
      if (!is_null($converts)){
        foreach ($converts as $fn => $attrs){
          foreach ($attrs as $attr){
            ($objs[$i])[$attr] = $fn(($objs[$i])[$attr]);
          }
        }
      }
    }


    //print_r($objs);

    //$ctx[$clave] = array();

    if (!isset($ctx[$clave])){
      $ctx[$clave] = array();
    }

    foreach ($objs as $obj){
      $id = $obj['id'];     // Se usa el id de la instancia para localizarla fast
      $ctx[$clave][$id] = $obj;
     // $ctx[$clave] = $objs;
    }


    //print_r($ctx[$clave]);
    return $objs;
}


function gen_perianio($cual, $valores, $pre = ""){
  $d = implode(" OR ", array_map(function($p) use ($cual, $pre){
      $np = addslashes($p);
      return "{$pre}{$cual}='$np'";
    }, $valores));

    return "($d)";
}


function gen_periodo($periodos, $pre = ""){
    return gen_perianio('periodo', $periodos, $pre);
}

function gen_anios($anios, $pre = ""){
    return gen_perianio('anio', $anios, $pre);
}

/////////////
/// SEC
///////////

/**
 * Genera la configuracion para la calendarizacion.
 */ 

function gen_kb($anios, $periodos){

    $data    = array();
    $dparams = array();

    $anisql = gen_anios($anios);
    $perisql = gen_periodo($periodos);
    context_addbysql($data, 'multiasignacion',
        "SELECT * FROM multiasignacion
        WHERE $anisql AND $perisql", $dparams,
        array('intval' => array('thorario')),
        array('gespaciot_id' => configuracion('gespacio_default'),
              'chorario'     => configuracion('chorario_default')));

    context_addbysql($data, 'asignacion',
      "SELECT * FROM asignacion 
        WHERE $anisql AND $perisql", $dparams,
        array('intval' => array('thorario')),
        array('gespaciot_id' => configuracion('gespacio_default'),
              'chorario'     => configuracion('chorario_default')));

    $asignaciones = ids_bysql(
      "SELECT id FROM asignacion WHERE $anisql AND $perisql", $dparams);

    $masignaciones = ids_bysql(
      "SELECT id FROM multiasignacion WHERE
        $anisql AND $perisql", $dparams);

    $anisql = gen_anios($anios, 'a.');
    $perisql = gen_periodo($periodos, 'a.');
    // Profes ligados a las asignaciones
    $sql = 'SELECT p.id FROM profesor AS p JOIN asignacion AS a '.
        "ON a.profesor_id=p.id WHERE $anisql AND $perisql";
    $profes_ligados = ids_bysql($sql, $dparams);

    context_addbysql($data, 'profesor',
        "SELECT * FROM profesor WHERE id IN ($profes_ligados)");

    $anisql = gen_anios($anios, 'ag.');
    $perisql = gen_periodo($periodos, 'ag.');
    $sql = 'SELECT at.id FROM asignatura AS at JOIN asignacion AS ag '.
        "ON ag.asignatura_id=at.id WHERE $anisql AND $perisql";
    $asignaturas_ligadas = ids_bysql($sql, $dparams);

    context_addbysql($data, 'asignatura',
      "SELECT * FROM asignatura WHERE id IN ($asignaturas_ligadas)");
      

    $sql = 'SELECT g.id FROM grupo AS g JOIN asignacion AS ag '.
        "ON ag.grupo_id=g.id WHERE $anisql AND $perisql";
    $grupos_ligados = ids_bysql($sql, $dparams);


    context_addbysql($data, 'grupo',
      "SELECT * FROM grupo WHERE id IN ($grupos_ligados)",
      null, array('intval' => array('alumnos')));

     context_addbysql($data, 'recursamiento',
      "SELECT * FROM recursamiento WHERE asignacion_id IN ($asignaciones)",
      null, array('intval' => array('alumnos'))); 

    $sql = "SELECT recurso_id AS id FROM asignacionrecurso ".
        "WHERE asignacion_id IN ($asignaciones)";
    $recursos_ligados = ids_bysql($sql);

    context_addbysql($data, 'recurso',
      "SELECT * FROM recurso WHERE id IN ($recursos_ligados)", null,
      array('intval' => array('capacidad', 'tipo')));

    context_addbysql($data, 'asignacionrecurso',
        "SELECT * FROM asignacionrecurso WHERE asignacion_id IN ($asignaciones)");

    context_addbysql($data, 'espaciorecurso',
        "SELECT * FROM espaciorecurso WHERE recurso_id IN ($recursos_ligados)");

    // catalogos
    context_addbysql($data, 'espacio',
      "SELECT * FROM espacio", null,
      array('intval' => array('capacidad')));

    context_addbysql($data, 'espaciodiscapacidad',
      "SELECT * FROM espaciodiscapacidad", null,
      array('intval' => array('capacidad')));

    context_addbysql($data, 'grupodiscapacidad',
      "SELECT * FROM grupodiscapacidad WHERE id IN ($grupos_ligados)", null);

    context_addbysql($data, 'profesordiscapacidad',
      "SELECT * FROM profesordiscapacidad
        WHERE profesor_id IN ($profes_ligados)", null);

    context_addbysql($data, 'gespacio',
        "SELECT * FROM gespacio");

    context_addbysql($data, 'gespaciomiembro',
        "SELECT * FROM gespaciomiembro");

    context_addbysql($data, 'carrera',
        "SELECT * FROM carrera");

    context_addbysql($data, 'instituto',
        "SELECT * FROM instituto");

    context_addbysql($data, 'especialidad',
        "SELECT * FROM especialidad");

    context_addbysql($data, 'discapacidad',
        "SELECT * FROM discapacidad");

    $anisql = gen_anios($anios);
    $perisql = gen_periodo($periodos);
    context_addbysql($data, 'rprofesor',
        "SELECT * FROM rprofesor
        WHERE 
        profesor_id IN ($profes_ligados) AND
        (($anisql AND $perisql) OR
        (anio=0 AND $perisql) OR
        (anio=0 AND periodo=''))", $dparams);

    context_addbysql($data, 'rgrupo',
        "SELECT * FROM rgrupo
        WHERE
        grupo_id IN ($grupos_ligados) AND
        (($anisql AND $perisql) OR
        (anio=0 AND $perisql) OR
        (anio=0 AND periodo=''))", $dparams);

    context_addbysql($data, 'respacio',
        "SELECT * FROM respacio
        WHERE ($anisql AND $perisql) OR
        (anio=0 AND $perisql) OR
        (anio=0 AND periodo='')", $dparams);

    context_addbysql($data, 'dia',
        "SELECT * FROM dia");

    return $data;
}

////////////////////////////
//// SEC
/////////////

/**
 * Retorna un arreglo base con clave "scheduling"
 * y subvalores como "teaching_hours" defaults.
 *
 */
function gen_scheduling_extras($anio, $periodo){
  $data = array();

  $objs = Configuracion::find('all');

  $converts = array(
    'anio'               => 'intval',
    'dias_laborales'     => 'intval',
    'sobrepoblacion_max' => 'intval');

  foreach ($objs as $obj){
    if (isset($converts[$obj->clave])){
      $fn    = $converts[$obj->clave];
      $valor = $fn($obj->valor);
    }else{
      $valor = $obj->valor;
    }

    $data[$obj->clave] = $valor;
  }

  $data['anio']    = $anio;
  $data['periodo'] = $periodo;

  return array('scheduling' => $data);
}


/**
 * Genera el arreglo de restricciones del usuario.
 *
 * El arreglo extra se usa para habilitar/deshabilitar o para valorar una
 * restriccion.
 */
function gen_user_constraints($anios, $periodos, $extra = array()){
    $data = array();


    return array('user-constraints' => $data);
}

function gen_scheduling_data($multihorario){
    $multios  = $multihorario->multihorariomiembros;

    $anios    = array();
    $periodos = array();

    foreach ($multios as $mos){
      $anios[]    = $mos->anio;
      $periodos[] = $mos->periodo;
    }

    $salida = array_merge(
      gen_kb($anios, $periodos),
      gen_scheduling_extras($anios, $periodos)
      );

    // TODO: Antes de enviar:
    // - setear cualquier default
    // - convertir cualquier tipo de datos que sea necesario operar (ints, etc)

    return $salida;
}

?>
