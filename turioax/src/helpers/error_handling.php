<?php

// TODO formatear error
function extract_error($e){
    $errors = array();
    preg_match("/^exception (.*) in/", $e->getMessage(), $errors);
    if (count($errors) > 0){
        return $errors[1];
    }else
        return $e->getMessage();
}

function show_error($error, $ex = null){
    if (!DEBUG){
        render('error', array('error' => $error), DISPATCH_LAYOUT_DEFAULT);
    }else{ // produccion
        render('debug',
            array('msg' => $error, 'error' => $ex),
            DISPATCH_LAYOUT_DEFAULT);
    }
    stash('NO-RENDER', true); // Detiene el flujo.
}

?>
