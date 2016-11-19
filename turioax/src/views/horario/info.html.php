<?php

echo "Horario: <br/>";
echo "$horario->id ({$horario->multihorario->_name})  $horario->nombre";


$has = $horario->horarioasignaciones;

echo "<br/>";
foreach ($has as $ha){
  echo "$ha->asignacion_id $ha->multiasignacion_id: $ha->espaciot_id, $ha->espaciop_id <br/>";

  $hcs = $ha->horarioclases;
  foreach ($hcs as $hc){
    echo " - - - - $hc->tipo $hc->dia $hc->hora $hc->duracion <br/>";
  }
}

?>



<br/><br/>
