<?php


get('/horarios', function(){ 
    redirect('/horarios/index');

});

get('/horarios/index', function(){ 
  $horarios = Horario::find('all');


  render('horarios'.DS.'index',
  array('horarios' => $horarios));


        //render('index'.DS.'proyectos',
    //    array('proyectos' => $proyectos), false);

});



get('/horarios/test', function(){

  $url = site_url().'?/scheduling/data';
  $script = "
    // TODO hacer un unfolding de multiasignaciones para pintar. tambien habria
    // que copiar los horarios a cada asignacion. Bueno, la multiasignacion es
    // muy enfadosa de pintar si hay dos profes o dos grupos o ... chale :-s
    // 
//    var scheduling_data = null;

    
    window.addEventListener('load', function(){
 //     m.request({method: 'GET', url: '$url'}).then(function(data){
 //       scheduling_data = data;
 //       nuevo_horario();
 //       document.getElementById('btn_nuevo_horario').disabled = false;    
 //     });
      nuevo_horario();
      document.getElementById('btn_nuevo_horario').disabled = false;
    });";

  render('horarios'.DS.'test', array(
    'jss' => array('underscore.js', 'mithril.js',
                   'mhc/paletas.js', 'mhc/data.js', 'mhc/core.js'),
    'scripts' => array($script)));
});

get('/horarios/mi1', function(){

  $url = site_url().'?/scheduling/data';

  render('horarios'.DS.'mi1', array(
    'anio' => 2015,
    'jss' => array('lodash.min.js', 'transducers.min.js',
                   'mhc/paletas.js', 'mhc/data.js', 'mhc/core.js'),
    'scripts' => array($script)));
});

?>
