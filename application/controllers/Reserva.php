<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Reserva extends CI_Controller {

	public function __construct() {
        parent::__construct();
        $this->load->model('Reserva_model');
    }

    /**
     * Método para obtener la reserva, si existe.
     * 
     * @param Int $idReserva Identificador del objecto
     */
    public function get(int $idReserva = NULL, int $idMesa = NULL){
        try {

            // Si viene el identificador de reserva
            if($idReserva){
                // Si no existe la reserva
                if(!$reserva = $this->Reserva_model->getRow($idReserva)){ throw new Exception('Reserva no encontrada', 404); }

                // Creamos la respuesta
                $response = [
                    'status' => 200,
                    'message' => 'Reserva encontrada',
                    'response' => [ // Sólo devolvemos parámetros relevantes
                        'idReserva' => $reserva->idReserva,
                        'idMesa' => $reserva->idMesa,
                        'fecha' => date('d-m-Y', strtotime($reserva->fecha)),
                        'comensales' => $reserva->comensales,
                        'nombreReserva' => $reserva->nombreReserva,
                        'codigoReserva' => $reserva->codigoReserva
                    ]
                ];
    
                return returnJSON($response);
            }else{
                // Obtenemos las reservas, pudiendo pasarle como filtro adicional, el idMesa
                $reservasTmp = $this->Reserva_model->getRowsBy(['idMesa' => $idMesa ?? '']);

                foreach ($reservasTmp as $reserva) {
                    $reservas[] = [
                        'idReserva' => $reserva->idReserva,
                        'idMesa' => $reserva->idMesa,
                        'fecha' => date('d-m-Y', strtotime($reserva->fecha)),
                        'comensales' => $reserva->comensales,
                        'nombreReserva' => $reserva->nombreReserva,
                        'codigoReserva' => $reserva->codigoReserva
                    ];
                }

                // Creamos la respuesta
                $response = [
                    'status' => 200,
                    'message' => 'Reservas encontradas',
                    'response' => $reservas
                ];
    
                return returnJSON($response);
            }

        } catch (\Throwable $error) {
            // Creamos la respuesta
            $response = [
                'status' => $error->getCode(),
                'message' => $error->getMessage(),
                'response' => NULL
            ];

            return returnJSON($response);
        }
    }
    
    /**
     * Método para crear una reserva, se necesitan dos parámetros enviados por POST
     * 
     * @param Int $_POST['idMesa'] Identificador de la mesa a hacer la reserva
     * @param String $_POST['fecha'] Fecha de la reserva. Formato dd-mm-yyyy
     * @param Int $_POST['comensales'] Número de comensales para la reserva
     * @param String $_POST['nombreReserva'] Nombre del que hace la reserva
     */
    public function post(){
        try {
            // En vez de crearlo directamente, vendría por POST
            $params = [
                'idMesa' => 1,
                'fecha' => date('01-12-2020'),
                'comensales' => 2,
                'nombreReserva' => 'Álvaro Fernández Cascajosa'
            ];

            if(!isset($params['idMesa'])){ throw new Exception('Parámetro idMesa no enviado', 400); }
            if(!isset($params['fecha'])){ throw new Exception('Parámetro fecha no enviado', 400); }
            if(!isset($params['comensales'])){ throw new Exception('Parámetro comensales no enviado', 400); }
            if(!isset($params['nombreReserva'])){ throw new Exception('Parámetro nombreReserva no enviado', 400); }

            // Generamos un código de reserva
            $params['codigoReserva'] = $this->Reserva_model->generateCode();

            // Comprobamos que los parámetros estén correctos
            $this->Reserva_model->checkParams($params);

            // Parseamos la fecha para guardarla en base de datos
            $params['fecha'] = date('Y-m-d', strtotime($params['fecha']));

            // Si se inserta
            if($idReserva = $this->Reserva_model->addRow($params)){

                $reserva = $this->Reserva_model->getRow($idReserva);

                // Creamos la respuesta
                $response = [
                    'status' => 200,
                    'message' => 'Reserva creada correctamente',
                    'response' =>  [ // Devolvemos sólo el identificador y el código de reserva para usar al gusto
                        'idReserva' => $reserva->idReserva,
                        'codigoReserva' => $reserva->codigoReserva
                    ]
                ];
    
                return returnJSON($response);
            }
        } catch (\Throwable $error) {
            // Creamos la respuesta
            $response = [
                'status' => $error->getCode(),
                'message' => $error->getMessage(),
                'response' => NULL
            ];

            return returnJSON($response);
        }
    }

    /**
     * Método para editar/actualizar una reserva, se necesitan dos parámetros enviados por POST y el identificador de la reserva.
     * 
     * @param Int $idReserva Identificador del objeto
     * @param Int $_POST['idMesa'] Identificador de la mesa a hacer la reserva
     * @param String $_POST['fecha'] Fecha de la reserva. Formato dd-mm-yyyy
     * @param Int $_POST['comensales'] Número de comensales para la reserva
     * @param String $_POST['nombreReserva'] Nombre del que hace la reserva
     */
    public function put($idReserva = NULL){
        try {
            // Comprobamos que el identificador haya sido enviado
            if(!$idReserva){ throw new Exception('Parámetro id no enviado', 400); }

            // Si no existe la reserva
            if(!$reserva = $this->Reserva_model->getRow($idReserva)){ throw new Exception('Reserva no encontrada', 404); }

            // En vez de crearlo directamente, vendría por POST
            $params = [
                'idReserva' => $reserva->idReserva,
                'idMesa' => 1,
                'fecha' => date('01-12-2020'),
                'comensales' => 1,
                'nombreReserva' => 'Álvaro Fernández Cascajosa',
                'codigoReserva' => $reserva->codigoReserva
            ];

            if(!isset($params['idMesa'])){ throw new Exception('Parámetro idMesa no enviado', 400); }
            if(!isset($params['fecha'])){ throw new Exception('Parámetro fecha no enviado', 400); }
            if(!isset($params['comensales'])){ throw new Exception('Parámetro comensales no enviado', 400); }
            if(!isset($params['nombreReserva'])){ throw new Exception('Parámetro nombreReserva no enviado', 400); }

            // Comprobamos que los parámetros estén correctos
            $this->Reserva_model->checkParams($params);

            // Parseamos la fecha para guardarla en base de datos
            $params['fecha'] = date('Y-m-d', strtotime($params['fecha']));

            // Si se actualiza
            if($this->Reserva_model->updateRow($idReserva, $params)){

                $reserva = $this->Reserva_model->getRow($idReserva);

                // Creamos la respuesta
                $response = [
                    'status' => 200,
                    'message' => 'Reserva actualizada correctamente',
                    'response' =>  [ // Devolvemos sólo el identificador y el código de reserva para usar al gusto
                        'idReserva' => $reserva->idReserva,
                        'codigoReserva' => $reserva->codigoReserva
                    ]
                ];
    
                return returnJSON($response);
            }
            
        } catch (\Throwable $error) {
            // Creamos la respuesta
            $response = [
                'status' => $error->getCode(),
                'message' => $error->getMessage(),
                'response' => NULL
            ];
            
            return returnJSON($response);
        }
    }

    /**
     * Método para eliminar una reserva.
     * 
     * @param Int $idReserva Identificador del objeto
     */
    public function del($idReserva = NULL){
        try {
            // Comprobamos que el identificador haya sido enviado
            if(!$idReserva){ throw new Exception('Parámetro id no enviado', 400); }

            // Si no existe la reserva
            if(!$reserva = $this->Reserva_model->getRow($idReserva)){ throw new Exception('Reserva no encontrada', 404); }

            // Si se actualiza
            if($this->Reserva_model->deleteRow($idReserva)){

                // Creamos la respuesta
                $response = [
                    'status' => 200,
                    'message' => 'Reserva borrada correctamente',
                    'response' => NULL
                ];
    
                return returnJSON($response);
            }
            
        } catch (\Throwable $error) {
            // Creamos la respuesta
            $response = [
                'status' => $error->getCode(),
                'message' => $error->getMessage(),
                'response' => NULL
            ];
            
            return returnJSON($response);
        }
    }
    
    /**
     * Método para obtener la disponibilidad de las mesas en una fecha y comensales concretos
     * 
     * @param String $_POST['fecha'] Fecha para buscar disponibilidad
     * @param Int $_POST['comensales'] Número de comensales para optar a la reserva
     */
    public function disponibilidad($fecha = NULL, $comensales = 0){
        try {

            $params = [
                'fecha' => $fecha,
                'comensales' => $comensales
            ];

            if(!isset($params['fecha'])){ throw new Exception('Parámetro fecha no enviado', 400); }
            if(!isset($params['comensales'])){ throw new Exception('Parámetro comensales no enviado', 400); }
            
            // Comprobamos que los parámetros estén correctos
            $this->Reserva_model->checkParams($params);

            // Parseamos la fecha para guardarla en base de datos
            $fecha = date('Y-m-d', strtotime($params['fecha']));

            // Primero obtenemos las mesas que están creadas
            $this->load->model('Mesa_model');
            $mesas = $this->Mesa_model->getRowsBy();

            // Buscamos para cada mesa, si está fisponible en la fecha
            foreach ($mesas as $index => $mesa) {
                // Si hay una reserva para esa mesa o supera el aforo máximo de la mesa, la quitamos
                if($this->Reserva_model->getRowsBy(['idMesa' => $mesa->idMesa, 'fecha' => $fecha]) || $params['comensales'] > $mesa->aforoMax){
                    unset($mesas[$index]);
                }
            }

            // Reordenamos el array después de quitar las mesas que no están disponibles
            $mesas = array_values($mesas);

            // Creamos la respuesta
            $response = [
                'status' => 200,
                'message' => 'Mesas disponibles',
                'response' => $mesas
            ];

            return returnJSON($response);
            
        } catch (\Throwable $error) {
            // Creamos la respuesta
            $response = [
                'status' => $error->getCode(),
                'message' => $error->getMessage(),
                'response' => NULL
            ];
            
            return returnJSON($response);
        }
    }
}
