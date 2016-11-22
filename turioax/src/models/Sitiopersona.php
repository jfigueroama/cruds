<?php
class Sitiopersona extends ActiveRecord\Model{
    static $table_name = 'sitiopersona';

    static $belongs_to = array(
        array('sitio'),
        array('persona')
    );

    static $order = array('sitio_id', 'persona_id');
    // Fields metadata
    static $meta = array(
        'sitio_id' => array(
            'type' => 'relation',
            'fname' => 'Sitio',
            'tip'  => '',
            'values' => '_sitio_id_values'),
        'persona_id' => array(
            'type' => 'relation',
            'fname' => 'Persona',
            'tip'  => '',
            'values' => '_persona_id_values'),
        );

    function get__name(){
        return $this->nombre;
    }

    static function _sitio_id_values(){
        return values_from_class('Sitio');
    }

    static function _persona_id_values(){
        return values_from_class('Persona');
    }

}
?>
