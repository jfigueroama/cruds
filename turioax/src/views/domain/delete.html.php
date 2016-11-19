<?php
/*
 *
 *
 */

$lurl = site_url().MR."/domain/$clase";

if ($eliminar != 0){
    // eliminado
    if (!stash('error')){


?>

<h4>Registro "<?php echo  $obj->_name;  ?>" eliminada.</h4>

<div style='text-align: left'>
<a href='<?php echo $lurl; ?>'>Volver al listado de registros.</a>
</div>

<?php
    }else{
        if (1 == preg_match('/Integrity constraint violation/',
                            stash('error')) && !DEBUG){
            stash('error', 'La instancia es una dependencia 
                            de otras instancias.');
        }
        echo "<span style='color: red;'>No se pudo borrar las instancia seleccionada: <br/><b>".stash('error').'</b></span><br/>';
        echo '<div style=\'text-align: left;\'><b>'.$clase.": ".$obj->_name.'</b></div><br/>';
        $vurl = site_url().MR."/domain/$clase";
        echo "<a href='$vurl'>Volver al &iacute;ndice.</a><br/>";

    }
}else{
    // no eliminado
?>

<form method='POST'>
<h4>Est&aacute; seguro que desea <i>Eliminar</i> el registro de la entidad 
<strong><?php echo $clase; ?>
</h4><br>

    &quot;<strong><?php echo  $obj->_name;  ?></strong>&quot;?<br/><br/>
<div style='text-align: left;'>
    Confirmaci&oacute;n <input type='checkbox' name='eliminar' value='1' />
<input type='submit' value='Eliminar' />
</div>
</form>


<?php
}
?>
