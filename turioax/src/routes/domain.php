<?php
/* Ruteador principal para los scaffoldings creados para las tablas del sistema
 * usando el ORM PHP-ActiveRecord como fuente de informacion.
 *
 * @author Jose Figueroa Martinez <jfigueroa@mixteco.utm.mx
 * @date 2013-05-03
 */

filter('domainclase', function ($clase) {
    if (isset($clase) &&
        is_subclass_of($clase, 'ActiveRecord\Model')){
            stash('clase', $clase);
    }else{
        show_error('Entidad del dominio inexistente: '.
            strval($clase));
//        render('error',
  //      array('clase' => $clase, 'objs' => $objs, 'attrs' => $attrs,
//              'urlexcepto' => $urlexcepto));

    }
});

filter('domainid', function ($id) {
    try{
        $clase = stash('clase');
        $obj   = $clase::find($id);
        stash('obj', $obj);

    }catch(Exception $e){
        show_error("No se pudo encontrar la instancia de la
            clase $clase con ID: $id", $e);
    }

});

filter('domainattr', function ($attr) {
    try{
        $clase    = stash('clase');
        $obj      = stash('obj');
        $relation = $clase::table()->get_relationship($attr);
        if (!$relation){
            throw new Exception();
        }


        print_r($relation);
        stash('attr_base', $attr);
        stash('relation', $relation);

    }catch(Exception $e){
        show_error("No se pudo encontrar la instancia de la
            clase $clase con ID: $id", $e);
    }

});

/* Util para parametros GET.
 * 
 * Ruta: get('/domain/:domainclase/:p.*', 'index');
 * Url: http://localhost/computacion/sa/?/domain/Profesor/p&excepto=nacionalid,id,nombre
 *
 */
filter('domainp', function($params){
//    $aparams = array();
//    $a = explode('&', $params);
//    $i = 0;
//    while ($i < count($a)){
//        $b = split('=', $a[i]);
//        $pname  = urldecode($b[0]);
//        $pvalue = urldecode($b[1]);
//        $aparams[$name] = $pvalue;
//        $i++;
//    }
//    stash('params', $aparams);
    unset($_GET[0]);
    stash('params', $_GET);
});


require_once(CA_RT_PATH.'domain'.DS.'index.php');
get('/domain/:domainclase', 'toindex');
get('/domain/:domainclase/index', 'index');
get('/domain/:domainclase/index/:p.*', 'index');

post('/domain/:domainclase', 'toindex');
post('/domain/:domainclase/index', 'index');
post('/domain/:domainclase/index/:p.*', 'index');

require_once(CA_RT_PATH.'domain'.DS.'new.php');
get('/domain/:domainclase/new', 'new_obj');
post('/domain/:domainclase/new', 'new_obj');

require_once(CA_RT_PATH.'domain'.DS.'edit.php');
get('/domain/:domainclase/edit/:domainid', 'edit');
post('/domain/:domainclase/edit/:domainid', 'edit');

get('/domain/:domainclase/edit-relation/:domainid/:domainattr', 'edit_relation');
post('/domain/:domainclase/edit-relation/:domainid/:domainattr', 'edit_relation');

require_once(CA_RT_PATH.'domain'.DS.'delete.php');
get('/domain/:domainclase/delete/:domainid', 'delete_obj');
post('/domain/:domainclase/delete/:domainid', 'delete_obj');

require_once(CA_RT_PATH.'domain'.DS.'view.php');
get('/domain/:domainclase/view/:domainid', 'view');
//get('/domain/:domainclase/view/:domainid/:params.*', 'view_params');
get('/domain/:domainclase/view-partial/:domainid', 'view_partial');

//get('/domain/:domainclase/:domainp.*', 'index');
 

?>
