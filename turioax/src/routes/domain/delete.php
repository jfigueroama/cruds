<?php

function delete_obj(){
    $clase = stash('clase');
    $obj = stash('obj');

    $eliminar = isset($_POST['eliminar']) ? intval($_POST['eliminar']) : 0;
    if ( $eliminar != 0){
        try {
            // Checando si existe el metodo _erasable
            if (method_exists($obj, '_erasable')){
                authorize_write($clase, $obj);
                if ($obj->_erasable()){
                    $clase::transaction(function() use ($obj, $clase){
                        $obj->delete();
                        logg('DELETE', $obj, $clase);

                    });
                }else{
                    stash('error', 'Puede que la instancia a eliminar sea utilizada por otras instancias.');

                }

            }else{
                authorize_write($clase, $obj);
                $clase::transaction(function() use ($obj, $clase){
                    $obj->delete();
                    logg('DELETE', $obj, $clase);
                });
            }
        }catch (Exception $e){
            stash('error', $e->getMessage());
        }

    }

    render("domain/delete", array('obj' => $obj, 'clase' => $clase, 'eliminar' => $eliminar));
}

?>
