<style>


.constraint .value {
  width: 50px;
}
.constraint .name {
  width: 100px;
}

.c-off .value {
  color: gray;
}
.c-off .name {
  color: gray;
}

.c-good .value {
  color: darkgreen;
}
.c-good .name {
  color: darkgreen;
}

.c-bad .value {
  color: crimson;
}
.c-bad .name {
  color: crimson;
}

.c-disable .value {
  text-decoration: line-through;
}
.c-disable .name {
  text-decoration: line-through;
}

.c-enable .value {
  color: blue;
}
.c-enable .name {
  color: blue;
}


/* --------------- */

.collisions {
  color: red;
}
.collisions-0 {
  color: green;
}

.required-c {
  color: red;
}

.required-c-100{
  color: green;
}

.desirable-c {
  color: red;
}
.desirable-c-100 {
  color: green;
}


</style>
<label for='anio'>A&ntilde;o: </label>
<input type='text' size='4' value='<?= $anio ?>' id='anio'>
&nbsp;&nbsp;
<label for=''>Periodo: </label>
<select>
  <option value='A'>A</option>
  <option value='B'>B</option>
</select>
&nbsp;&nbsp;
<input type='button' value='Cargar datos' />

<br/><br/><hr/><br/><br/>
<span>Cargando Datos</span>
<img src='<?= site_url().'/public/images/loading.gif' ?>' alt='' width='25px' height='25px'>


<br/><br/><hr/><br/><br/>
<span>Iniciando B&uacute;squeda</span>
<img src='<?= site_url().'/public/images/loading.gif' ?>' alt='' width='25px' height='25px'>

<br/><br/><hr/><br/><br/>
<span>Buscando ...</span>
<img src='<?= site_url().'/public/images/search.gif' ?>' alt='' width='25px' height='25px'>
<input type='button' value='Obtener Horario' />
<br/>
<span>* Recuerde que mientras m&aacute;s tiempo lleve este proceso, mejor ser&aacute; el horario obtenido.</span>


<br/><br/><hr/><br/><br/>


EVALUACI&Oacute;N<br/><br/>
<span>A&ntilde;o <b><?= $anio ?></b>, Periodo <b>B</b></span><br/><br/>

<span class='required-c-100'>100%</span> <span> requerido, </span>
  <span class='desirable-c-100'>100%</span><span> deseable.</span><br/><br/>
&oacute;<br/><br/>
<span class='required-c-100'>100%</span> <span> requerido, </span>
  <span class='desirable-c'>50%</span><span> deseable.</span><br/><br/>
&oacute;<br/><br/>
<span class='required-c'>85%</span> <span> requerido, </span>
  <span class='desirable-c-100'>100%</span><span> deseable.</span><br/><br/>
&oacute;<br/><br/>
<span class='required-c'>80%</span> <span> requerido, </span>
  <span class='desirable-c'>70%</span><span> deseable.</span><br/><br/>

<br/>


<br/><br/><hr/><br/><br/>

<span>Colisiones: </span> <span  class='collisions-0'>0</span><br/></br>

<span>Colisiones: </span> <span class='collisions'>10</span><br/></br>

<span>Restricciones requeridas:</span><br/></br>

<div class='constraint c-off'>
  <input type='checkbox'/> 
  <span class='value'>0</span>
  <span class='name'>Restricci&oacute;n deshabilitada.</span>
</div>

<div class='constraint c-off c-enable'>
  <input type='checkbox'/> 
  <span class='value'>0</span>
  <span class='name'>Restricci&oacute;n habilitada pero no evaluada.</span>
</div>

<div class='constraint c-good'>
  <input type='checkbox'/> 
  <span class='value'>0</span>
  <span class='name'>Restriccion sin errores.</span>
</div>

<div class='constraint c-good c-disable'>
  <input type='checkbox'/> 
  <span class='value'>0</span>
  <span class='name'>Restriccion sin errores deshabilitada.</span>
</div>

<div class='constraint c-bad'>
  <input type='checkbox'/> 
  <span class='value'>10</span>
  <span class='name'>Restriccion con errores.</span>
</div>

<div class='constraint c-bad c-disable'>
  <input type='checkbox'/> 
  <span class='value'>10</span>
  <span class='name'>Restriccion con errores deshabilitada.</span>
</div>

<br/>
<span>Restricciones deseables:</span><br/></br>

<div class='constraint c-off'>
  <input type='checkbox'/> 
  <span class='value'>0</span>
  <span class='name'>Restricci&oacute;n deshabilitada.</span>
</div>

<div class='constraint c-off c-enable'>
  <input type='checkbox'/> 
  <span class='value'>0</span>
  <span class='name'>Restricci&oacute;n habilitada pero no evaluada.</span>
</div>

<div class='constraint c-good'>
  <input type='checkbox'/> 
  <span class='value'>0</span>
  <span class='name'>Restriccion sin errores.</span>
</div>

<div class='constraint c-good c-disable'>
  <input type='checkbox'/> 
  <span class='value'>0</span>
  <span class='name'>Restriccion sin errores deshabilitada.</span>
</div>

<div class='constraint c-bad'>
  <input type='checkbox'/> 
  <span class='value'>10</span>
  <span class='name'>Restriccion con errores.</span>
</div>

<div class='constraint c-bad c-disable'>
  <input type='checkbox'/> 
  <span class='value'>10</span>
  <span class='name'>Restriccion con errores deshabilitada.</span>
</div>


<br/><br/><hr/><br/><br/>

HORARIOS

<br/><br/><hr/><br/><br/>


