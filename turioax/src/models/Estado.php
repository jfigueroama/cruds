<?php
class Sample extends ActiveRecord\Model{
    static $table_name = 'sample';

    static $has_many = array(
        array('carreras', 'class_name' => 'Carrera'),
        array('profesores', 'class_name' => 'Profesor')
    );

    static $belongs_to = array(
        array('institucion')
    );

    static $validates_presence_of = array(
        array('nombre', 'message' => VPRESENTE));// Puede ser 'Debe proporcionar un valor'

    static $validates_uniqueness_of = array(
        array('nombre', 'message' => VUNICO)    // Puede ser 'Debe ser unico'
    );

    static $order = array('nombre', 'instituto_id', 'tipo', 'color');   // Orden de los campos y 
                                                                        // y si aparecen o no.

    // Fields metadata
    static $meta = array(
        'nombre' => array(
            'autofocus' => true,
            'type' => 'text',
            'hidden' => false),
        'tipo' => array(
            'values' => array(
                array(1, 'Lic'),
                array(2, 'Maestria'),
                array(3, 'Docto')),
            //'values' => '_tipo_values',    // Ver metodo _tipo_values
            'fname' => 'Tipo de Carrera',   // Friendly name
            'type' => 'text'),
        'instituto_id' => array(
            'type' => 'relation',
            'fname' => 'Instituto!!!',
            'tip'  => 'Seleccione el instituto al que pertenece la carrera',
            'values' => '_instituto_id_values')

//        '_id' => array(
//            'type' => 'relation',
//            'fname' => '',
//            'tip'  => '',
//            'values' => '__id_values'),
    );

    static function _instituto_id_values(){
        $values = array();
        $ins = Instituto::find('all');
        foreach ($ins as $in){
            $values[] = array($in->id, $in->nombre);
        }
        return $values;
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
