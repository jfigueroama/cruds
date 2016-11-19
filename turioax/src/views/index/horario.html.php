<fieldset>
<table class="horarios">
    <tr>
        <th>&nbsp;</th>
        <th><label>Lunes</label></th>
        <th><label>Martes</label></th>
        <th><label>Mi&eacute;rcoles</label></th>
        <th><label>Jueves</label></th>
        <th><label>Viernes</label></th>
    </tr>

<?php
// PARAMETROS: $tabla

/*
echo "<pre>";
print_r($tabla);
echo "</pre>";
 */

$dias  = array('L', 'M', 'X', 'J', 'V');
$horas = array(
    8  => '8:00 - 9:00',
    9  => '9:00 - 10:00',
    10 => '10:00 - 11:00',
    11 => '11:00 - 12:00',
    12 => '12:00 - 13:00',
    13 => '13:00 - 14:00',
    16 => '16:00 - 17:00',
    17 => '17:00 - 18:00',
    18 => '18:00 - 19:00'
);

foreach (array_keys($horas) as $hora){
    echo '<tr>';
    echo "<td id='horas'><div>{$horas[$hora]} Hrs.</div></td>";

    foreach ($dias as $dia){
        $horario = $tabla[$hora][$dia];
        if (! is_null($horario)){
            $horario->asignacion; // Para que se cargue el objeto.
            if ($horario->proposito == Horario::$PROPOSITO_CLASE){
                echo '<td style="text-align: center;'.'
                            background-color: cyan;">'.
                        '<a href="" class="event">';
                echo h($horario->asignacion->asignatura->nombre);
                echo '</a></td>';
            }else{
                echo '<td style="background-color: yellow; '.
                        'text-align: center;">Asesorias</td>';
            }
        }else{
            echo '<td></td>';
        }
    }
    echo "</tr>\n";
}
 
?>

</tbody></table></fieldset>
