<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Mesa_model extends MY_Model {

    protected $table = 'mesa'; // Nombre de la tabla en la base de datos
    protected $id = 'idMesa'; // Identificador único en la tabla

    // Campos de la tabla en la base de datos
    protected $fields = [
        'idMesa',
        'aforoMin',
        'aforoMax'
    ];

    // Campos que se pueden añadir o editar en la base de datos
    protected $allowedFields = [
        'aforoMin',
        'aforoMax'
    ];

    // Campos que no se pueden modificar una vez insertados
    protected $protectedFields = [
        'idMesa'
    ];

    /**
     * MÉTODOS
     */

    /**
     * Función para comprobar que los parámetros llevan un formato y valores válidos.
     * 
     * @param Array $params Parámetros que se van a insertar en la base de datos
     */
    public function checkParams(array $params = []) : bool{
        // Si el aforo mínimo es menor o igual que 0
        if($params['aforoMin'] <= 0){
            throw new Exception('El aforo mínimo no puede ser igual o menor que 0', 400);
        }
        // Si el aforo máximo es menor o igual que 0
        if($params['aforoMax'] <= 0){
            throw new Exception('El aforo máximo no puede ser igual o menor que 0', 400);
        }

        // Si el aforo máximo es menor que el aforo mínimo
        if($params['aforoMax'] <= $params['aforoMin']){
            throw new Exception('El aforo máximo no puede ser menor o igual que el aforoMin', 400);
        }

        return true;
    }
}