<?php
class Sitio extends ActiveRecord\Model{
    static $table_name = 'sitio';

    static $has_many = array(
 //       array('carreras', 'class_name' => 'Carrera'),
  //      array('profesores', 'class_name' => 'Profesor')
    );

    static $belongs_to = array(
 //       array('institucion')
    );

    static $validates_presence_of = array(
        array('nombre', 'message' => VPRESENTE));// Puede ser 'Debe proporcionar un valor'

    static $validates_uniqueness_of = array(
        array('nombre', 'message' => VUNICO)    // Puede ser 'Debe ser unico'
    );

    static $order = array('region_id', 'ciudad_id', 'nombre',
        'descripcion', 'telefono', 'correo', 'url', 'direccion',
        'latitud', 'longitud', 'observaciones', 'referencia'); 


    static $meta = array(
        'region_id' => array(
            'type' => 'relation',
            'fname' => 'Regi&oacute;n',
            'tip'  => '',
            'values' => '_region_id_values'),
        'ciudad_id' => array(
            'type' => 'relation',
            'fname' => 'Ciudad',
            'tip'  => '',
            'values' => '_ciudad_id_values'),
        'nombre' => array(
            'autofocus' => true,
            'fname' => 'Nombre',
            'type' => 'text',
            'hidden' => false),
        'telefono' => array(
            'default' => '',
            'fname' => 'Telefono',
            'type' => 'text',
            'hidden' => false),
        'direccion' => array(
            'default' => '',
            'fname' => 'Direcci&oacute;n',
            'type' => 'text',
            'hidden' => false),
        'correo' => array(
            'default' => '',
            'fname' => 'Correo',
            'type' => 'text',
            'hidden' => false),
        'url' => array(
            'default' => '',
            'fname' => 'URL',
            'type' => 'text',
            'hidden' => false),
        'latitud' => array(
            'default' => 0.0,
            'fname' => 'Latitud',
            'type' => 'text',
            'hidden' => false),
        'longitud' => array(
            'default' => 0.0,
            'fname' => 'Longitud',
            'type' => 'text',
            'hidden' => false),
        'descripcion' => array(
            'default' => '',
            'fname' => 'Descripci&oacute;n',
            'type' => 'textarea',
            'hidden' => false),
        'observaciones' => array(
            'default' => '',
            'fname' => 'Observaciones',
            'type' => 'textarea',
            'hidden' => false),
        'referencia' => array(
            'default' => '',
            'fname' => 'Referencia',
            'type' => 'text',
            'hidden' => false)

//        '_id' => array(
//            'type' => 'relation',
//            'fname' => '',
//            'tip'  => '',
//            'values' => '__id_values'),
    );

    static function _region_id_values(){
        return values_from_class('Ciudad');
    }

    static function _ciudad_id_values(){
        return values_from_class('Ciudad');
    }

    function get__name(){
        return $this->nombre;
    }


    /**
     * Valida si se puede borrar esta instancia checando sus relaciones de
     * integridad referencias en los $has_many.
     * 
     * Deberia de ejecutarse en una transaccion.
     * Retorna true o false.
     */
    /*
    function _erasable(){
        $borrable = true;
        $profesores = count($this->profesores);
        $carreras = count($this->carreras);

        if ($profesores > 0 ||
            $carreras > 0)
            $borrable = false;


        return $borrable;
    }*/

    /*
    static function _tipo_values(){
    }
     */
}
?>
