<?php
class Horario extends ActiveRecord\Model{
    static $table_name = 'horario';

    static $belongs_to = array(
        array('temporada')
    );

    static $has_many = array(
    );

    static $order = array('temporada_id', 'dias', 'horas');
    static $meta = array(
        'dias' => array(
            'default' => '',
            'autofocus' => true,
            'type' => 'text',
            'fname' => 'Dias',
            'tip'  => 'Iniciales de dias de la semana separadas por comas. Ej: L M X J V S D',
            'hidden' => false),
        'horas' => array(
            'type' => 'text',
            'fname' => "Horas",
            'tip'   => 'Horas separadas por espacios (24H). Ej: 8 10 15 20',
            'hidden' => false),
        'temporada_id' => array(
            'type' => 'relation',
            'fname' => 'Temporada',
            'tip'  => '',
            'values' => '_temporada_id_values')
        );

    function get__name(){
        return $this->nombre;
    }

    static function _temporada_id_values(){
        return values_from_class('Temporada');
    }


}
?>
