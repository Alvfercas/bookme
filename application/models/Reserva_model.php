<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Reserva_model extends MY_Model {

    protected $table = 'reserva'; // Nombre de la tabla en la base de datos
    protected $id = 'idReserva'; // Identificador único en la tabla

    // Campos de la tabla en la base de datos
    protected $fields = [
        'idReserva',
        'idMesa',
        'fecha',
        'comensales',
        'nombreReserva',
        'codigoReserva',
        'iv'
    ];

    // Campos que se pueden añadir o editar en la base de datos
    protected $allowedFields = [
        'idMesa',
        'fecha',
        'comensales',
        'nombreReserva',
        'codigoReserva',
        'iv'
    ];

    // Campos que no se pueden modificar una vez insertados
    protected $protectedFields = [
        'idReserva',
        'idMesa',
        'iv'
    ];

    /* He optado por cifrar el nombre de la reserva para que no quede reflejado en la base de datos
    * Bajo el index 'fields' estará el array de campos cifrados en base de datos
    * En el index 'iv' estará el nombre del campo que se usará para guardar el vector de inicialización para el cifrado
    */
    protected $encrypted = [
        'fields' => [
            'nombreReserva'
        ],
        'iv' => 'iv'
    ];

    /**
     * MÉTODOS
     */

    /**
     * Función para crear códigos de reserva
     */
    public function generateCode() : string{
        // Se genera un código aleatorio
        $code = generateRandomString(6);

        // Si ya existe ese código en la base de datos y no es único, volvemos a llamar a la función
        if($reserva = $this->Reserva_model->getRowsBy(['codigoReserva' => $code])){
            return $this->generateCode();
        }

        return $code;
    }

    /**
     * Función para comprobar que los parámetros llevan un formato y valores válidos
     * 
     * @param Array $params Parámetros que se van a insertar en la base de datos
     */
    public function checkParams(array $params = []) : bool{
        // Si no existe la mesa
        $this->load->model('Mesa_model');
        if(isset($params['idMesa']) && !$mesa = $this->Mesa_model->getRow($params['idMesa'])){
            throw new Exception('Mesa no encontrada', 400);
        }
        
        // Si el formato de fechas no es válido
        if(isset($params['fecha']) && !validateDate($params['fecha'], 'd-m-Y')){
            throw new Exception('Formato de fecha no válida', 400);
        }

        // Si el número de comensales es igual o menor que 0
        if(isset($params['comensales']) && $params['comensales'] <= 0){
            throw new Exception('El número de comensales no puede ser igual o menor que 0', 400);
        }

        // Si el número de comensales es mayor que el aforo máximo de la mesa
        if(isset($params['comensales']) && $mesa && $params['comensales'] > $mesa->aforoMax){
            throw new Exception('El número de comensales no puede ser mayor que el aforo máximo de la mesa', 400);
        }

        // Si en el tiempo de la petición se ha creado una reserva. No impide que se actualice la misma reserva en el caso de querer cambiar sólo comensales
        if(isset($params['idReserva']) && $reservas = $this->Reserva_model->getRowsBy(['fecha' => date('Y-m-d', strtotime($params['fecha'])), 'idMesa' => $mesa->idMesa])){
            foreach ($reservas as $reserva) {
                if($reserva->idReserva != $params['idReserva']){
                    throw new Exception('La mesa ya ha sido reservada para esta fecha', 400);
                }
            }
        }

        return true;
    }
}