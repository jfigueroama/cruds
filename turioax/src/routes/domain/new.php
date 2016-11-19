<?php
/* 
 *
 * PARAMAS??: valores default del sistema/valores default datos.
 * El manejo de errores se hace a traves del stach "error", el cual se muestra en la misma
 * ventana de alta.
 *
 * Se van a tratar de reutilizar los valores proporcionados por el usuario.
 *
 */

function new_obj(){
    $clase = stash('clase');
    $tabla = $clase::table();

    set_meta_defaults($clase, get_filters());

    $newid = null;
    $datos = array();
    $errors = null;
    if (isset($_POST['crear'])){
        unset($_POST['crear']);
        $datos = $_POST;

        foreach ($datos as $cp1 =>$vp1){
            if (is_foreign_key($cp1) && empty($vp1))
                $datos[$cp1] = null;    // Asegura integridad referencial
        }

        try{
            $obj = new $clase($datos);
            authorize_write($clase, $obj);

            if ($obj->is_invalid()){
                $errors = $obj->errors;
                throw new Exception('Existen errores de validaci&oacute;n de datos:');
            }
            $clase::transaction(function() use ($clase, $obj){
                $saved = $obj->save();
                if (!$saved || $obj->is_dirty()){
                    throw new Exception('No se pudo crear la instancia. 
                                        Verifique los datos ingresados.');
                }
            });
            $newid = $obj->id;

            logg('ADD', $obj, $clase);
        }catch(Exception $e){
            stash('error', $e->getMessage());
            print_r($obj);
            throw $e;
        }

        if (!stash('error')){
            stash('msg', "Instancia creada exitosamente.");
            $datos = array();
        }
    }

    $fields = create_fields($clase, $tabla, $datos, $errors);

    render('domain'.DS.'new',
        array('clase' => $clase, 'tabla' => $tabla,
        'newid' => $newid, 'fields' => $fields));
}
?>
