<?php
/**
 * Autorization helper.
 *
 * Mete las reglas de autorizacion basicas definidas en vicerrectoria administrativa:
 * - Todo mundo que se loguee puede ver todo.
 * - El administrador puede hacer todo.
 * - Los profesores y el personal pueden editar su perfil de Profesor o Personal.
 * - Los Jefes de Carrera pueden crear/editar
 *   - Asignaciones que sean para los grupos de las carreras que dependen de su instituto.
 * - Las secretarias pueden crear/editar
 *   - Grupos?
 *   - Asignaturas?
 *
 */


//function authorize_


/**
 * Busca si el usuario tiene un cargo asignado que este en la lista de $cargos
 * requeridos para poder escribir (crear/editar/eliminar) sobre una
 * entidad.
 */
function has_cargo($user, $cargos = null){
    if (!$cargos)
        $cargos = array();

    $res = false;
    $ucargos = $user->cargos;
   
    foreach ($ucargos as $cargo){
        foreach ($cargos as $requerido){
            if ($cargo->codigo == $requerido){
                $res = true;
                break;
            }
        }
        if ($res)
            break;
    }
    return $res;
}


function writable_for($clase, $obj = null){
    $res = false;

    $us    = current_user();

    if ($us){
        $cuser = $us['clase'];
        if (!empty($cuser)){
            $user  = $cuser::find($us['id']);
        }else{
            return $res;
        }

        $aufn = 'authorize_' + $clase;
        if (function_exists($aufn)){
            $res = $aufn($us);
        }else{
            // Revisar permisos globales de aministrador o de secretarias y
            // de jefes de carrera.

            if (isset($clase::$writable_by)){
                if (has_cargo($user, $clase::$writable_by)){
                    $res = true;
                }
            }

            if (method_exists($clase, 'writable_by')){
                $res = $clase::writable_by($user, $obj);
            }

            if (!$res){
                if ( has_cargo($user, array('ADMIN')) ){
                    //echo "El usuario es administrador!<br/>";
                    $res = true;
                }
            }
        }
    }  // Default: no dejar ver nada a nadie no logeado.

    /*
    if ($res)
        echo "TIENE permisos :-|";
    else
        echo "NO TIENE PERMISOS";
     */

    return $res;
}

function authorize_login(){
    if (CA_AUTHORIZE && !is_logged())
        redirect('/sesion/login');

}

function authorize_write($clase, $obj = null){
    if (!CA_AUTHORIZE)
        return true;

    if (!is_logged()){
        throw new Exception("Requiere iniciar sesi&oacute;n y tener los permisos adecuados.");
    }


    if (!writable_for($clase, $obj))
        throw new Exception("No autorizado.");
    return true;

}

/*
function is_writable_for($clase, $obj){
    $res = false;
    if (writable_for($clase, $obj) && CA_AUTHORIZE)
        $res = true;

}
 */
?>
