<h3>Horarios</h3>

<?php
foreach ($horarios as $horario){
  $url = hurl($horario->id);
  echo "<a target='_black' href='$url'>$horario->nombre</a><br/>";
}
?>


