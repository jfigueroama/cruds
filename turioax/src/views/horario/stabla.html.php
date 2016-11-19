<div id='horarios' class='contenedorPrincipal'>
<h2>Construyendo Tabla de horarios. Por favor espere...</h2>
</div>
<script type='text/babel'>

window.carreras = <?php echo json_encode($carreras, JSON_PRETTY_PRINT); ?> ;

function fgrupo (grupo){
  return ( <Horariogrupostatic data={grupo} /> );
}


function fsemestre(semestre){
  let egrupos = semestre.grupos.map(fgrupo);
  let nse     = semestre.semestre.substr(1);
  return (
    <div>
      <h3>Semestre <span>{nse}</span></h3>
      {egrupos}
    </div>
  );
}

var ecarreras = carreras.map(function(carrera){
  let esemestres = carrera['semestres'].map(fsemestre);
  return (
    <div>
    <h2>{carrera.carrera}</h2>
    {esemestres}
    </div>
  );
});


ReactDOM.render(
  (<div>{ecarreras}</div>),
  document.getElementById('horarios')
  );
</script>
