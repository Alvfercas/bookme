<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Mesa extends CI_Controller {

	public function __construct() {
        parent::__construct();
        $this->load->model('Mesa_model');
    }

    /**
     * Método para obtener la mesa, si existe.
     * 
     * @param Int $idMesa Identificador del objecto
     */
    public function get(int $idMesa = NULL){
        try {

            // Si viene el identificador de mesa
            if($idMesa){
                // Si no existe la mesa
                if(!$mesa = $this->Mesa_model->getRow($idMesa)){ throw new Exception('Mesa no encontrada', 404); }

                // Creamos la respuesta
                $response = [
                    'status' => 200,
                    'message' => 'Mesa encontrada',
                    'response' => $mesa
                ];
    
                return returnJSON($response);
            }else{
                $mesas = $this->Mesa_model->getRowsBy();

                // Creamos la respuesta
                $response = [
                    'status' => 200,
                    'message' => 'Mesas encontradas',
                    'response' => $mesas
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
     * Método para crear una mesa, se necesitan dos parámetros enviados por POST
     * 
     * @param Int $_POST['aforoMin'] Aforo mínimo de la mesa
     * @param Int $_POST['aforoMax'] Aforo máximo de la mesa
     */
    public function post(){
        try {
            // En vez de crearlo directamente, vendría por POST
            $params = [
                'aforoMin' => '1',
                'aforoMax' => '2'
            ];

            if(!isset($params['aforoMin'])){ throw new Exception('Parámetro aforoMin no enviado', 400); }
            if(!isset($params['aforoMax'])){ throw new Exception('Parámetro aforoMax no enviado', 400); }

            // Comprobamos que los parámetros estén correctos
            $this->Mesa_model->checkParams($params);

            // Si se inserta
            if($idMesa = $this->Mesa_model->addRow($params)){

                // Creamos la respuesta
                $response = [
                    'status' => 200,
                    'message' => 'Mesa creada correctamente',
                    'response' => $this->Mesa_model->getRow($idMesa)
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
     * Método para editar/actualizar una mesa, se necesitan dos parámetros enviados por POST y el identificador de la mesa.
     * 
     * @param Int $idMesa Identificador del objeto
     * @param Int $_POST['aforoMin'] Aforo mínimo de la mesa
     * @param Int $_POST['aforoMax'] Aforo máximo de la mesa
     */
    public function put($idMesa = NULL){
        try {
            // Comprobamos que el identificador haya sido enviado
            if(!$idMesa){ throw new Exception('Parámetro id no enviado', 400); }

            // Si no existe la mesa
            if(!$mesa = $this->Mesa_model->getRow($idMesa)){ throw new Exception('Mesa no encontrada', 404); }

            // En vez de crearlo directamente, vendría por POST
            $params = [
                'aforoMin' => '3',
                'aforoMax' => '4'
            ];

            if(!isset($params['aforoMin'])){ throw new Exception('Parámetro aforoMin no enviado', 400); }
            if(!isset($params['aforoMax'])){ throw new Exception('Parámetro aforoMax no enviado', 400); }

            // Comprobamos que los parámetros estén correctos
            $this->Mesa_model->checkParams($params);

            // Si se actualiza
            if($this->Mesa_model->updateRow($idMesa, $params)){

                // Creamos la respuesta
                $response = [
                    'status' => 200,
                    'message' => 'Mesa actualizada correctamente',
                    'response' => $this->Mesa_model->getRow($idMesa)
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
     * Método para eliminar una mesa.
     * 
     * @param Int $idMesa Identificador del objeto
     */
    public function del($idMesa = NULL){
        try {
            // Comprobamos que el identificador haya sido enviado
            if(!$idMesa){ throw new Exception('Parámetro id no enviado', 400); }

            // Si no existe la mesa
            if(!$mesa = $this->Mesa_model->getRow($idMesa)){ throw new Exception('Mesa no encontrada', 404); }

            // Si se actualiza
            if($this->Mesa_model->deleteRow($idMesa)){

                // Creamos la respuesta
                $response = [
                    'status' => 200,
                    'message' => 'Mesa borrada correctamente',
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
}
