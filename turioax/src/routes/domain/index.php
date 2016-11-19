<?php
/* Funcion para editar en el scaffolding
 *
 *
 *
 *
 *
 *
 */

function toindex(){
    //    redirect()
    $keys = array_keys($_GET);
    $url  = site_url().MR.$_SERVER['QUERY_STRING'];
    $last = substr($url, -1);

    $url .= $last == '/' ? '' : '/';
    $url .= 'index';

    nredirect($url);
}

function index(){
    $clase  = stash('clase');
    $attrs  = $clase::table()->columns;
    $params = stash('params');


    $ofiltros = null;
    if (isset($_POST['filters'])){
        $ofiltros = get_filters();
        add_filters($_POST['filters']);
    }

    ////////////////////////////////////
    
    $htmlfiltros = build_html_filters($clase, get_filters(), $ofiltros);
    $filtros = get_filters();   // Pueden haber cambiado.

    ////////////////////////////////////

    // TODO Quitado el false para desactivar!
    if (method_exists($clase, 'index')){
        /*
        $data   = $clase::index($filtros);
        $objs   = $data['instancias'];

        $campos = $data['campos'];
        if (is_array($campos)){
            $nattrs = array();
            foreach ($campos as $campo){
                unset($attrs[$campo]);
                $nattrs[$campo] = $attrs[$campo];
            }
            $attrs = $nattrs;
        }
         */
        $objs   = $clase::index($filtros);
    }else{
        $objs  = $clase::find('all');
    }
    $exceptos = array();
    if (isset($params['excepto'])){
        $exceptos = explode(',', $params['excepto']);
        foreach ($exceptos as $excepto){
            unset($attrs[$excepto]);
        }
    }

    $sexcepto   = implode(',', $exceptos);
    $urlexcepto = site_url().MR."/domain/$clase/p&excepto=";
    if (!empty($sexcepto))
        $urlexcepto .= $sexcepto.',';

    render('domain'.DS.'index',
        array('clase' => $clase, 'objs' => $objs, 'attrs' => $attrs,
        'htmlfiltros'  => $htmlfiltros,
        'urlexcepto' => $urlexcepto));

}

?>
