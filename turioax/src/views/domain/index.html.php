<?php

echo $htmlfiltros;
/*
 *
 * PARAMETROS:
 * - $clase
 * - $objs
 * - $campos (op)
 * - $urlexcepto
 */
 
echo "<h3>Registros | $clase</h3>";
$curl = site_url().MR."/domain/$clase/new"; //TODO Pasar a route, sacar de aqui
echo "<a href='$curl' class='btn btn-primary'>Crear nuevo registro</a><br/><br/>";
echo '<div style="overflow: auto;min-width: 20%; max-width: 800px; height: 500px;">';


//echo '<table class="table table-hover" style="width: auto;">';

echo '<ol style="font-size: 12px">';

// Ordena por _name de forma ascendente.
usort($objs, function($o1, $o2){
    return $o1->_name > $o2->_name;
});

foreach ($objs as $obj){
    $turl  = site_url().MR."/domain/$clase/edit/$obj->id";
    $trurl = site_url().MR."/domain/$clase/delete/$obj->id";
    $tnom = $obj->_name;
?>
                     <!--
                    <tr>
                        <th>Nombre</th>
                        <th>Codigo</th>
                        <th>Capacidad</th>
                        <th>Opciones</th>
                    </tr>
                    -->

                           <!-- <tr>
                           <td style='border-style: none;'> -->

<li style='margin-bottom: 1px;'><a href="<?php echo $turl;?>" name="editar" ><?php echo $tnom; ?></a>

<!--                            <button name="eliminar" type="button" class="btn btn-danger">Eliminar</button></td> -->

                           <!-- <td style='border-style: none;'> -->
 | <a href="<?php echo $trurl;?>" style='color:red'>Eliminar</a> <br/></li>
                           <!-- </tr> -->
<?php
}
echo '</ol>';
echo '</div>';
//echo '</table>';

/*
echo '<table border="1px">';
echo '<th></th>'; // Para los enlaces
foreach ($attrs as $attr => $meta){
    $url = $urlexcepto.$attr;

    //echo "<th><a href='$url' title='Remover'>".ucfirst($attr).'</a></th>';
    echo "<th>".ucfirst($attr).'</th>';
}

// Las relaciones no valen para orden y no valen para filtros.
if (isset($clase::$belongs_to)){
    foreach($clase::$belongs_to as $bt){
        $nm = ucfirst($bt[0]);
        $pk = $clase::table()->get_relationship($bt[0])->foreign_key[0];

        $url = $urlexcepto.$attr;
        echo "<th>".ucfirst($nm).'</th>';
    }
}

echo '</tr>';

// --------------------------------

foreach ($objs as $obj){
    echo '<tr>';

    $eurl = site_url().MR."/domain/$clase/edit/".$obj->id;
    $vurl = site_url().MR."/domain/$clase/view/".$obj->id;
    $lurl = site_url().MR."/domain/$clase/delete/".$obj->id;
    echo "<td>
    <a href='$vurl' title='Ver'>V</a>|<a href='$eurl' title='Editar'>U</a>|<a href='$lurl' title='Eliminar'>D</a>
    </td>";

    foreach ($attrs as $attr => $meta){
        if (!is_object($obj->$attr))    // No es una relacion?
            $valor = h($obj->$attr);
        else
            $valor = h($obj->$attr->nombre); // Nombre de la relacion
        // Multivalores
        $nvalues = $attr.'_values';   // dia_values, proposito_values, etc.
        if (isset($clase::$$nvalues)){
            $valores = $clase::$$nvalues;
            $valor = $valores[$valor];
        }

        echo "<td>$valor</td>";
    }
    if (isset($clase::$belongs_to)){
        foreach($clase::$belongs_to as $bt){
            $nm = ucfirst($bt[0]);
            $pk = $clase::table()->get_relationship($bt[0])->foreign_key[0];
            $ob = $obj->$bt[0];
            if ($ob)
                $valor = h($ob->nombre);
            else
                $valor = "";
            echo "<td>$valor</td>";
        }
    }


    echo '</tr>';
}



echo '</table>';
echo '
<div id="confirmar-borrar" title="¿Eliminar?">
<!-- 
<p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>These items will be permanently deleted and cannot be recovered. Are you sure?</p> -->
</div>';
 */
?>
