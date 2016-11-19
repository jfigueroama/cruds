<?php

function limpiar_attrs($objs = array(), $attrs = array()){
    foreach ($objs as $o){
        foreach ($attrs as $a){
            $o->$a = limpiar($o->$a);
            echo $o->$a;
            echo " | ";
        }
        $o->save();
        echo "<br/>";
    }
    return $objs;
}



get('/test/fixnames', function(){
    echo "
    <!DOCTYPE html>
    <html>
        <head>
            <meta name='charset' value='utf8' />
        </head>
        <body>
        ";

    if (true){
        limpiar_attrs(Carrera::find('all'), array('nombre'));
        limpiar_attrs(Instituto::find('all'), array('nombre'));
        limpiar_attrs(Asignatura::find('all'), array('nombre', 'nombre_corto', 'comentario'));
        limpiar_attrs(Asignacion::find('all'), array('comentario'));
        limpiar_attrs(Cargo::find('all'), array('nombre'));
        limpiar_attrs(Especialidad::find('all'), array('nombre'));
        limpiar_attrs(Espacio::find('all'), array('nombre'));
        limpiar_attrs(Tespacio::find('all'), array('nombre'));
        limpiar_attrs(Recurso::find('all'), array('nombre'));
        limpiar_attrs(Grupo::find('all'), array('comentario'));
        limpiar_attrs(Discapacidad::find('all'), array('nombre'));
        limpiar_attrs(Multiasignacion::find('all'), array('comentario'));
        limpiar_attrs(Personal::find('all'), array('nombres', 'apellidos'));
        limpiar_attrs(Profesor::find('all'), array('nombres', 'apellidos', 'comentario'));



    }


    echo "</body></html>";
});


get('/test/daysinorder/:confirm', function($confirmm){
  $as = Asignacion::find('all');
  $mas = Multiasignacion::find('all');

  header('Content-Type: text/plain');
  $chorario_default = array(
    'lgs' => array(
      array('lectures'    => array(array('t' => 't', 'd' => 1, 'n' => 5)),
            'hours'       => array(8, 9, 10, 11, 12, 13, 16, 17, 18),
            'days'        => array(0,1,2,3,4),
            'daysinorder' => false)
    ),
    'samehourtp' => false,
  );

  $confirm = (intval($confirmm) == 1) ? true : false;

  if (!$confirm){
    echo "Cambios no confirmados. Mande 1  en vez de 0 para confirmar.";
  }else{
    echo "Cambios confirmados!!!";
  }
  echo "\n\n";


  foreach ($as as $a){
    $chorario = $a->chorario;
    $json = json_decode($chorario, true);
    if ($json){
      for ($i=0; $i< count($json['lgs']); $i++){
        (($json['lgs'])[$i])['daysinorder'] = false;
      }
    }else{
      $json = $chorario_default;
    }

    echo json_encode($json);
    echo "\n\n";

    if ($confirm){
      $a->chorario = json_encode($json);
      $a->save();
    }
  }

  echo "Multiasignaciones: \n\n";
  foreach ($mas as $a){
    $chorario = $a->chorario;
    $json = json_decode($chorario, true);
    if ($json){
      for ($i=0; $i< count($json['lgs']); $i++){
        (($json['lgs'])[$i])['daysinorder'] = false;
      }
    }else{
      $json = $chorario_default;
    }

    echo json_encode($json);
    echo "\n\n";
    if ($confirm){
      $a->chorario = json_encode($json);
      $a->save();
    }
  }

});

get('/test/daysinorder', function(){
  redirect('/test/daysinorder/0');
});


/**
 * Migracion para meter datos default a las asignaciones y multiasignaciones.
 *
 *
 */
get('/test/migracionbounds', function(){
  
    $masignaciones = Multiasignacion::find('all');
    $asignaciones  = Asignacion::find('all');

    echo "MULTIASIGNACIONES<br/><br/>";

    foreach ($masignaciones as $ma){
        if ($ma->gespaciot_id == null){
            //echo "Problemas en $ma->id <br/>";
            echo "Cambiando gespacit_id en $ma->id a 101. <br/>";
            $ma->gespaciot_id = 101;
            $ma->save();

        }

        if ($ma->gespaciop_id != null){
            echo "Comentario de $ma->id: $a->comentario <br/>";
        }
    }

    echo "<br/><br/>ASIGNACIONES<br/><br/>";
    foreach ($asignaciones as $a){
        if ($a->gespaciot_id == null){
            echo "Cambiando gespacit_id en $a->id a 101. <br/>";
            $a->gespaciot_id = 101;
            $a->save();
        }

        if ($a->gespaciop_id != null){
            echo "Comentario de $a->id: $a->comentario <br/>";
        }

    }
});

get('/test/tests', function(){
 //   header('Content-type: text/plain');

    echo "<pre>";

    //print_r(horas_clase_grupo(Grupo::find(120)));

    //print_r(horario_laboral_default());

    //print_r(horas_clase_profesor(Profesor::find(2)));
    //print_r(hbound(412));
    print_r(array_remove(array(8,9,10,11,12,13), array(9,11,13,16)));

    echo "</pre>";
});



get('/test/hbounds', function(){
//    header('Content-Type: text/plain');

    $ps = Profesor::find('all');
    $labdays=   5;
    $hdef   =   2; // TODO sacar de configuracion "horario_laboral_default"
    $impar  =   array(1,3,5,7,9);
    $par    =   array(2,4,6,8,10);
    $anio   =   2015;
    $periodo=   'A';
    
    $grupos =   array();
    $asignaturas    =   array();
    $asignaciones   =   array();
    $profesores     =   array();
    $espaciost      =   array();
    $espaciosp      =   array();
    $horasc         =   array();
    $duracion       =   array(1,2,3,4,5);
    $casignacion    =   array(); //para guardar asignaciones repetidas

    $bounds         =   array();
    $metabounds     =   array();
    // BY HAND SEARCH OF GRUPO AND ASIGNATURA RETRIVING WITHOUT SEMESTRE FILTERING
//    $sentence   =   "select a.id as grupo, b.id as asignatura from asignatura b, grupo a where a.carrera_id = b.carrera_id and a.semestre = b.semestre and a.anio = $anio and a.periodo = '$periodo' order by a.carrera_id desc";
//    $gs    =   Asignatura::find_by_sql($sentence);
    // FLEXIBLE GRUPO AND ASIGNATURA RETRIVING (ACCORDING TO SEMESTER REGISTERED ASIGNMENTS, YEAR AND PERIOD)
//    $joins  =   'LEFT JOIN grupo a ON(asignatura.carrera_id = a.carrera_id and asignatura.semestre = a.semestre)';
//    $gs    =   Asignatura::find('all', array('joins' => $joins, 'select' => 'a.id as grupo,asignatura.id as asignatura' ,'conditions' => array('a.semestre in (?) and a.anio = ? and periodo = ?',$impar, $anio, $periodo), 'order' => 'a.carrera_id desc'));
    //  CUSTOM RETRIVING OF GRUPO AND ASIGNATURA
    $gs =  Asignacion::find('all', array('conditions' => array('anio = ? and periodo = ?', $anio, $periodo)));
    foreach ($gs as $as){
        if (!in_array($as->id,$casignacion)){
        // CUSTOM RETRIVING MULTIGRUPO Y/O MULTIPROFESOR

            $multi  =   $as->multiasignados;
            if($multi){
                $multitipo = $multi[0]->multiasignacion->tipo;
                if($multitipo[0]->tipo == 1){
                    $mgrupos = $multi[0]->multiasignacion->multiasignados;
                    foreach($mgrupos as $grupo){
                        $data   = Asignacion::find("all",array('conditions'=>array('id=?',$grupo->asignacion_id)));
                        array_push($grupos,         intval($data[0]->grupo_id));
                        array_push($asignaciones,   intval($grupo->asignacion_id));
                        array_push($asignaturas,    intval($data[0]->asignatura_id));
                        array_push($casignacion,    $grupo->asignacion_id);
                    }
                    array_push($profesores, intval($as->profesor));
                }else{
                    $mprofesores = Multiasignado::find("all",array('conditions'=>array('multiasignacion_id=?',$multitipo[0]->id)));
                    foreach($mprofesores as $profesor){
                        $data   = Asignacion::find("all",array('conditions'=>array('id=?',$profesor->asignacion_id)));
                        array_push($profesores,intval($data[0]->profesor_id));
                        array_push($casignacion, $profesor->asignacion_id);
                    }
                    array_push($grupos,         intval($as->grupo));          
                    array_push($asignaciones,   intval($as->asignacion));
                    array_push($asignaturas,    intval($as->asignatura));
                }
            }else{
                echo "No hay multiasignacion";

                array_push($grupos,         intval($as->grupo));
                array_push($profesores,     intval($as->profesor));
                array_push($asignaciones,   intval($as->asignacion));
                array_push($asignaturas,    intval($as->asignatura));
            }
        /*  
        print("grupos");
        print_r($grupos);
        print("profesores");
        print_r($profesores);*/


            $horas  =   Asignatura::find("all", array('select'=>'hxs_teoria as ht, hxs_practica as hp', 'conditions'=>array('id = ?',$as->asignatura)));
            // CUSTOM RETRIVING OF ESPACIOS OF TEORIA Y PRACTICA        
            $espacios_teoria    = NULL;
            $espacios_practica  = NULL;
            if ($as->gespaciot_id != NULL and $horas[0]->ht != 0){
 //           print($as->gespaciot_id);
                $espacios_teoria    =   Gespaciomiembro::find("all", array('select'=>'espacio_id as espacios_teoria', 'conditions'=>array('gespacio_id = ?',$as->gespaciot_id)));
//            print_r($espacios_teoria);
            }else{
                // Cuando no hay definido un espacio para teoria, pero se necesita, se toman en cuenta todas las aulas de licenciatura disponibles.
                $espacios_teoria    =   Gespaciomiembro::find("all", array('select'=>'espacio_id as espacios_teoria', 'conditions'=>array('gespacio_id = 101')));
            }
            if ($as->gespaciop_id != NULL and $horas[0]->hp != 0){
                $espacios_practica   =   Gespaciomiembro::find("all",array('select'=>'espacio_id as espacios_practica', 'conditions'=>array('gespacio_id = ?',$as->gespaciop_id)));
            }else if( $horas[0]->hp != 0){
                // cuando no hay definido un espacio para practica, pero se necesita, se toman en cuenta todas las salas de licenciatura disponibles
                $espacios_practica   =   Gespaciomiembro::find("all",array('select'=>'espacio_id as espacios_practica', 'conditions'=>array('gespacio_id = 100')));
            }
            //print_r($as->to_json());
            //print_r($horas[0]->to_json());
            if ($espacios_teoria != NULL) {
                foreach($espacios_teoria as $et){
                    array_push($espaciost,intval($et->espacios_teoria));
                }
            }
            if ($espacios_practica != NULL){
                foreach($espacios_practica as $ep){
                    array_push($espaciosp,intval($ep->espacios_practica));
                }   
            }
        /*
        echo "asignacion $as->asignacion \r";
        print("teoria");
        print_r($espaciost);
        print("practica");
        print_r($espaciosp);
        */
            // CUSTOM RETRIVING OF HOURS BOUNDS from Profesor table
            foreach($profesores as $p){
                $hora_profesor = Profesor::find("all",array('select'=>'horariolaboral_id, horasnodisponibles', "conditions"=>array('id =?',$p)));
                //Asignando horario laboral default a los profesores que no tienen horario laboral asignado
                if($hora_profesor[0]->horariolaboral_id == 0){
                    $hora_profesor[0]->horariolaboral_id = $hdef;
                }
                $horario_laboral = HorarioLaboral::find("all",array('select'=>'horas', 'conditions'=>array('id = ?',$hora_profesor[0]->horariolaboral_id)));
                $horas  =   explode(',',$horario_laboral[0]->horas);
               // restando horas no disponibles a horariolaboral
                if($hora_profesor[0]->horasnodisponibles != NULL){
                    //                print_r($hora_profesor[0]->horasnodisponibles);
                    $hnd    =   explode(',',$hora_profesor[0]->horasnodisponibles);
                    //asignando bounds de horario laboral con horas no disponibles
                    foreach($horas as $h){
                        if(!in_array($h, $hnd) and !in_array($h, $horasc)){
                            array_push($horasc, intval($h));
                        }
                    }
                }else{
                    // asignando bounds de horario laboral
                    foreach($horas as $h){
                        if(!in_array($h, $horasc)){
                            array_push($horasc, intval($h));
                        }
                    }
                }       
            }
            //print_r($horasc);



        //ASIGNAR BOUNDS A ESA PARTE DEL VECTOR SOLUCION
            array_push($metabounds,$grupos);
            array_push($metabounds,$asignaturas);
            array_push($metabounds,$asignaciones);
            array_push($metabounds,$profesores);
            array_push($bounds,$espaciost);
            array_push($bounds,$espaciosp);
            for($day = 1; $day <= $labdays*2; $day++){
                array_push($bounds, $horasc);
                array_push($bounds, $duracion);
            }

        //LIBERAR MEMORIA
            $grupos         =   array();
            $asignaturas    =   array();
            $asignaciones   =   array();
            $profesores     =   array();
            $espaciost      =   array();
            $espaciosp      =   array();
            $horasc         =   array();
        }
    }
    //print_r($solucion);
    echo "metabounds";
    print_r(json_encode($metabounds));
    echo "bounds";
    print_r(json_encode($bounds));

});


// @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
// @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
// @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@

get('/test/bounds', function(){
    header('Content-Type: text/plain');
    $ps = Profesor::find('all');
    $labdays=   5;
    $hdef   =   2; //horario laboral por default de 9  2 y 4 a 7
    $impar  =   array(1,3,5,7,9);
    $par    =   array(2,4,6,8,10);
    $anio   =   2015;
    $periodo=   'A';
    $profesores     =   array();
    $espaciost      =   array();
    $espaciosp      =   array();
    $horasc         =   array();
    $duracion       =   array(1,2,3,4,5);
    $casignacion    =   array(); //para guardar asignaciones repetidas
    $bounds         =   array();
    $metabounds     =   array();
    $metaba         =   array();
    // BY HAND SEARCH OF GRUPO AND ASIGNATURA RETRIVING WITHOUT SEMESTRE FILTERING
//    $sentence   =   "select a.id as grupo, b.id as asignatura from asignatura b, grupo a where a.carrera_id = b.carrera_id and a.semestre = b.semestre and a.anio = $anio and a.periodo = '$periodo' order by a.carrera_id desc";
//    $gs    =   Asignatura::find_by_sql($sentence);
    // FLEXIBLE GRUPO AND ASIGNATURA RETRIVING (ACCORDING TO SEMESTER REGISTERED ASIGNMENTS, YEAR AND PERIOD)
//    $joins  =   'LEFT JOIN grupo a ON(asignatura.carrera_id = a.carrera_id and asignatura.semestre = a.semestre)';
//    $gs    =   Asignatura::find('all', array('joins' => $joins, 'select' => 'a.id as grupo,asignatura.id as asignatura' ,'conditions' => array('a.semestre in (?) and a.anio = ? and periodo = ?',$impar, $anio, $periodo), 'order' => 'a.carrera_id desc'));
    //  CUSTOM RETRIVING OF GRUPO AND ASIGNATURA
    $gs =  Asignacion::find('all', array('select' => 'grupo_id as grupo, asignatura_id as asignatura, profesor_id as profesor, gespaciot_id, gespaciop_id, id as asignacion', 'conditions' => array('anio = ? and periodo = ?', $anio, $periodo)));
    foreach ($gs as $as){
        if (!in_array($as->asignacion,$casignacion)){



        // CUSTOM RETRIVING MULTIASIGNACIONES

            $multi  =   Multiasignado::find("all",array('conditions'=>array('asignacion_id = ?',$as->asignacion)));
            if($multi){ // Multiasignado - varios arreglos
                $ma = Multiasignado::find("all",array('conditions'=>array('multiasignacion_id=?',$multi[0]->multiasignacion_id)));
                foreach ($ma as $m){ // para cada multiasignacion
                    $a      =   Asignacion::find("all", array('conditions'=>array('id = ?', $m->asignacion_id)));
                    $espe   =   Asignatura::find("all", array('select'=>'especialidad_id as es', 'conditions'=>array('id = ?',$a->asignatura_id)));
                    array_push($profesores, intval($a[0]->profesor_id));
                    array_push($metaba, array("assignment"=>intval($a[0]->id), "lecture"=>intval($a[0]->asignatura_id), "group"=>intval($a[0]->grupo_id), "teacher"=>intval($a[0]->profesor_id), "specialty"=>intval($espe[0]->es),"sameHourTP"=>intval(0),"fixedHours"=>intval(0)));
                    array_push($casignacion,    $m->asignacion_id);
                }

            }else{ // NO multiasignado un arreglo para multiasignacion
                $espe  =   Asignatura::find("all", array('select'=>'especialidad_id as es', 'conditions'=>array('id = ?',$as->asignatura)));
                array_push($profesores, intval($as->profesor));
                // FALTA CALCULAR LA MISMA HORA PARA TEORIA Y PRACTICA, ASI COMO LAS HORAS FIXEADAS (para cada atributo, 1->si, 0->no)
                array_push($metaba,array("assignment"=>intval($as->asignacion),"lecture"=>intval($as->asignatura),"group"=>intval($as->grupo),"teacher"=>intval($as->profesor),"specialty"=>intval($espe[0]->es),"sameHourTP"=>intval(0),"fixedHours"=>intval(0)));
                array_push($casignacion,    $grupo->asignacion_id);
            }




            // ASIGNANDO VARIABLES DE BOUNDS

            //horas y espacios para cada asignatura
            $horas = array();
            $espacios = array();
            // Para una asignatura
            if(count($metaba) == 1){
                array_push($horas, Asignatura::find("all", array('select'=>'gespaciop_id, gespaciot_id, hxs_teoria as ht, hxs_practica as hp', 'conditions'=>array('id = ?',$metaba[0]['lecture']))));
                array_push($espacios, Asignacion::find("all", array('select'=>'gespaciot_id, gespaciop_id', 'conditions'=>array('asignatura_id = ?',$metaba[0]['assignment']))));
            // Para varias asignaturas
            }else{
                foreach($metaba as $ma){
                    array_push($horas, Asignatura::find("all", array('select'=>'hxs_teoria as ht, hxs_practica as hp', 'conditions'=>array('id = ?', $ma['lecture']))));
                    array_push($espacios, Asignacion::find("all", array('select'=>'gespaciot_id, gespaciop_id', 'conditions'=>array('asignatura_id = ?',$ma['assignment']))));
                 }
            }
            //print($metaba[0]['lecture']);
            //print_r($horas);
            //print_r($espacios);

            // CUSTOM RETRIVING OF ESPACIOS OF TEORIA Y PRACTICA        
            $espacios_teoria    = NULL;
            $espacios_practica  = NULL;
            

            if ($as->gespaciot_id != NULL and $horas[0]->ht != 0){
 //           print($as->gespaciot_id);
                $espacios_teoria    =   Gespaciomiembro::find("all", array('select'=>'espacio_id as espacios_teoria', 'conditions'=>array('gespacio_id = ?',$as->gespaciot_id)));
//            print_r($espacios_teoria);
            }else{
                // Cuando no hay definido un espacio para teoria, pero se necesita, se toman en cuenta todas las aulas de licenciatura disponibles.
                $espacios_teoria    =   Gespaciomiembro::find("all", array('select'=>'espacio_id as espacios_teoria', 'conditions'=>array('gespacio_id = 101')));
            }
            if ($as->gespaciop_id != NULL and $horas[0]->hp != 0){
                $espacios_practica   =   Gespaciomiembro::find("all",array('select'=>'espacio_id as espacios_practica', 'conditions'=>array('gespacio_id = ?',$as->gespaciop_id)));
            }else if( $horas[0]->hp != 0){
                // cuando no hay definido un espacio para practica, pero se necesita, se toman en cuenta todas las salas de licenciatura disponibles
                $espacios_practica   =   Gespaciomiembro::find("all",array('select'=>'espacio_id as espacios_practica', 'conditions'=>array('gespacio_id = 100')));
            }
            //print_r($as->to_json());
            //print_r($horas[0]->to_json());
            if ($espacios_teoria != NULL) {
                foreach($espacios_teoria as $et){
                    array_push($espaciost,intval($et->espacios_teoria));
                }
            }
            if ($espacios_practica != NULL){
                foreach($espacios_practica as $ep){
                    array_push($espaciosp,intval($ep->espacios_practica));
                }   
            }
        /*
        echo "asignacion $as->asignacion \r";
        print("teoria");
        print_r($espaciost);
        print("practica");
        print_r($espaciosp);
        */
            // CUSTOM RETRIVING OF HOURS BOUNDS from Profesor table
            foreach($profesores as $p){
                $hora_profesor = Profesor::find("all",array('select'=>'horariolaboral_id, horasnodisponibles', "conditions"=>array('id =?',$p)));
                //Asignando horario laboral default a los profesores que no tienen horario laboral asignado
                if($hora_profesor[0]->horariolaboral_id == NULL){
                    $hora_profesor[0]->horariolaboral_id = $hdef;
                }
                $horario_laboral = HorarioLaboral::find("all",array('select'=>'horas', 'conditions'=>array('id = ?',$hora_profesor[0]->horariolaboral_id)));
                $horas  =   explode(',',$horario_laboral[0]->horas);
               // restando horas no disponibles a horariolaboral
                if($hora_profesor[0]->horasnodisponibles != NULL){
                    //                print_r($hora_profesor[0]->horasnodisponibles);
                    $hnd    =   explode(',',$hora_profesor[0]->horasnodisponibles);
                    //asignando bounds de horario laboral con horas no disponibles
                    foreach($horas as $h){
                        if(!in_array($h, $hnd) and !in_array($h, $horasc)){
                            array_push($horasc, intval($h));
                        }
                    }
                }else{
                    // asignando bounds de horario laboral
                    foreach($horas as $h){
                        if(!in_array($h, $horasc)){
                            array_push($horasc, intval($h));
                        }
                    }
                }       
            }
            //print_r($horasc);



        //ASIGNAR BOUNDS A ESA PARTE DEL VECTOR SOLUCION
            array_push($metabounds,$metaba);
            array_push($bounds,$espaciost);
            array_push($bounds,$espaciosp);
            for($day = 1; $day <= $labdays*2; $day++){
                array_push($bounds, $horasc);
                array_push($bounds, $duracion);
            }

        //LIBERAR MEMORIA
            $metaba         =   array();
            $profesores     =   array();
            $espaciost      =   array();
            $espaciosp      =   array();
            $horasc         =   array();
        }
    }
    $arreglo    =   array("metabounds"=>$metabounds, "bounds"=>$bounds);
    //print_r($solucion);
//    echo "arreglo";
    print_r(json_encode($arreglo));

/*        
    foreach ($ps as $p){
        //print_r($p->to_json());
    }

    print_r(json_encode(
        array(
            array(1,4, 6, 7, 8),
            array(4),
            array(3,5)
        )));
    json("Testeando el metodo GET");
*/

});


get('/test/methods', function(){
    $p = Profesor::find('all');
    print_r($p);
    json("Testeando el metodo GET");

});

post('/test/methods', function(){ 
    json("Testeando el metodo POST");

});

put('/test/methods', function(){ 
    json("Testeando el metodo PUT");

});

delete('/test/methods', function(){ 
    json("Testeando el metodo DELETE");

});

get('/test/charset', function(){
    echo "Default charset: ".ini_get('default_charset');
});

get('/test/afunctions', function(){ 
    $a = array(
        'f1' => function($a){
            return $a;
        });
    echo $a['f1']("Que pedo");
    print_r($a);

});


get('/test/domain', function(){
    header('Content-Type: text/plain');
    $in  = Instituto::find(4);
    print_r($in);
    echo "-------------------------------------------";

});


filter('asignacion', function ($asg) {
    try{
        $asignacion = Asignacion::find($asg);
        stash('asignacion', $asignacion);
    }catch(Exception $e){
        show_error('No se pudo encontrar la asignacion con ID: '.
                    strval($asg) ,$e);
    }

});

get('/test/horarios', function(){
    $asignaciones = Asignacion::find('all');
    $asignacion   = $asignaciones[0];
    if (!is_null($asignacion)){
        header('Location: '.site_url().'?/test/horarios/'.$asignacion->id);
    }else{
        echo "No hay asignaciones.";
    }
});

function ctrl_asignacion($post = false){
    $msg = '';
    if ($post){
        $op = $_POST['operacion'];
        if ($op == 'borrar'){
            // Borrar

        }elseif ($_POST['operacion'] == 'crear'){
            // PARSEAR MINI LANG PARA AGREGAR HORARIOS RAPIDAMENTE






            $msg = 'AGREGAR!!!';
        }else{  // Seleccionando nueva asignacion
            header('Location: '.site_url().
                '?/test/horarios/'.$_POST['asignacion']);
            return null;
        }
    
    }
    $asignacion = stash('asignacion');
    $asignaciones = Asignacion::find('all');
    $horarios     = $asignacion->asignacionhorarios;
    render('test/horarios', array(
        'asignaciones'  => $asignaciones,
        'asignacion'    => $asignacion,
        'horarios'      => $horarios,
        'msg'           => $msg));
}

get('/test/horarios/:asignacion', function(){
    ctrl_asignacion();
});

/**
  * Se requiere ver las asignaciones y multiasignaciones.
  * Para la asignacion correcta se requiere
 */
post('/test/horarios/:asignacion', function(){
    ctrl_asignacion(true);
});

get('/test/reparar', function(){
    echo '<!DOCTYPE html>
        <html lang="en">
          <head>
              <meta http-equiv="content-type" content="text/html; charset=UTF-8">
                  <meta charset="utf-8">
            </head><body>';

    $clase = 'Multiasignacion';
    $attr  = 'nombre';


    $objs = Multiasignacion::find('all');

    echo "Á É Í Ó Ú | á é í ó ú | Ñ ñ | <br/><br/>";

    foreach ($objs as $o){

        $valor = limpiar_raros($o->$attr);
        if ($o->$attr != $valor){
            echo $o->$attr."<br/>-&gt; ".$valor."<br/><br/>";

            $o->$attr = $valor;
            $o->save();
        }else{
            echo $o->$attr."<br/><br/>";
        }
    }

    echo "<h2>Limpiado!</h2>";
         
         
    //print_r($objs);


    echo '</body></html>';

});


get('/test/carreras', function(){
    $cas = Carrera::find('all');
    foreach ($cas as $ca){
        echo dh($ca->nombre)."<br/>\n";
    }
    $objs = Profesor::find('all');
    foreach ($objs as $ob){
        echo dh($ob->_name)."<br/>\n";
    }

});

?>
