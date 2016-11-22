<?php
class Sitio extends ActiveRecord\Model{
    static $table_name = 'sitio';

    static $has_many = array(
        array('sitiotgenerales', 'class_name' => 'Sitiotgeneral'),
        array('tgenerales',
            'class_name' => 'Tgeneral',
            'through' => 'sitiotgenerales',
            'fname' => 'Tipo General',
            'erasable_values' => '_tgenerales_values'),
        array('sitiotespecificos', 'class_name' => 'Sitiotespecifico'),
        array('tespecificos',
            'class_name' => 'Tespecifico',
            'through' => 'sitiotespecificos',
            'fname' => 'Tipo Espec&iacute;fico',
            'erasable_values' => '_tespecificos_values'),
        array('sitiopersonas', 'class_name' => 'Sitiopersona'),
        array('personas',
            'class_name' => 'Persona',
            'through' => 'sitiopersonas',
            'fname' => 'Personas',
            'erasable_values' => '_personas_values')

    );

    static $belongs_to = array(
        array('region'),
        array('ciudad')
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
        return values_from_class('Region');
    }

    static function _ciudad_id_values(){
        return values_from_class('Ciudad');
    }

    function get__name(){
        return $this->nombre;
    }


    function _tespecificos_values(){
        $vals = array();
        $pcs = $this->sitiotespecificos;

        foreach ($pcs as $pc){
            $vals[] = array($pc->id, $pc->tespecifico->_name);
        }
        return $vals;
    }

    function _tgenerales_values(){
        $vals = array();
        $pcs = $this->sitiotgenerales;

        foreach ($pcs as $pc){
            $vals[] = array($pc->id, $pc->tgeneral->_name);
        }
        return $vals;
    }


    function _personas_values(){
        $vals = array();
        $pcs = $this->sitiopersonas;

        foreach ($pcs as $pc){
            $vals[] = array($pc->id, $pc->persona->_name);
        }
        return $vals;
    }



}
?>
