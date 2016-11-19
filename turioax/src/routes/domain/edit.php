<?php
/* Funcion para editar en el scaffolding.
 *
 * Las directivas de seguridad van a ser basicas: Solo los administradores
 * pueden entrar a estas paginas.
 * Los administradores pueden cambiar todo lo que necesiten por ahora.
 *
 * Las vistas van a ser tambien medio programadas y van a recibir un
 * arreglo de metainformacion para crear la interfaz. Van a ser muy dinamicas
 * pero basicas por ahora. La idea es tenerlas para hoy y no para una semana
 * despues.
 */
require_once(CA_HP_PATH.'domain.php');

function edit(){
    $clase = stash('clase');
    $obj   = stash('obj');

    $tabla = $clase::table();

    $errors = null;
    $datos  = array();
    $forms = array();


    if (isset($_POST['_form_name'])){
        $cual  = $_POST['_form_name'];
        unset($_POST['_form_name']);

        if ($cual == 'main'){
            try{
                $datos = $_POST;

                // Actualizando sin guardar
                foreach ($datos as $cp1 =>$vp1){
                    if (is_foreign_key($cp1) && empty($vp1))
                        $vp1 = null;    // Asegura integridad referencial
                    $obj->$cp1 = $vp1;
                }

                if ($obj->is_invalid()){
                    $errors = $obj->errors;
                    throw new Exception('Existen errores de validaci&oacute;n de datos:');
                }else{
                    authorize_write($clase, $obj);

                    $clase::transaction(function() use ($obj, $clase){
                        $saved = $obj->save();
                        if (!$saved || $obj->is_dirty()){
                            throw new Exception("No se pudo guardar
                                                la instancia.");
                        }
                    });    
                    logg('EDIT', $obj, $clase);
                    stash('msg', 'Los datos fueron actualizados con &eacute;xito.');
                }
            }catch(Exception $e){
                stash('error', $e->getMessage());
            }
        }else{
            try{
                $tclase = $_POST['class'];
                unset($_POST['class']);

                if ($cual == 'remover' && count($_POST) > 0){ // Se quiere eliminar algo.
                    try{
                        $tval = array_pop($_POST);
                        $tobj = $tclase::find($tval);
                        authorize_write($tclase, $tobj);

                        $tclase::transaction(function() use ($tobj, $tclase){
                            $tobj->delete();
                            // TODO Poner mensaje de exito.
                        });
                        
                        logg('DELETE', $tobj, $tclase);
                    }catch(Exception $e){
                        stash('error', $e->getMessage());
                    }
                }elseif ($cual == 'agregar'){
                    try{
                        foreach ($_POST as $cp1 =>$vp1){
                            if (is_foreign_key($cp1) && empty($vp1))
                                $_POST[$cp1] = null;    // Asegura integridad referencial
                        }

                        $tobj = new $tclase($_POST);
                        authorize_write($tclase, $tobj);

                        $tclase::transaction(function() use ($tobj, $tclase){
                            $saved = $tobj->save();
                            if (!$saved || $tobj->is_dirty()){
                                throw new Exception("No se pudo agregar
                                                la instancia seleccionada.");
                            }
                        });

                        logg('ADD', $tobj, $tclase);
                    }catch(Exception $e){
                        stash('error', $e->getMessage());
                    }
                    // TODO Poner mensaje de exito.
                }else{
                    throw new Exception("Formulario no definido.");
                }
            }catch (Exception $e){
            }
        }


        if (!stash('error')){
            stash('msg', "Instancia guardada exitosamente.");
        }
    }
    $obj = $clase::find($obj->id);  // Reabriendo el objeto.

    $forms['main'] = create_fields($clase, $tabla, $datos, $errors, $obj);

    // Formularios extra para las relaciones muchos a muchos.
    $hms = isset($clase::$has_many) // has_many
        ? $clase::$has_many : array();

    try{
        foreach ($hms as $hm){
            $atributo_fuente  = $hm[0];
            $clase_fuente     = get_relation_class($atributo_fuente, $tabla);

            if (isset($hm['noedit']) && $hm['noedit'])
                continue;

            // Solo many-to-many
            if (isset($hm['through'])){
                $atributo_enlace = $hm['through'];
                $clase_enlace    = get_relation_class($atributo_enlace, $tabla);

                if (empty($clase_enlace))   // La relacion no existe.
                    continue;
                $tabla_enlace    = $clase_enlace::table();

                $erasable_values   = array();
                if (isset($hm['erasable_values']) && method_exists($obj, $hm['erasable_values'])){
                    $filter = $hm['erasable_values'];
                    $erasable_values = $obj->$filter();
                }

                if (isset($clase_enlace::$belongs_to)){
                    $ebts = $clase_enlace::$belongs_to;

                    foreach ($ebts as $ebt){
                        $eattr = $ebt[0];
                        $efk   = get_relation_fk($eattr, $tabla_enlace);
                        $cfk   = get_relation_class($eattr, $tabla_enlace);

                        if (strval($cfk) == "\\$clase"){        // "\Profesor" == "\Profesor"

                            $campo = create_empty_field($atributo_fuente);
                            $campo->values = $erasable_values;
                            $campo->size   = 5;
                            $campo->type   = 'has_many';
                            $campo->class  = substr($cfk, 1);
                            $campo->meta['values'] = $erasable_values;
                            $campo->meta['type']   = 'has_many';
                            $campo->meta['size']   = '5';
                            $campo->meta['fname']  = ucfirst($atributo_fuente);
                            $campo->meta['through'] =
                                substr($clase_enlace, 1);
                            $campo->meta['source_class'] =
                                substr($clase_enlace, 1);

                            $campos = create_fields($clase_enlace, $tabla_enlace);
                            foreach ($campos as $ckey => $cp){
                                if ($cp->name == $efk){
                                    $cp->value = $obj->id;  // Definiendo el valor de este objeto.
                                    $cp->meta['hidden'] = true; // Poniendolo hidden.
                                    $campos[$ckey] = $cp;       // Agregandolo de nuevo.
                                    break;
                                }
                            }
                            $ttclase = strval($clase_enlace);
                            $ttclase = substr($ttclase, 1);

                            $forms[$atributo_fuente] = array(
                                'remove'=> $campo,
                                'add' => $campos,
                                'class' => $ttclase,
                                'fname' => $hm['fname'],
                                'type'  => 'crud'
                            );
                        }

                    }
                }
            }else{
                // INFO: Interfaces para ver hijos de una relacion
                // belongs_to externa. Se necesita definir "links" => true
                // en la especificacion de la relacion.

                if (isset($hm['links']) && $hm['links']){
                    $forms[$atributo_fuente] = array(
                        'class' => substr($clase_fuente, 1,
                                          strlen($clase_fuente)),
                        'fname' => isset($hm['fname']) ? $hm['fname'] :
                                    ucfirst($atributo_fuente),
                        'type'  => 'links'  // solo muestra enlaces
                    );
                }
            }
        }
    }catch (Exception $e){
        stash('error', 'Ocurri&oacute; un error inesperado: <br/>'.
            $e->getMessage());
    }
    

    render('domain'.DS.'edit',
        array('clase' => $clase, 'obj' => $obj, 'tabla' => $tabla, 'forms' => $forms));
}


?>
