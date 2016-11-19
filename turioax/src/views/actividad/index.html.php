
<div class="row">

    <div class="col-md-9">

      
		<div class="row">         
            <!-- center left-->

			 <hr>
			 <h3>Eventos recientes en el sistema</h3>
				<div class="panel panel-default">
                
					<div class="page-container">
						
   						<div class="container" style="overflow: auto;min-width: 20%; max-width:80%; height:300px;">
							<div class="row">
								<?php
								foreach ($logs as $jlog){
									if (empty($jlog))
										continue;

									$log = json_decode($jlog);

									if (!empty($log->user->correo)){
										$datos = explode("@", $log->user->correo);
										$correo = $datos[0];
									}else{
										$correo = "An&oacute;nimo";
									}
									//$fecha = date_create_from_format(DateTime::ISO8601, $log->date);
									//print_r(DateTime::getLastErrors());

									echo '<div class="col-md-12">';
									echo '</br>';
									echo $log->date;
									//echo ' | '.$fecha->format(DateTime::ISO8601);
									echo '&nbsp';
									echo $correo;
									echo '&nbsp';
									echo '<span style="color:red;font-weight:bold">';

									switch ($log->action) {
										case "EDIT":
											echo "Editó";
											break;
										case "DELETE":
											echo "Agregó";
											break;
										case "ADD":
											echo "Creó";
											break;
									}

									echo '</span>';
									echo '&nbsp';
									echo '<span style="color:#135683;font-weight:bold">'; 
									echo $log->obj->clase;
									echo '</span>';
									echo '&nbsp';
									echo $log->obj->nombre;
									//echo '</br>';
									//echo "<pre>";
									//print_r($log);
									//echo "</pre>";
  									
									echo '</div>';

								}
								?>
								</div>
  								
							</div>
  	 					</div>
					</div>

				</div>
		</div>
	</div>
</div>