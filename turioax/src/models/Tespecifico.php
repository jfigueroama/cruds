<?php
<?php
class Tespecifico extends ActiveRecord\Model{
    static $table_name = 'tespecifico';

    static $has_many = array(
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

