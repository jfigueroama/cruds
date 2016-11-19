<pre>
<?php

 // print_r($datos);

?>
</pre>

<div class="contenedorPrincipal">
<?php

foreach ($datos as $carrera => $semestres){
  foreach ($semestres as $semestre => $grupos){
    foreach ($grupos as $grupodata){
      $grupo = $grupodata['grupo'];
      $datos = $grupodata['datos'];
      $clases = $grupodata['clases'];

?>
			<div id="contenedor-info-grupoYAnio">
      <div id="grupo"><h3>Grupo: <?= $grupo->codigo ?></h3></div>
      <div id="year"><h3><?= $grupo->anio?>-<?= $grupo->periodo ?></h3></div>
			</div>
			poner icono de practica donde haya practica y hacer que se muestre una nubesita con info del espacio de trabajo
			<table class="table table-bordered table-striped" id="tabla-horario">
				<thead>
					<tr>
						<th class="hora">HORARIO</th>
						<th>lunes</th>
						<th>martes</th>
						<th>miercoles</th>
						<th>jueves</th>
						<th>viernes</th>
					</tr>
				</thead>
				<tbody>
					<tr>
            <td>8:00-9:00</td>
            <?= fila_hora($clases['h8']) ?>
					</tr>
					<tr>
						<td class="hora">9:00-10:00</td>
            <?= fila_hora($clases['h9']) ?>
					</tr>
					<tr>
						<td class="hora">10:00-11:00</td>
            <?= fila_hora($clases['h10']) ?>
					</tr>
					<tr>
            <td class="hora">11:00-12:00</td>
            <?= fila_hora($clases['h11']) ?>
					</tr>
					<tr>
            <td class="hora">12:00-13:00</td>
            <?= fila_hora($clases['h12']) ?>
					</tr>
					<tr>
            <td class="hora">13:00-14:00</td>
            <?= fila_hora($clases['h13']) ?>
					</tr>
					<tr>
            <td class="hora">14:00-15:00</td>
            <td colspan='5'></td>
					</tr>
					<tr>
            <td class="hora">15:00-16:00</td>
            <td colspan='5'></td>
					</tr>
					<tr>
            <td class="hora">16:00-17:00</td>
            <?= fila_hora($clases['h16']) ?>
					</tr>
					<tr>
            <td class="hora">17:00-18:00</td>
            <?= fila_hora($clases['h7']) ?>
					</tr>
					<tr>
						<td class="hora">18:00-19:00</td>
            <?= fila_hora($clases['h18']) ?>
					</tr>
				</tbody>
			</table>
			<br>
			<table class="table table-bordered table-striped">
				<thead>
					<tr>
						<th>Materia</th>
            <th><img src="<?=site_url()?>public/images/teoria.png"></th>
						<th><img src="<?=site_url()?>public/images//practica.png"></th>
						<th>Profesor</th>
					</tr>
				</thead>
        <tbody>
<?php
          
      foreach ($datos as $fila){
        $asignatura = $fila['asignatura'];
        $nc = nombre_corto($asignatura->nombre);
        $espaciot   = $fila['espaciot'];
        $espaciop   = $fila['espaciop'];
        $profesor   = $fila['profesor'];
        $np         = "{$profesor->grado} "
                        ." $profesor->nombres $profesor->apellidos";

        echo "<tr>";
        echo "<td>{$asignatura->nombre} ({$nc})</td>";
        if ($espaciot)
          echo "<td>{$espaciot->nombre}</td>";
        else
          echo "<td></td>";
        if ($espaciop)
          echo "<td>{$espaciop->nombre}</td>";
        else
          echo "<td></td>";
        echo "<td><i>{$np}</i></td>";
        echo "</tr>";

      }
?>
<!-- 
        <tr>
						<td>ADMINISTRACIÓN Y DIRECCIÓN DE EMPRESAS</td>
						<td>AULA 34</td>
						<td></td>
						<td><i>M.A. MA. GUADALUPE NORIEGA GOMEZ</i></td>
          </tr>
-->
				</tbody>
			</table>

<?php
    } // grupos
  } // semestres
} // carresas
?>
</div>
