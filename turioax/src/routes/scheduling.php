<?php
/***
 * Controller para las funciones relacionadas con la calendarizacion de
 * horarios como proceso.
 *
 */
require_once(CA_LIB_PATH.'scheduling_data.php');
require_once(CA_LIB_PATH.'formateo.php');

/*
get('/scheduling/kb', function(){

  redirect("/scheduling/data");
});

get('/scheduling/data', function(){

  $anio = configuracion('anio_actual');
  $periodo = configuracion('periodo_actual');

  redirect("/scheduling/data/$anio/$periodo");
});

 */

get('/scheduling/data/:multihorario', function($multihorario){
  $mh = Multihorario::find($multihorario);
  if ($mh != null){
    $data = gen_scheduling_data($mh);
    header('Content-Type: text/plain');
    jrender($data);
  }
});



/**
 * Genera los datos para la calendarizacion.
 *
 * Debe ir antes que las otras rutas.
 * TODO validar usuario root
 */
get('/scheduling/data/:anio/:periodo', function($anio, $periodo){
  $data = gen_scheduling_data($anio, array($periodo));

  header('Content-Type: text/plain');
  jrender($data);
});


get('/scheduling/data/:anio/:p1/:p2', function($anio, $p1, $p2){
  $data = gen_scheduling_data($anio, array($p1, $p2));

  header('Content-Type: text/plain');
  jrender($data);
});


get('/scheduling/data/:anio/:p1/:p2/:p3', function($anio, $p1, $p2, $p3){
  $data = gen_scheduling_data($anio, array($p1, $p2, $p3));

  header('Content-Type: text/plain');
  jrender($data);
});


/*
get('/scheduling/:cual', function($cual){
  $anio = configuracion('anio_actual');
  $periodo = configuracion('periodo_actual');

  
  redirect("/scheduling/$cual/$anio/$periodo");
});


get('/scheduling/:cual/:anio/:periodo', function($cual, $anio, $periodo){
  switch ($cual){
  case 'config':
    $data = gen_config($anio, $periodo);
    break;
  case 'kb':
    $data = gen_kb($anio, $periodo)['kb'];
    break;
  case 'problem-representation':
    $data = gen_problem_representation();
    break;
//  case 'bounds':
//    $data = gen_bounds($anio, $periodo);
//    break;
  case 'user-constraints':
    $data = gen_user_constraints($anio, $periodo);
    break;
  default:
    $data = null;
  }
  
  header('Content-Type: text/plain');
  echo json_encode($data, JSON_PRETTY_PRINT); 
});

 */
//////////////////////////////////////////////

get('/scheduling/start', function(){
  $anio = configuracion('anio_actual');
  $periodo = configuracion('periodo_actual');

  $data = gen_scheduling_data($anio, $periodo);

  $json = json_encode($data, JSON_PRETTY_PRINT);

  $b64 = base64_encode($json);
  $url = site_url().'?/scheduling/start';
  echo "
    <html>
    <body>
    <form action='$url' method='post'>
    <input type='hidden' value='$b64' name='data' />
    <input type='submit' value='Iniciar Calendarizaci&oacute;n' />
    </form>
    </body>
    </html>
    ";
});

post('/scheduling/start', function(){
  header('Content-Type: text/plain');

  $data = $_POST['data'];

  $json = base64_decode($data);
  $js   = json_decode($json, true);

  $bs   = $js['bounds'];
  $sol  = array();

  for ($i=0; $i < count($bs); $i++){
    $bu = $bs[$i];
    $l = count($bu);

    if ($l > 0){
      $cual = rand(0, $l - 1);
      $cosa = $bu[$cual];
    }else{
      $cosa = null;
    }

    array_push($sol, $cosa);
  }

  print_r(json_encode($sol));
});



?>
