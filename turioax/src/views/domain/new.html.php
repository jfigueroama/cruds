<?php
/* Parametros:
 *
 * $clase
 * $tabla
 * $newid
 * $datos
 */

$jquery_date_format = 'dd/mm/yy';


if ($newid){
    $url = site_url().MR.'/domain/'.$clase."/edit/$newid";
    echo "<div style='color: blue;'>".stash('msg');
    echo " <a href='$url'>Editar</a>";
    echo '</div>';

}

?>
<div class="col-md-6">
    <div class="col-md-6 col-md-offset-12">
        <div data-alerts="alerts" data-titles="{&quot;success&quot;: &quot;&lt;em&gt;Registro Agregado!&lt;/em&gt;&quot;}" data-ids="myid" data-fade="10000"> </div>
    </div>
    <hr>

    <div class="panel panel-default">
        <div class="panel-heading"><h3>Registro | <?php echo isset($clase::$fname) ? $clase::$fname : $clase; ?></h3>
<?php
$vurl = site_url().MR."/domain/$clase";
echo "<a href='$vurl'>Volver al &iacute;ndice.</a><br/>";
?>

</div>

        <div class="panel-body">
            <div class="row">
                <div class="col-md-10">
                    <div class="control-group">
<?php



echo "<form name='main' method='POST' accept-charset='uft-8' action=''>";

//////////////////////////////////////////////////

if (stash('error')){
    echo "<div class='controls' style='color:red;'>".stash('error').'</div>';
}

foreach ($fields as $field){
    $control = create_control($field);
    $label = create_label($field);

    echo "<h4>$label</h4>";
    echo "<div class='controls'>$control</div>";
}
echo "<div class='controls' style='text-align: center;'><br/>
        <button type='submit' name='crear' value='Crear' class='btn btn-primary'>Guardar</button></div>";


////////////////
echo '</form>';


?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

