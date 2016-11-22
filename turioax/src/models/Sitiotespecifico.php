<?php
class Sitiotespecifico extends ActiveRecord\Model{
    static $table_name = 'sitiotespecifico';

    static $belongs_to = array(
        array('sitio'),
        array('tespecifico')
    );

    static $order = array('sitio_id', 'tespecifico_id');
    // Fields metadata
    static $meta = array(
        'sitio_id' => array(
            'type' => 'relation',
            'fname' => 'Sitio',
            'tip'  => '',
            'values' => '_sitio_id_values'),
        'tespecifico_id' => array(
            'type' => 'relation',
            'fname' => 'Tipo Espec&iacute;fico',
            'tip'  => '',
            'values' => '_tespecifico_id_values'),
        );

    function get__name(){
        return $this->nombre;
    }

    static function _sitio_id_values(){
        return values_from_class('Sitio');
    }

    static function _tespecifico_id_values(){
        return values_from_class('Tespecifico');
    }

}
?>
