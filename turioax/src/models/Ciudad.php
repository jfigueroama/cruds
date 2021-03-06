<?php
class Ciudad extends ActiveRecord\Model{
    static $table_name = 'ciudad';

    static $has_many = array(
    );

    static $belongs_to = array(
        array('estado')
    );

    static $validates_presence_of = array(
        array('nombre', 'message' => VPRESENTE));

    static $validates_uniqueness_of = array(
        array('nombre', 'message' => VUNICO)
    );

    static $order = array('estado_id', 'nombre');
    // Fields metadata
    static $meta = array(
        'estado_id' => array(
            'type' => 'relation',
            'fname' => 'Estado',
            'tip'  => 'Estado al que pertenece la ciudad.',
            'values' => '_estado_id_values'),

        'nombre' => array(
            'autofocus' => true,
            'type' => 'text',
            'hidden' => false)
        );

    static function _estado_id_values(){
        return values_from_class('Estado');
    }


    function get__name(){
        return $this->nombre;
    }

}
?>

