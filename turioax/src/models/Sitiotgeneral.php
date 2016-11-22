<?php
class Sitiotgeneral extends ActiveRecord\Model{
    static $table_name = 'sitiotgeneral';

    static $belongs_to = array(
        array('sitio'),
        array('tgeneral')
    );

    static $order = array('sitio_id', 'tgeneral_id');
    // Fields metadata
    static $meta = array(
        'sitio_id' => array(
            'type' => 'relation',
            'fname' => 'Sitio',
            'tip'  => '',
            'values' => '_sitio_id_values'),
        'tgeneral_id' => array(
            'type' => 'relation',
            'fname' => 'Tipo General',
            'tip'  => '',
            'values' => '_tgeneral_id_values'),
        );

    function get__name(){
        return $this->nombre;
    }

    static function _sitio_id_values(){
        return values_from_class('Sitio');
    }

    static function _tgeneral_id_values(){
        return values_from_class('Tgeneral');
    }

}
?>
