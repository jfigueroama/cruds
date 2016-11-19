<!-- PARAMS: $profesores, $url_base, $anio, $periodo -->
<ul>
<?php

foreach ($profesores as $p){
    $url = $url_base . $p->id . '/' . $anio . '/' . $periodo;
    echo "<li><a href='$url'>".h($p->nombreFormal())."</a></li>";
}

?>
</ul>
