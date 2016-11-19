<?php
/**
 * Utiliza el CA_LOG_FILE. Si esta vacio o no existe, no se logea. SI si,
 * Se loguea.
 *
 * Requiere la libreria de sesion.
 */

function logg($action, $obj = '', $clase = ''){
    if (defined('CA_LOG_FILE')){
        $lf = CA_LOG_FILE;
        if (!empty($lf)){
            $alogg = array();

            $alogg['date'] = date('c');
            $alogg['action'] = $action;
            $alogg['user'] = current_user();
            $user = $alogg['user'];

            $cargos = '';
            if ($user){
                if ($user['id']){
                    $cl = $user['clase'];
                    $user  = $cl::find($user['id']);

                    $acargos = $user->cargos;
                    if (is_array($acargos)){
                        $acargos = array_map(function($a){
                            return $a->codigo;
                        }, $acargos);
                        $acargos = array_filter($acargos, function($a){
                            return !empty($a);
                        });
                        if (count($acargos) > 0){
                            $alogg['user']['cargos'] = implode(', ', $acargos);
                        }
                    }
                }
            }else{
                $alogg['user'] = array(
                    'correo' => '',
                    'clase' => '',
                    'id' => -1);
            }

            $alogg['ip'] = $_SERVER['REMOTE_ADDR'];


            if ($obj){
                if (method_exists($obj, 'to_json')){
                    $obj = json_decode($obj->to_json(), true);
                    $obj['clase'] = $clase;
                }
            }
            
            $alogg['obj'] = $obj;

            $cadena = json_encode($alogg);
            $arch = fopen(CA_LOG_FILE, "a+");

            fwrite($arch, $cadena."\n");
            fclose($arch);
        }

    }
}


?>
