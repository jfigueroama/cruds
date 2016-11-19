function paleta(nombre){
  if (!nombre)
    nombre = 'easter-time-1';

  var paletas = {
    'easter-time-1': ['#F898B9', '#FED277', '#B8D07B', '#79C9BD',
                      '#BDA9D0', '#BAD37A', '#7EA71E', '#A9CE40',
                      '#EDD678', '#E89A25', '#EAF9FF', '#C1E4F9']
  };

  return paletas[nombre];

}
