<?php
/**
 * Helper para crear un horario a partir de lo que mande el generador de horarios.
 *
 */


/**
 *
 *
 * $data = 
 * array('asignacio' =>
 *   array('espaciot_id' => N,        // Asignacion
 *         'espaciop_id' => N | null,
 *         'clases'      => array(
 *            Clase, Clase2, ...)));
 *
 * Clase: array(
 *   'tipo': "t" | "p",
 *   'dia': N (0-6),
 *   'hora': N (7,8,9 ...),
 *   'duracion': N (0-6))
 * 
 */
function crear_horario($ho, $data){
  //$tclase = array('t' => 1, 'p' => 2, 't/p' =>3);

  $asignaciones = $data;
  $asignaciones = [];

  foreach ($data as $dasig){
    $ismulti = ($dasig['multiasignacion_id'] == null) ? false : true;
    if ($ismulti){
      $mid = $dasig['multiasignacion_id'];
      $ma  = Multiasignacion::find($mid);

      foreach ($ma->asignaciones as $a){
        $nasig = $dasig;
        $nasig['multiasignacion_id'] = null;
        $nasig['asignacion_id']       = $a->id;
        $nasig['clases']              = $dasig['clases'];
        $asignaciones[] = $nasig;
      }
    }else{
      $asignaciones[] = $dasig;
    }
  }

  foreach ($asignaciones as $asig){
    /*
    $ismulti = ($asig['multiasignacion_id'] == null) ? false : true;

    $rattrib = ($ismulti) ? 'multiasignacion_id' : 'asignacion_id';

    $id = ($ismulti) ? $asig[$rattrib] : $asig[$rattrib];
//    $oa = ( $ismulti)
//      ? Multiasignacion::find($id)
//      : Asignacion::find($id);
     */
    $id = $asig['asignacion_id'];
    $condsql = "horario_id=? AND asignacion_id=?";
    $cond = array('conditions' => array($condsql, $ho->id, $id));

    $ohas = Horarioasignacion::find('all', $cond);

   // Borrando viejas horarioasignaciones 
    foreach ($ohas as $oha){
      $oha->delete();
    }


    $oha = new Horarioasignacion();
    $oha->horario_id    = $ho->id;
    $oha->asignacion_id = $id;

    $oha->espaciot_id = $asig['espaciot_id'];
    $oha->espaciop_id = $asig['espaciop_id'];

    $oha->save();

    /*
      // Borrando viejas clases.
      $oclases = $oha->horarioclases;
      foreach ($oclases as $oclase){
        $oclase->delete();
      }  */


    foreach ($asig['clases'] as $clase){
      $ohc = new Horarioclase();
      $ohc->horarioasignacion_id = $oha->id;
      $ohc->tipo      = $clase['tipo']; // Numerico 1 t, 2 p, 3 t/p (shtp)
      $ohc->dia       = $clase['dia'];
      $ohc->hora      = $clase['hora'];
      $ohc->duracion  = $clase['duracion'];

      $ohc->save();
      echo "$ohc->hora\n";
    }
  }
}

//////////////////////////////////////////
/////////////////////////////////////////
/////////////////////////////////////////

/**
 * Recibe horario y grupo.
 */
function datos_grupo($h, $g){
  $cond = array('conditions' => array('grupo_id=?', $g->id));
  $as = Asignacion::find('all', $cond);

  $tabla = array();
  foreach ($as as $a){
    // La tabla lleva:
    // - nombre de la materia y su diminutivo
    // - nombre del profesor con grado.
    // - Nombre del espacio de teoria
    // - Nombre del espacio de practica si lo hay
    // * Cada cosa va con datos extra como el id y la clase para visualizar los
    //   datos de edicion.

    /*
    $aattr  = ($a->multiasignacion_id == null)
              ? "asignacion_id": "multiasignacion_id";
    $idasig = ($a->multiasignacion_id == null)
               ? $a->id : $a->multiasignacion_id;

     */
    $cond = array('conditions' => array(
      "horario_id=? AND asignacion_id=?", $h->id, $a->id));

    $has = Horarioasignacion::find('all', $cond);
    if (count($has) == 0)
      throw new Exception(
        "Horarioasignacion no encontrada para grupo $g->codigo, asignacion $a->id .");

    $ha  = $has[0];

    $cond = array('conditions' => array(
      "horarioasignacion_id=?", $ha->id));
    $hcs = Horarioclase::find('all', $cond);

    $pr = $a->profesor;

    $ets = ($a->gespaciot) ? $a->gespaciot->espacios : [];
    $eps = ($a->gespaciop) ? $a->gespaciop->espacios : [];

    $espaciost = array_map(function($e){
      return ['id' => $e->id, 'nombre' => $e->nombre];
    }, $ets);

    $espaciosp = array_map(function($e){
      return ['id' => $e->id, 'nombre' => $e->nombre];
    }, $eps);

    $cls = $ha->horarioclases;
    $clases = array_map(function($hc){
      return ['id' => $hc->id, 'tipo' => $hc->tipo, 'duracion'=> $hc->duracion,
              'hora' => $hc->hora, 'dia' => $hc->dia ];
    }, $cls);

    $fila = array(
      'asignacion'        => $a->id,
      'asignatura'        => $a->asignatura->nombre,
      'alumnos'           => ($a->alumnos != 0) ? $a->alumnos : $g->alumnos,
      'profesor'          => $pr->nombre_formal,
      'horarioasignacion' => $ha->id,
      'espaciot'          => $ha->espaciot->nombre,
      'espaciot_id'       => $ha->espaciot_id,
      'espaciop'          => ($ha->espaciop_id) ?$ha->espaciop->nombre : "",
      'espaciop_id'       => $ha->espaciop_id,
      'espaciost'         => $espaciost,
      'espaciosp'         => $espaciost,
      'clases'            => $clases
    );
    $tabla[] = $fila;
  }

  return $tabla;

}

/**
 * Saca el nombre corte de una asignatura.
 * Por ejemplo:
 * Programacion Orintada a Objetos 2: POO2
 */
function nombre_corto($nombre){
  $partes = explode(' ', $nombre);
  $inis = array_map(function($p){
    return substr($p, 0, 1);
  }, $partes);
  
  return implode('', $inis);
}

/**
 * Saca los datos de las duraciones
 * Recibe el grupo, la tabla de datos y el horario.
 */
function horario_grupo($h, $g, $datos){
  $horas = array('h7', 'h8', 'h9', 'h10', 'h11', 'h12', 'h13', 'h14',
                 'h16', 'h17', 'h18');
  $tabla = array(
    'h7'  => array(true, true, true, true, true),
    'h8'  => array(true, true, true, true, true),
    'h9'  => array(true, true, true, true, true),
    'h10' => array(true, true, true, true, true),
    'h11' => array(true, true, true, true, true),
    'h12' => array(true, true, true, true, true),
    'h13' => array(true, true, true, true, true),
    'h14' => array(true, true, true, true, true),
    'h16' => array(true, true, true, true, true),
    'h17' => array(true, true, true, true, true),
    'h18' => array(true, true, true, true, true),
    'h19' => array(true, true, true, true, true)
  );

  foreach ($datos as $fila){
    $clases = $fila['clases'];

    foreach ($clases as $clase){
      $celdas   = [];
      $dia      = $clase['dia'];
      $hora     = $clase['hora'];
      $duracion = $clase['duracion'];

      for ($i=0; $i < $duracion; $i++){
        $celda  = $clase;
        $celda['data'] = $fila['asignatura'];

        $hor   = $hora + $i;
        $horai  = "h{$hor}";

        if ($tabla[$horai][$dia] === true){
          $tabla[$horai][$dia] = array($celda);
        }elseif (is_array($tabla[$horai][$dia])){
          $tabla[$horai][$dia] = array_merge($tabla[$horai][$dia], array($celda));
        }else{
          //echo "{$tabla[$horai][$dia]} $horai $dia";
          //print_r($clase);
          //throw new Exception("Que pedo con las tablas");
        }
      }
    }
  }

  return $tabla;
}


function procesar_grupos($horario, $grupos){
  $carrera_ids = array_map(function($g) {
    return $g->carrera_id;
  }, $grupos);

  $carreras = array_map(function($cid){
    return Carrera::find($cid);
  }, array_unique($carrera_ids));

  usort($carreras, function($a, $b){
    return $a->nombre > $b->nombre;
  });

  $ncarreras = array_map(function($ca) use ($horario, $grupos){
    $ngrupos = array_filter($grupos,function($g) use ($ca){
      return $g->carrera_id == $ca->id;
    });

    $semestres = array_unique(array_values(array_map(function($g){
      return $g->semestre;
    }, $ngrupos)));

    $nsemestres = array_values(array_map(function($s) use ($horario, $ngrupos){
      $sngrupos = array_filter($ngrupos, function($g) use ($s){
        return $g->semestre == $s;
      });
      $ugrupos = array_values(array_map(function($g) use ($horario){
        $datos = datos_grupo($horario, $g);
        return [
          'grupo'   => $g->codigo,
          'anio'    => $g->anio,
          'periodo' => $g->periodo,
          'datos'   => $datos,
          'horario' => horario_grupo($horario, $g, $datos)];
      }, $sngrupos));

      return ['semestre' => "s{$s}", 'grupos' => $ugrupos];

    }, $semestres));


    return ['carrera' => $ca->nombre, 'semestres' => $nsemestres];
  }, $carreras);


  return $ncarreras;

}


function hurl($id, $tipo = ''){
  if ($tipo == 'edit'){
    return site_url()."?/horario/$id/edit";
  }else{
    return site_url()."?/horario/$id/static";
  }
}

////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////

// EDICION DEL GRUPO

function datos_edicion($horario, & $grupos){

  $sql = "SELECT hc.id, hc.tipo, hc.dia, hc.hora, hc.duracion FROM
            horarioclase AS hc JOIN horarioasignacion AS ha 
              ON hc.asignacion_id=ha.id
            JOIN horario AS h ON ha.horario_id=h.id
            AND h.id={$horario->id}";
  $clases = Horarioclase::find_by_sql($sql);

  $profesores = array_map(function($g){
    $p = $g->asignacion->profesor;
    return array(
      'id'            => $g->profesor_id,
      'nombre'        => $p->nombre,
      'nombre_formal' => $p->nombre_formal);
  }, $grupos);

  $espacios = array_map(function($e){
    return array(
      'id'      => $e->id,
      'nombre'  => $e->nombre);
  }, Espacio::find('all'));

  $ngrupos = array_map(function($g){
    return array(
    );
  }, $grupos);

  // Solo espacios utilizados?? y si usan otros espacios?

  $data = array(
    'clases'     => & $clases,
    'profesores' => & $profesores,
    'grupos'     => & $ngrupos,
    'espacios'   => & $espacios
  );

  return $data;
}

/**
 * Recibe el horarioasignacion_id y devuelve las horas con info agregada para
 * la edicion.
 */
function horas_asignacion($haid){
  $sql =
    "SELECT
      ha.id AS horarioasignacion_id , ha.asignacion_id, ha.multiasignacion_id,
      a.anio, a.periodo,
      p.grado, p.nombres,  p.apellidos,
      
      hc.tipo, hc.dia, hc.hora, hc.duracion,
  ";
}

?>
