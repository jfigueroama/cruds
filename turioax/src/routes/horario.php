<?php
/**
 *
 * @date 20160707
 * @author Jose Figueroa Martinez
 */

require_once(CA_HP_PATH.'horario.php');

filter('horarioid', function ($id) {
    try{
        $ho = Horario::find($id);
        stash('horario', $ho);

    }catch(Exception $e){
        show_error("No se pudo encontrar el horario con ID: $id", $e);
    }

});

get('/horario/:horarioid', function(){
  $ho = stash('horario');

  render('horario'.DS.'info', array('horario' => $ho));
});


//////////////////////////////////////////
/////////////////////////////////////////


get('/horario/:horarioid/static', function(){
  $horario = stash('horario');

  $has = $horario->horarioasignaciones;

  $multih = $horario->multihorario;
  $multihs = $multih->multihorariomiembros;

  $grupos = [];

  foreach ($multihs as $mhmiembro){
    $anio     = $mhmiembro->anio;
    $periodo  = $mhmiembro->periodo;

    $cond = array('conditions' =>
      array('anio=? AND periodo=?', $anio, $periodo));
    $grs = Grupo::find('all', $cond);

    //$as = Asignacion::find('all', $cond);
    //$mas = Multiasignacion::find('all', $cond);

    foreach ($grs as $g){
      $grupos[] = $g;
    }
  }

  $carreras = procesar_grupos($horario, $grupos);

  //debuga($carreras);
 // exit();
  
  render('horario'.DS.'stabla', array(
    'horario' => $ho,
    'carreras'=>$carreras,
    'csss'    => array('estilos_horarios.css')
  ), 'layout-horario');
});

get('/horario/:horarioid/edit', function(){
  $horario = stash('horario');

  $has = $horario->horarioasignaciones;

  $multih = $horario->multihorario;
  $multihs = $multih->multihorariomiembros;

  $grupos = [];

  foreach ($multihs as $mhmiembro){
    $anio     = $mhmiembro->anio;
    $periodo  = $mhmiembro->periodo;

    $cond = array('conditions' =>
      array('anio=? AND periodo=?', $anio, $periodo));
    $grs = Grupo::find('all', $cond);

    //$as = Asignacion::find('all', $cond);
    //$mas = Multiasignacion::find('all', $cond);

    foreach ($grs as $g){
      $grupos[] = $g;
    }
  }

  $data = datos_edicion($horario, $grupos);

  
  render('horario'.DS.'rtabla', array(
    'horario' => $ho,
    'data'=>$data,
    'csss'    => array('estilos_horarios.css')
  ), 'layout-horario');
});


////////////////////////////////////////////////////
////////////////////////////////////////////////////

post('/horario/:horarioid/fill', function(){
  $ho = stash('horario');

  $data = array(
    'ohorario' => $ho,
    'horario' => json_decode($_POST['horario'], true)
  );

  crear_horario($ho, $data['horario']);

  render('horario'.DS.'fill', $data, 'vacio');
});



