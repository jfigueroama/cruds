<?php
/* Parametros:
 *
 * $clase
 * $obj
 * $tabla
 * $attr_base
 * $relation
 */

//echo "<div style='color: blue;'>".stash('msg').'</div>';
echo "<table>";
//////////////////////////////////////////////////

$attrs = $tabla->columns;
// TODO aplicar filtros de seguridad, etc.



// ID  ---------------------------------------
$eurl = site_url().MR."/domain/$clase/edit/".$obj->id;
echo "<tr>
    <td><label for='id'>Identificador:</td>
    <td><a href='$eurl'>$obj->id</a></td>
    </tr>";

// --------------------------------
// ATRIBUTOS SIMPLES
foreach ($attrs as $attr => $meta){
    if (preg_match("/.*_id$/", $attr) ||
        $attr == $tabla->pk[0]) // si es igual a *_id o a id
        continue;

    $valor = h($obj->$attr);

    // Multivalores
    $nvalues = $attr.'_values';   // dia_values, proposito_values, etc.
    if (isset($clase::$$nvalues)){
        $valores = $clase::$$nvalues;
        $valor = $valores[$valor];
    }

    echo '<tr>';
    echo "<td ><label for='$attr'>".ucfirst($attr)."</label></td>";

    echo "<td >$valor</td>";

    echo "</tr>\n";
}

// ------------------------
// RELACIONES

$rbt = isset($clase::$belongs_to) // BELONGS_TO
    ? $clase::$belongs_to : array();
foreach ($rbt as $bt){
    $attr   = $bt[0];
    $fk     = get_relation_fk($attr, $tabla);
    $cn     = get_relation_class($attr, $tabla);
    $fkmeta = $attrs[$fk];
    
    $ovalores = $cn::find('all');
    $valor  = h($obj->$attr->nombre);  // Nombre

    echo '<tr>';
    echo "<td ><label for='$fk'>".ucfirst($attr)."</label>:</td>
          <td >$valor</td></tr>";
}

////////////////
echo '</table>';

//--------------

// HAS_MANY FORM
echo '<table><tr>';
echo '<td>'.ucfirst($attr_base).'</td>';
echo '<td>';

echo '</td>';

echo '</tr></table>';

// HAS_MANY links
echo "<hr/>";
$hms = isset($clase::$has_many) // BELONGS_TO
    ? $clase::$has_many : array();

foreach ($hms as $hm){
    $nattr = $hm[0];
    if ($attr_base == $nattr)
        continue;

    $fk     = get_relation_fk($nattr, $tabla);
    $cn     = get_relation_class($nattr, $tabla);

    $url = site_url().MR.DS."domain/$clase/edit-relation/$obj->id/$nattr";
    echo "<a href='$url'>".ucfirst($nattr)."</a>&nbsp;
    <a href='$url'>$url</a><br/>";

}

// ----------------------------

/*
echo '<pre>';
echo '--------------------------------'."\n";

print_r($tabla);
echo '</pre>';
*/
?>
