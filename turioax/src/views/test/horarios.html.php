
<h2> CRUD de Horarios para Asignaciones</h2>
<script type='text/javascript'>
    function seleccionar(aid){
        document.getElementById('fasignacion').submit();
    }
    function borrar(){
        document.getElementById('operacion').value = 'borrar';
        document.getElementById('faccion').submit();
    }
    function crear(){
        document.getElementById('operacion').value = 'crear';
        document.getElementById('faccion').submit();
    }
</script>
    <span style='color: blue'><?php echo $msg; ?></span><br/>

    <form id='fasignacion' method='POST' action='<?php echo site_url().'?/test/horarios/'.$asignacion->id; ?>'>
    <label for='asignacion'>Asignaci&oacute;n: </label>
    <select name='asignacion' id='asignacion' onchange='seleccionar(this.value);'>
<?php
    foreach ($asignaciones as $asg){
        if (isset($asignacion) && $asg->id == $asignacion->id)
            $sel = 'selected="selected"';
        else
            $sel = '';

        echo "<option value='$asg->id' $sel>".$asg->_name."</option>";

    }
?>
    </select>
</form>

    <form id='faccion' method='post' action='<?php echo site_url().'?/test/horarios/'.$asignacion->id; ?>'>

<div>
<?php
    foreach ($horarios as $h){
        echo $h->_name.'<br/>';
    }
?>
</div>
    <input type='hidden' id='operacion' name='operacion' value='' />
    <button value='Borrar' onclick='borrar();'>Borrar</button>
    <br/><br/>
    <hr/>
    <input type='text' size='30' name='horarios' value='' />
    <br/>
    <button value='Crear' onclick='crear();'>Crear</button>

</form>
