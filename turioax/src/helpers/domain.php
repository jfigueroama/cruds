<?php
/**
 * Funciones para apoyar el manejo del dominio en el scaffolder.
 *
 * Requiere acceso a los Models.
 *
 *
 */

define('VUNICO', 'El valor debe ser &uacute;nico en toda la tabla.');
define('VPRESENTE', 'Debe proporcionar un valor.');
define('VMAYORCERO', 'El valor debe ser mayor a cero.');
define('VSELECCION', 'Debe seleccionar un elemento.');

// Devuelve la clave foranea de la asociacion con nombre $attr en la tabla
// $tabla.
function get_relation_fk($attr, $tabla){
    $relacion = $tabla->get_relationship($attr);
    if (count($relacion->foreign_key) < 2){
        $fk = null;
        if (count($relacion->foreign_key) > 0)
            $fk = $relacion->foreign_key[0];
        return $fk;
    }else{
        throw new Exception("La relacion $attr tiene una
            clave foranea compuesta.");
    }
}

// Devuelve la clase de una relacion recibiendo el nombre del atributo y la
// metainformacion de la tabla.
function get_relation_class($attr, $tabla){
    $relacion = $tabla->get_relationship($attr);
    $cn = $relacion->class_name;
    return $cn;
}


function is_foreign_key($campo){
    $l = strlen($campo);
    $tres = substr($campo, $l - 3, 3);      // algo_id
    if ($tres == '_id')
        return true;
    else
        return false;
}


/**
 * Crea un control html de un formulario para poder agregar o editar el valor
 * de un atributo siemple o medio simple utilizando la metainformacion del
 * atributo y la informacion extra colocada en el arreglo $meta de la clase.
 *
 * $attr  - Nombre del atributo.
 * $info  - informacion de phpactivercord sobre el atributo.
 * $meta  - metainformacion del arreglo Clase::$meta[$attr].
 * $value - Valor del atributo en este momento.
 *
 * Del arreglo $meta se manejan los siguientes atributos:
 * autofocus- true/false para tener autofocus o no
 * noedit   - true/false para no devolver nada.
 * hidden   - true/false para esconder el control en un input hidden.
 * readonly - true/false 
 * type     - "hora", "horas", "fecha", "color",. Para tipos raritos.
 * //size     - "area", "normal", etc. Para saber si es un textarea.
 * //            Default es normal.
 * values   - array(array(0, "Inactive",), array(1, 'Active'))
 *            Es para campos enteros cuyo valor es una opcio que aparte
 *            aparte tiene un nombre en html.
 * tip      - Es un tooltip.
 * fname    - "Friendly name", usado para el label.
 *
 * Retorna el control en HTML
 */
function create_control($field){
    $jquery_date_format = 'dd/mm/yy';
    $control = 'NO SOPORTADO';
    
    $attr = $field->name;
    $info = $field->info;
    $meta = $field->meta;
    $value = $field->value;

    if (!$meta)
        $meta = array();
    if (!$info)
        $info = array();

    if (//preg_match("/.*_id$/", $attr) ||
        $attr == 'id') // $tabla->pk[0]) // si es igual a *_id o a id
        if (!isset($field->meta)){
            return null;
        }

    // noedit hace que el control no tenga name. Para esconderlo hacerlo
    // hidden, readonly o disabled.
    $name = "name=\"$attr\"";
    if (isset($meta['noedit']) && $meta['noedit']){
        $name = '';
    }

    $title   = isset($meta['tip']) ? $meta['tip'] : '';
    $afocus  = isset($meta['autofocus']) ? 'autofocus="autofocus"' : '';
    $readonly= isset($meta['readonly']) && $meta['readonly'] ? 
                    " readonly='readonly'" : '';
    $disabled= isset($meta['disabled']) && $meta['disabled'] ? 
                    " disabled='disabled'" : '';

    if (isset($meta['hidden']) && $meta['hidden']){
        $control = "<input type='hidden' $name value=\"$value\" />";
        return $control;
    }


    if (isset($field->values) && is_array($field->values)){
        $values = $field->values;  // array(array(0, "V1"), array(1, "V1"))
        if (!isset($field->size))
            $field->size = 1;  // Combo

        $control    = "<select id='$attr' $name title='$title' $afocus  class='form-control' size='$field->size' $readonly $disabled>";

        if ($field->type == 'relation' || $field->type == 'belongs_to'){
            $selected   = empty($value) ? 'selected="selected"' : '';
            $control .= "<option value=''>----</option>";
        }

        // SORT para las que vienen de una relacion.
        if ($meta['type'] == 'relation')
            usort($values, function($a, $b){
                return $a[1] > $b[1];
            });
        foreach ($values as $val){
            $vval   = $val[0];
            $vlabel = $val[1];

            $selected   = $vval == $value
                ? 'selected="selected"' : '';
            $control .= "<option value='$vval' title='$vlabel' $selected>$vlabel</option>";
        }
        $control    .= '</select>';

        if (($field->type == 'relation' || $field->type == 'belongs_to'
            || $field->type == 'has_many') && $field->class){
                // TODO revisar evento de mouse
                // TODO ver si se puede definir la url de visualizacion
                $cll = isset($field->meta['source_class']) ?
                    $field->meta['source_class'] : $field->class;
                $url = site_url().'?/domain/'.$cll.'/edit/';
                $js="this.href='$url'+document.getElementById('$attr').value;";
                $control .= "<a href='' onmouseover=\"$js\" onfocus=\"$js\" "."
                    id='url_$attr'>Ir</a>";
        }
        
        return $control;
    }

    if (!isset($field->type))
        $field->type = $field->info->raw_type;

    switch ($field->type){

    case 'restriccion':
      $control = "<div id='{$attr}_content'></div>
            <script type='text/babel'>
            ReactDOM.render(<Restriccionh
                      name='$attr' value='$value' />,
                    document.getElementById('{$attr}_content'));
          </script>";
      break;

    case 'chorario':
      $control = "<div id='{$attr}_content'></div>
        <script type='text/babel'>
            ReactDOM.render(<Chorario name='$attr' value='$value' />,
                    document.getElementById('{$attr}_content'));
          </script>";

      break;

    case 'hour':
        $control = 'HOUR';
        break;
    case 'hours':
        $control = 'HOURS';
        break;
    case 'textarea':
        $control = "<textarea $name $afocus title='$title' class='form-control'>$value</textarea>";
        break;
    case 'date':
        $icono = site_url().'public/images/calendar.gif';
        $control = "<script>
            $(function() {
                $( '#$attr' ).datepicker({
                    dateFormat: '$jquery_date_format',
                        changeMonth: true,
                        changeYear: true,

                        showOn: 'button',
                        buttonImage: '$icono',
                        buttonImageOnly: true
    });
    });</script>";
    $control .= "<input type='text' id='$attr' $name value='$value' $afocus title='$title' $readonly $disabled class='form-control' />" ;
    break;
    default:
        $nvalue = h($value);
        $control = "<input type='text' $name value=\"$nvalue\" $afocus title='$title' $readonly $disabled class='form-control'/>";
        break;
    }

    return $control;
}

function create_label($field){
    $attr = $field->name;
    $info = $field->info;
    $meta = $field->meta;

    if (isset($meta['hidden']) && $meta['hidden'])
      return '';

    $style = '';
    $tip   = '';
    if (!empty($field->error)){
        $style = 'color: red';
        $tip = $field->error;
    }

    $lab   = isset($meta['fname'])? $meta['fname'] : ucfirst($attr);
    //$label = "<label for='$attr'>".$lab."</label>";

    $label = "<label style='$style' title='$tip'>".$lab."</label>";
    return $label;
}


function create_empty_field($key){
    $field = new stdClass();
    $field->name = $key;
    $field->type = null; 
    $field->meta = array();
    $field->info = array();
    $field->value = null;
    $field->error = '';

    return $field;
}

function create_field($key, $tabla, $datos = array(), $errors = null, $obj = null, $clase, $metas = array()){   
    $attrs = $tabla->columns;

    $attr = $attrs[$key];
    $info = $attrs[$key];
    $meta = (isset($metas[$key])) ? $metas[$key] : array();

    $field = new stdClass();
    $field->name = $key;
    $field->class = isset($meta['class']) ? $meta['class'] : null;
    $field->type = isset($meta['type']) ? $meta['type'] : $info->raw_type;
    $field->meta = $meta;
    $field->info = $info;

    $field->error = $errors->$key ? $errors->$key : '';  // Validation errors
    if (is_array($field->error)){
        $field->error =  $field->error[0];
        $field->meta['autofocus'] = true;
    }

    $field->value = null;
    if (isset($datos[$key])){
        $field->value = $datos[$key];
    }elseif (isset($obj) && isset($obj->$key)){
        $field->value = $obj->$key;
    }elseif (isset($meta['default'])){
        $field->value = $meta['default'];
    }

    if (isset($meta['values'])){
        if (is_array($meta['values']))
            $field->values = $meta['values'];
        elseif (method_exists($clase, $meta['values'])){
            $mname = $meta['values'];
            $field->values = $clase::$mname();
        }else{
            throw new Exception("Error al comprobar los values generados: $clase $field->name");
        }
    }

    return $field;
}



/**
 * Emula la clase Errors de ActiveRecord para que no falle el acceso al obj.
 */
class NoErrors {
    function __get($algo){
        return null;
    }
}

/**
 * Crea los objetos field a partir de los datos de la clase, la tabla y los
 * datos mandados por el usuario.
 */
function create_fields($clase, $tabla, $datos = array(), $errors = null, $obj = null){
    $fields = array();
    $attrs  = $tabla->columns;

    if (is_null($errors))
        $errors = new NoErrors();

    $metas    = isset($clase::$meta) ? $clase::$meta : array();
    $keys    = isset($clase::$order) ? $clase::$order : array_keys($attrs);

    foreach ($keys as $key){
        if (array_search($key, $tabla->pk) !== false
            && !isset($metas[$key])){  // Encontrada una pk y no tiene meta datos
                continue;
            }else{
                // pass si es primaria con metadatos
            }

        if ($key == 'horas'){
          //print_r($datos);
        }
        $field = create_field($key, $tabla, $datos, $errors,
                              $obj, $clase, $metas);
        if ($field)
            $fields[] = $field;
    }

    return $fields;
}

function values_from_class($clase, $order = null){
    $values = array();
    $filters = get_filters();

    if (!method_exists($clase, 'index')){
        if (is_null($order))
            $objs = $clase::find('all');
        else
            $objs = $clase::find('all',  array('order' => $order));

    }else{
        $ofi = isset($clase::$only_filter_index)
                ? $clase::$only_filter_index : false;
        if(!$ofi){
         $objs = $clase::index($filters);
        }else{
            $objs = $clase::find('all');
        }
    }
    
    foreach ($objs as $obj){
        $values[] = array($obj->id, $obj->_name);
    }
    return $values;
}

?>
