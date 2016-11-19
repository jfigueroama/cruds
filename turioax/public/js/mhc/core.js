// acceso a los elementos de cada horario, incluyendo los metabounds
function partir(a, tcacho){
  var r = [];

  var ini = 0;
  var fin = ini + tcacho;
  for ( ; ini < a.length; ini += tcacho){
    var fin = ini + tcacho;
    var cacho = a.slice(ini, fin);
    r.push(cacho);
  }

  return r;
}

/**
 * Reduce un nombre sacando las
 *
 */
function reducir_nombre(nombre){
  return nombre.replace(" ", '<br/>');
}


//////////////////////////

function tspace(spec, cacho){
  return cacho[spec.tspace];
}

function pspace(spec, cacho){
  return cacho[spec.pspace];
}

function teachers(spec, mbound){
  if (mbound.length > 1){
    var ts = _.uniq(
        _.map(mbound, function (v){
          return v.teacher;
        }));
  }else{
    var ts = [ mbound[0].teacher ];
  }
  return ts;
}

;;;;;;;;;;;;;;;;;;;;;;;;;;

function thours(spec, cacho){
  var horas = cacho.slice(spec['thours-begin'],
      spec['thours-end'] + 1); // +1 o solo va a tomar 9 de 10 :-s

  if (horas.length != spec['working-days'] * 2){
    console.log('horas raras' + cacho + ' ' + horas);
  }
  
  return horas;
}

function phours(spec, cacho){
  var horas =  cacho.slice(spec['phours-begin'],
      spec['phours-end'] + 1);

  return horas;
}

function empty_days_hours(spec){
  var hdays = []

    for (var i=0; i< spec['working-days']; i++){
      hdays.push([]);
    }

  return hdays;
}

// recibe un arreglo en la forma [9, 2, 3, 1, 4, 0, 0, 2] con parejas
// de hora-inicio, duracion, hora-inicio, duracion ... y devuelve un
// arreglo con los dias de la semana en arreglos en los cuales las horas
// ya estan expandidas.
function expand_hours(spec, hours){
  horas = empty_days_hours(spec);
  dias  = partir(hours, 2);

  for (var i=0; i< dias.length; i++){
    var dia = dias[i];
    var ini = dia[0];  
    var can = dia[1];

    if (ini == 0 || can == 0){
      continue;
    }

    for (var j=0; j<can; j++){
      try{
        horas[i].push(ini + j);
      }catch (e){
        console.log('problemas');
      }
    }
  }

  return horas;
}


;;;;;;;;;;;;;;;;;;;;;;;;;;


function generar_solucion(spec, bounds){
  var sol = _.map(bounds, function(bn){
    return _.sample(bn);
  });

  // copiando los la hora de inicio de cada clase a las demas en el vector.
  
  var cachos = sol.length / spec.psize;
  for (var i=0; i< cachos; i++){
    var j= i * spec.psize;
    
    j += spec['thours-begin'];
    var hinicio = sol[j];
    j += 2; // hora, duracion, hora, duracion
    for (var k=1; k< spec['working-days']; k++, j+=2){
      sol[j] = hinicio;
    }

    j = i * spec.psize;
    j += spec['phours-begin'];
    var hinicio = sol[j];
    j += 2; // hora, duracion, hora, duracion
    for (var k=1; k< spec['working-days']; k++, j+=2){
      sol[j] = hinicio;
    }
  }

  return sol;
}

function unidora(ini, mcacho){
  var spec    = scheduling_data['problem-representation'];
  var kb      = scheduling_data['kb'];
  var cacho   = mcacho.cacho;
  var mb      = mcacho.metabound;

  var espaciot    = tspace(spec, cacho);
  var espaciop    = pspace(spec, cacho);

  var horast      = expand_hours(spec, thours(spec, cacho));
  var horasp      = expand_hours(spec, phours(spec, cacho));

  for (var i=0; i < mb.length; i++){
    var asignacion    = mb[i];
    var grupo         = asignacion.group;
    var asignatura    = asignacion.lecture;

    var profesores    = teachers(spec, mb);
    var carrera   = kb.grupo[grupo].carrera_id;
    var semestre  = kb.grupo[grupo].semestre;

    if (ini[carrera] == undefined)
      ini[carrera] = {};
    if (ini[carrera][semestre] == undefined)
      ini[carrera][semestre] = {};
    if (ini[carrera][semestre][grupo] == undefined)
      ini[carrera][semestre][grupo] = [];

    ini[carrera][semestre][grupo].push({
      'profesores'  : profesores,
      'asignatura'  : asignatura,
      'horas'       : {'teoria': horast, 'practica': horasp},
      'espaciot'    : espaciot,
      'espaciop'    : espaciop });

  }

  return ini;
}

/**
 * Horario
 *
 *
 * El horario es un arreglo de objetos de la siguiente manera:
 * {'idcarrera1':
 *    {'idsemestre1':
 *        {'idgrupo1':[
 *          {
 *            'materia' : M,
 *            'profesor': x,
 *            'horas': [],
 *            'espaciot': et,
 *            'espaciop': ep }  // clase o asignacion con horario
 *  
 *
 *
 */
function generar_horario(data){
  var gmetabounds = data.metabounds;
  var gbounds     = data.bounds;
  var gkb         = data.kb;
  var gspec       = data['problem-representation'];

  var sol         = generar_solucion(gspec, gbounds);
  var cachos      = partir(sol, gspec.psize);
//  var cachos      = cachos.slice(0,1);// TODO pruebas
  console.log(cachos);
  var mcachos     = _.map(cachos, function(cacho, idx){
    return {'cacho' : cacho, 'metabound': gmetabounds[idx]};
  });

  var vini = {};
  var horario = _.reduce(mcachos, unidora, vini);

  return horario;
}

function pintar_horario(horario){
  var divhorario = document.getElementById('horario');
  divhorario.innerHTML = '';

  var shorario = '';
  var shoras   = '';

  var colores = paleta('easter-time-1');

  for (cid in horario){
    var carrera = horario[cid];
    var cinfo = gkb.carrera[cid];

    for (semeid in carrera){
      var semestre = carrera[semeid];
      shorario += '<h3>' + cinfo.nombre +' | Semestre '+ semeid +'</h3><br/>';

      for (gid in semestre){
        var asignaciones = semestre[gid];
        var ginfo = gkb.grupo[gid];
        shorario += '<h4>' + ginfo.codigo + '</h4>';
        shorario += 
          '<table class="table"><tr><th>Asignatura</th> <th>Profesor</th>'+
          '<th>E. Teo&iacute;a</th><th>E. Pr&aacute;ctica</th></tr>';

        for (var i=0; i<asignaciones.length; i++){
          var asignacion = asignaciones[i];
          var asignatura = asignacion['asignatura'];
          var profesores = asignacion['profesores'];

          var color = colores[i];

          var ainfo = gkb.asignatura[asignatura];
          var pinfo = [];
          for (var j=0; j<profesores.length; j++){
            pinfo.push(gkb.profesor[profesores[j]]);
          }
          pnombres = _.map(pinfo, function(pinf){
            return pinf.nombres + ' ' + pinf.apellidos;
          }).join('<br/>');

          etinfo = {nombre: ''};
          if (asignacion.espaciot)
            etinfo = gkb.espacio[asignacion.espaciot];

          epinfo = {nombre: ''};
          if (asignacion.espaciop)
            epinfo = gkb.espacio[asignacion.espaciop];

          shorario += '<tr>' +
            '<td style="background-color: '+ color + '">'
                   + ainfo.nombre + '</td>' +
            '<td>' + pnombres + '</td>' +
            '<td>' + etinfo.nombre + '</td>' +
            '<td>' + epinfo.nombre + '</td>' + '</tr>';

        }

        shorario += '</table><hr>';

        // Tabla del horario
        shoras = '<table class="table"><tr><th></th><th>Lunes</th>' + 
                  '<th>Martes</th><th>Mi&eacute;rcoles</th><th>Jueves</th>'+
                  '<th>Viernes</th></tr>';

        var shora = ''; // Para un renglon u hora en toda la semana
        var horasl = [7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20];
        for (var ih=0; ih < horasl.length; ih++){
          var hora = horasl[ih];

          shora += '<tr>';
          shora += '<td><b>' + hora + ' h</b></td>';

          var horasinclase = true;

          // dias en la semana
          for (var ida=0; ida < gspec['working-days']; ida++){

            // buscar la hora en todas las asignaciones.
            // si la encuentra, salir de este ciclo.
            for (var idaa=0; idaa < asignaciones.length; idaa++){
              var asignacion = asignaciones[idaa];
              var asignatura = asignacion['asignatura'];
              var espaciot   = asignacion.espaciot;
              var espaciop   = asignacion.espaciop;
              var ainfo = gkb.asignatura[asignatura];
              
              var color = colores[idaa];

              if (espaciot){
                var horast = asignacion.horas.teoria[ida];
                
              }else{
                var horast = empty_days_hours(gspec);
              }
              
              if (espaciop){
                var horasp = asignacion.horas.practica[ida];
              }else{
                var horasp = empty_days_hours(gspec);
              }

              var tt = false;
              var tp = false;


              if (_.contains(horast, hora)){
                tt = true;
              }
              //console.log('Horas t: ' + horast + ': ' + hora + ' dia: ' + ida
              //     + ' tt: ' + tt);
             
              if (_.contains(horasp, hora)){
                tp = true;
              }
              var adata = null; // guarda la info de la asignatura

              var anombre = reducir_nombre(ainfo.nombre);
              if (tt == true && tp == true){
                adata = anombre + '<br/>TP';
              }else if (tt == true){
                adata = anombre + '<br/>T';
              }else if (tp == true){  // tp
                adata = anombre + '<br/>P';
              }
              
              if (adata){
                shora += '<td style="background-color: '+ color + '">' 
                      + adata + '</td>';
                horasinclase = false;
                break;
              }
            }
            
            if (!adata){
              shora += '<td></td>';
            }
          }

          shora += '</tr>';

          if (!horasinclase){
            shoras += shora;
          }

          shora = '';
          horasinclase = false;
        }

        shoras += '</table>';
        shorario += shoras;
      }

    }
  }

  divhorario.innerHTML = shorario;
}

function nuevo_horario(){
  var horario = generar_horario(scheduling_data);
  pintar_horario(horario);
}


