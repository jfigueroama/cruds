<?php
/* Parametros:
 *
 * $clase
 * $obj
 * $tabla
 * $forms
 */

$jquery_date_format = 'dd/mm/yy';


echo "<div style='color: blue;'>".stash('msg').'</div>';
if (stash('error')){
    echo "<span style='color:red;'>".stash('error').'</span>';
}
?>
<div class="col-md-6" style='width: 70%;'>
    <div class="col-md-6 col-md-offset-12" style='width: auto;'>
        <div data-alerts="alerts" data-titles="{&quot;success&quot;: &quot;&lt;em&gt;Registro Agregado!&lt;/em&gt;&quot;}" data-ids="myid" data-fade="10000"> </div>
    </div>
    <hr>

    <div class="panel panel-default" style='width: auto;'>
        <div class="panel-heading"><h3>Edici&oacute;n | <?php echo isset($clase::$fname) ? $clase::$fname : $clase; ?></h3>
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
echo "<input type='hidden' name='_form_name' value='main' />";
$fields = $forms['main'];
$eurl = site_url().MR."/domain/$clase/edit/".$obj->id;
$durl = site_url().MR."/domain/$clase/delete/".$obj->id;
echo "<h4>Identificador</h4>
    <div class='controls'>
    <a href='$eurl'>$obj->id</a> -- 
    <a href='$durl' style='color:red; font-size: smaller;'>Eliminar</a>
    </div>";

foreach ($fields as $field){
    $control = create_control($field);
    $label = create_label($field);

    echo "<h4>$label</h4>";
    echo "<div class='controls'>$control</div>";
}
echo "<div class='controls' style='text-align: center;'><br/>
        <button type='submit' class='btn btn-primary'>Guardar</button></div>";
?>
                         </form>

<?php
unset($forms['main']);
foreach ($forms as $fname => $form){
    echo "<hr/>";
    $tit = $form['fname'] ? $form['fname'] : ucfirst($fname);

    if ($form['type'] == 'crud'){
        echo "<div style='background-color: #E0F2FF; padding: 4px;'>
            <div style='background-color: #B7E1FF; padding: 4px;'>
            <h4>".$tit.'</h4>';

        echo '<form method=\'post\'>';
        echo "<input type='hidden' name='class' value='".$form['class'].'\'/>';
        echo "<input type='hidden' name='_form_name' value='remover' />";
        echo '<div class=\'controls\'>'.create_control($form['remove']).'</div>';
        echo "<div class='controls' style='text-align: center;'><br/>
            <button type='submit' class='btn btn-primary'>Remover</button></div></form></div>";

        echo '<div style="background-color: #B7E1FF; padding:4px; width: auto;"><form method=\'post\'>';
        echo "<input type='hidden' name='class' value='".$form['class'].'\'/>';
        echo "<input type='hidden' name='_form_name' value='agregar' />";

        foreach ($form['add'] as $field){
            $control = create_control($field);
            $label = create_label($field);

            if (! (isset($field->meta['hidden']) && $field->meta['hidden'])){
                $control = create_control($field);
                $label = create_label($field);

                echo "<h5>$label</h5>";
                echo "<div class='controls'>$control</div>";
            }else{
                echo $control;
            }
        }
        echo "<div class='controls' style='text-align: center;'><br/>
            <button type='submit' class='btn btn-primary'>Agregar</button></div>            </form></div></div>";
    }elseif ($form['type'] == 'links'){
        echo "<div style='background-color: #E0F2FF; padding: 4px;'>
            <div style='background-color: #B7E1FF; padding: 4px;'>
            <h4>".$tit.'</h4>';

        $attrf  = $fname;
        $clasef = $form['class'];
        
        echo "<ul>";
        foreach ($obj->$attrf as $objx){
            $urlx = site_url().'?/domain/'.$clasef.'/edit/'.$objx->id;
            echo "<li><a href=\"$urlx\">$objx->_name</a></li>";
        }
        echo "</ul>";
        echo "</div></div>";
    }else{
        throw new Exception('Tipo de formulario inv&aacute;lido: '.
                            $form->type);
    }
}
?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php

?>
