<?php
class Tgeneral extends ActiveRecord\Model{
    static $table_name = 'tgeneral';

    static $has_many = array(
        array('sitiotgenerales', 'class_name' => 'Sitiotgeneral')
    );

    static $belongs_to = array(
    );

    static $validates_presence_of = array(
        array('nombre', 'message' => VPRESENTE));

    static $validates_uniqueness_of = array(
        array('nombre', 'message' => VUNICO)
    );

    static $order = array('nombre');
    static $meta = array(
        'nombre' => array(
            'autofocus' => true,
            'type' => 'text',
            'hidden' => false)
        );

    function get__name(){
        return $this->nombre;
    }

}
?>

