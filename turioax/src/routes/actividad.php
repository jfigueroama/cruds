<?php


get('/actividad', function(){ 
    redirect('/actividad/index');

});

get('/actividad/index', function(){ 
	$logf = CA_LOG_FILE;
	$logs = array();

	if (!empty($logf)){
		$slogs = file_get_contents($logf);
		$logs = explode("\n", $slogs);

	} 
	
    render('actividad'.DS.'index',
    	array('logs' => $logs));


        //render('index'.DS.'proyectos',
    //    array('proyectos' => $proyectos), false);

});



 
?>
