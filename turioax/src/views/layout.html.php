<?php

define('UI_PANEL_INFO_ACA', 0);
define('UI_PANEL_INFO_ADM', 1);
define('UI_PANEL_CATALOGOS', 2);

define('UI_PANEL_HORARIOS', 3);
define('UI_PANEL_ACTIVIDAD', 4);

$left_panel = UI_PANEL_INFO_ACA;
if (isset($clase)){
    $left_panel_clases = array(
        'Profesor' => UI_PANEL_INFO_ACA,
        'Asignatura' => UI_PANEL_INFO_ACA,
        'Asignacion' => UI_PANEL_INFO_ACA,
        'Multiasignacion' => UI_PANEL_INFO_ACA,
        'Especialidad' => UI_PANEL_INFO_ACA,

        'Grupo' => UI_PANEL_INFO_ADM,
        'Espacio' => UI_PANEL_INFO_ADM,
        'Personal' => UI_PANEL_INFO_ADM,

        'Instituto' => UI_PANEL_CATALOGOS,
        'Carrera' => UI_PANEL_CATALOGOS,
        'Cargo' => UI_PANEL_CATALOGOS,
        'Discapacidad' => UI_PANEL_CATALOGOS,
        'Gespacio' => UI_PANEL_CATALOGOS,
        'Recurso' => UI_PANEL_CATALOGOS,
        'Horariolaboral' => UI_PANEL_CATALOGOS
    );

    $left_panel = $left_panel_clases[$clase];
}else{
  // rutas externas a las del dominio.

  $qs = explode('/', $_SERVER['QUERY_STRING']);
  $bu = $qs[1]; // ignora la primera pues es vacio.

  if ($bu == 'restricciones'
    || $bu == 'horarios'){
    $left_panel = UI_PANEL_HORARIOS;
  }else if ($bu == 'actividad'){
    $left_panel = UI_PANEL_ACTIVIDAD;
  }

  unset($qs);
  unset($bu);
}


?><!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <title>Sistema de calendarizacion.</title>
    <meta name="generator" content="Bootply" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link href="<?php echo site_url(); ?>public/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo site_url(); ?>public/css/prettify.css" />
    <!--[if lt IE 9]>
      <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <link href="<?php echo site_url(); ?>public/css/styles.css" rel="stylesheet">
    <link href="<?php echo site_url(); ?>public/css/bootstrap-modal.css" rel="stylesheet">
    <link href="<?php echo site_url(); ?>public/css/bootstrap-modal-bs3patch.css" rel="stylesheet">

<!--   <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script> -->
    <!-- <script src="<?php echo site_url(); ?>public/js/jquery-2.1.1.js"></script> -->
    <script src="<?php echo site_url(); ?>public/js/jquery-1.9.1.js"></script>
    <script src="<?php echo site_url(); ?>public/js/prettify.js"></script>
    <script src="<?php echo site_url(); ?>public/js/jquery.bsAlerts.js"></script>
    <script src="<?php echo site_url(); ?>public/js/scripts.js"></script>
   <!--  <script src="<?php echo site_url(); ?>public/js/jquery.min.js"></script> -->
    

    <script src="<?php echo site_url(); ?>public/js/bootstrap.js"></script>

    <script src="<?php echo site_url(); ?>public/js/bootstrap.min.js"></script>
    <script src="<?php echo site_url(); ?>public/js/bootstrap-modalmanager.js"></script>
    <script src="<?php echo site_url(); ?>public/js/bootstrap-modal.js"></script>
    <script src="<?php echo site_url(); ?>public/js/interact.js"></script>
    <script src="<?php echo site_url(); ?>public/js/modernizr.js"></script>


    <link rel='stylesheet' href="<?php echo site_url(); ?>public/react-toolbox/roboto.css.local">
    <link rel='stylesheet' href="<?php echo site_url(); ?>public/react-toolbox/material-icons.css.local">
    <link rel='stylesheet' href="<?php echo site_url(); ?>public/react-toolbox/react-toolbox-bundle.css">
    <script src="<?php echo site_url(); ?>public/react-toolbox/react-toolbox-bundle.js"></script>
    <script src="<?php echo site_url(); ?>public/js/browser.min.js"></script>
    <script src="<?php echo site_url(); ?>public/js/lodash-4.13.1.min.js"></script>
    <link rel='stylesheet' href="<?php echo site_url(); ?>public/widgets/horarios.css">
    <script type='text/babel' src="<?php echo site_url(); ?>public/widgets/horarios.jsx"></script>



<?php
if (isset($jss)){
  if (!is_array($jss))
    $jss = array();

  foreach ($jss as $js){
    echo '    <script src="'.site_url().'public/js/'.$js.
                "\" type='text/javascript'></script>\n";
  }
}

if (isset($csss)){
  if (!is_array($csss))
    $csss = array();

  foreach ($csss as $css){
    echo '    <link rel="stylesheet" href="'.site_url().
                'public/css/'.$css."\">\n";
  }
}

if (isset($scripts)){
  if (!is_array($scripts))
    $scripts = array();

  foreach ($scripts as $script){
    echo '    <script type="text/javascript">'.$script."</script>\n";
  }
}
    ?>





    <script>
function crear() {
    var div = document.createElement('div');
    div.setAttribute("id","drop2");
    div.setAttribute("class","dropzone js-drop");
    //document.body.appendChild(div);
    document.getElementById('container1').appendChild(div);
}
</script>


  </head>

    <!-- Header -->

 <body>
    <div id="top-nav" cglass="navbar navbar-inverse navbar-static-top" style=''>
      <div class="container">
        <div class="navbar-header" style=''>
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="<?= site_url() ?>">Universidad Tecnológica de la Mixteca</a>
        </div>
        <div class="navbar-collapse collapse" >
          <ul class="nav navbar-nav navbar-right">

            <li class="dropdown">
              <a class="dropdown-toggle" role="button" data-toggle="dropdown" href="#"><i class="glyphicon glyphicon-user"></i> Admin <span class="caret"></span></a>
              <ul id="g-account-menu" class="dropdown-menu" role="menu">
                <li><a href="#">My Profile</a></li>
              </ul>
            </li>
            <li>
<?php
if (!is_logged()){
    echo "<a href='".site_url().'?/sesion/login'."'><i class=\"glyphicon glyphicon-lock\"></i> Login</a>";
}else{
    
    $cuser = current_user();
    echo "<a href='".site_url().'?/sesion/logout'."'>{$cuser['correo']}<br/><i class=\"glyphicon glyphicon-lock\"></i> Logout</a>";
     
}
     
?>
            </li>
          </ul>
        </div>
      </div><!-- /container -->
    </div>
<!-- /Header -->

<!-- Main -->
 <div class="container" style='width: 100%;'>
      <div class="col-md-2" style="height:100%" >
         <!-- Left column -->
         <a href="#"><strong><i class="glyphicon glyphicon-attach"></i> Menu</strong></a>

           <hr>

           <ul class="list-unstyled">
    <div class="panel-group" id="accordion">
      <div class="panel panel-primary">
        <div class="panel-heading">
          <h4 class="panel-title">
            <a data-toggle="collapse" data-parent="#accordion"
                href="#accordionOne">
               Información Academica
            </a>
          </h4>
        </div>
        <div id="accordionOne" class="panel-collapse collapse <?= UI_PANEL_INFO_ACA == $left_panel ? 'in' : '' ?> ">
          <div class="panel-body">
           <ul class="list-unstyled collapse in" id="userMenu">
               <li><a href="<?= site_url() ?>?/domain/Profesor"><i class="glyphicon glyphicon-user"></i> Profesores </a></li>
                 <li><a href="<?= site_url() ?>?/domain/Asignatura"><i class="glyphicon glyphicon-file"></i> Asignaturas</a></li>
                 <li><a href="<?= site_url() ?>?/domain/Asignacion"><i class="glyphicon glyphicon-file"></i> Asignaciones</a></li>
                 <li><a href="<?= site_url() ?>?/domain/Multiasignacion"><i class="glyphicon glyphicon-file"></i> Multi asignaciones</a></li>
                <li><a href="<?= site_url() ?>?/domain/Especialidad"><i class="glyphicon glyphicon-file"></i>Especialidades</a></li>
            </ul>
            
            </div>
        </div>
      </div>

      <div class="panel panel-primary">
        <div class="panel-heading">
          <h4 class="panel-title">
            <a data-toggle="collapse" data-parent="#accordion"
               href="#accordionTwo">
              Información Administrativa
            </a>
          </h4>
        </div>
        <div id="accordionTwo" class="panel-collapse collapse <?= UI_PANEL_INFO_ADM == $left_panel ? 'in' : '' ?>">
          <div class="panel-body">
             <ul class="list-unstyled collapse in" id="catalogosMenu">
                    <li><a href="<?= site_url() ?>?/domain/Grupo"><i class="glyphicon glyphicon-map-marker"></i> Grupos</a></li>     

                <li><a href="<?= site_url() ?>?/domain/Espacio"><i class="glyphicon glyphicon-calendar"></i>Espacios f&iacute;sicos</a></li>
               <li><a href="<?= site_url() ?>?/domain/Personal"><i class="glyphicon glyphicon-user"></i> Personal administrativo </a></li>   
                </ul>
            
      </div>
        </div>
      </div>
      
      <div class="panel panel-warning">
        <div class="panel-heading">
          <h4 class="panel-title">
            <a data-toggle="collapse" data-parent="#accordion" 
               href="#accordionThree">
              Catálogos
            </a>
          </h4>
        </div>
    
        <div id="accordionThree" class="panel-collapse collapse <?= UI_PANEL_CATALOGOS == $left_panel ? 'in' : '' ?>">
          <div class="panel-body">
           <li><a href="<?= site_url() ?>?/domain/Instituto"><i class="glyphicon glyphicon-calendar"></i> Institutos</a></li>
                        <li><a href="<?= site_url() ?>?/domain/Carrera"><i class="glyphicon glyphicon-file"></i>Carreras</a></li>
                 <li><a href="<?= site_url() ?>?/domain/Cargo"><i class="glyphicon glyphicon-file"></i> Cargos de Personal Administrativo</a></li>
                        <li><a href="<?= site_url() ?>?/domain/Discapacidad"><i class="glyphicon glyphicon-user"></i> Discapacidades</a></li>
                        <li><a href="<?= site_url() ?>?/domain/Gespacio"><i class="glyphicon glyphicon-map-marker"></i> Grupo de Espacios</a></li>
                 <li><a href="<?= site_url() ?>?/domain/Recurso"><i class="glyphicon glyphicon-file"></i> Recursos para los espacios f&iacute;sicos </a></li>
                 <li><a href="<?= site_url() ?>?/domain/Horariolaboral"><i class="glyphicon glyphicon-file"></i> Horarios Laborales</a></li>

            
      </div>
        </div>
      </div>

       <div class="panel panel-success">
        <div class="panel-heading">
          <h4 class="panel-title">
            <a data-toggle="collapse" data-parent="#accordion" 
               href="#accordionFour">
              Horarios y Restricciones
            </a>
          </h4>
        </div>
    
        <div id="accordionFour" class="panel-collapse collapse <?= UI_PANEL_HORARIOS == $left_panel ? 'in' : '' ?>">
          <div class="panel-body">
            <li><a href="<?= site_url() ?>?/restricciones/"><i class="glyphicon glyphicon-file"></i> Restricciones</a></li>
            <li><a href="<?= site_url() ?>?/horarios/"><i class="glyphicon glyphicon-file"></i> Horarios</a></li>

            
      </div>
        </div>
      </div>

      <div class="panel panel-danger">
        <div class="panel-heading">
          <h4 class="panel-title">
            <a data-toggle="collapse" data-parent="#accordion" 
               href="#accordionFive">
              Actividad Reciente <span class="badge">42</span>
            </a>
          </h4>
        </div>
    
        <div id="accordionFive" class="panel-collapse collapse <?= UI_PANEL_ACTIVIDAD == $left_panel ? 'in' : '' ?>">
          <div class="panel-body">
              <li><a href="<?= site_url() ?>?/actividad/"><i class="glyphicon glyphicon-file"></i> Eventos Recientes <span class="badge">8</span></a></li>   

            
      </div>
        </div>
      </div>

    </div>
           
           </ul>
              <!-- <hr>  -->
              
      </div>

    <br/>

<?php echo content(); ?>



<div style='position: absoulte; left: 50%; margin-bottom: 1px; float: left; text-align: center; bottom: 2px;'><hr/>Para cualquier duda o comentario enviar un correo a <a href='mailto: jfigueroa@mixteco.utm.mx'>jfigueroa@mixteco.utm.mx</a></div>
  </body>
</html>
