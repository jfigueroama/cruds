<?php
if (count($proyectos) > 0){
    echo '<ul>';
    foreach ($proyectos as $proyecto){
        $profesores = implode(', ', array_map(function($pr){
            return $pr->nombreFormal();
        }, $proyecto->profesores()));
        $alumnos    = implode(', ', array_map(function($pr){
            return $pr->nombreCompleto();
        }, $proyecto->alumnos()));
?>
    <li><b><?= h($proyecto->nombre) ?></b></li>
        <span><?= h($proyecto->resumen) ?></span><br/><br/>
        <span>Fondos: <?= h($proyecto->apoyo->nombre) ?></span><br/>
        <span>Participantes: <?= h($profesores) ?></span><br/>
        <span>Alumnos: <?= h($alumnos) ?></span><br/>
        <span>Iniciado el: <?= $proyecto->iniciado_el ?></span>
        <?php
        if (! is_null($proyecto->terminado_el)){
            echo '<br/><span>Terminado el: '.$proyecto->terminado_el.
                      '</span>';
        }
        ?>
<?php
    }
    echo '</ul>';
}else{
    echo '<i>No se encontraron proyectos.</i>';
}
?>
