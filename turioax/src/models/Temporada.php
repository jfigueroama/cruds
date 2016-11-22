<?php
class Temporada extends ActiveRecord\Model{
    static $table_name = 'temporada';

    static $belongs_to = array(
        array('sitio')
    );

    static $has_many = array(
        array('horarios', 'class_name' => 'Horario')
    );

    static $validates_presence_of = array(
        array('nombre', 'message' => VPRESENTE));

    static $validates_uniqueness_of = array(
        array('nombre', 'message' => VUNICO)
    );

    static $order = array('sitio_id', 'nombre');
    // Fields metadata
    static $meta = array(
        'nombre' => array(
            'autofocus' => true,
            'type' => 'text',
            'hidden' => false),
        'sitio_id' => array(
            'type' => 'relation',
            'fname' => 'Sitio',
            'tip'  => '',
            'values' => '_sitio_id_values')
        );

    function get__name(){
        return $this->nombre;
    }

    static function _sitio_id_values(){
        return values_from_class('Sitio');
    }


}
?>
