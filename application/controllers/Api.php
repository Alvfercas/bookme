<?php
use chriskacerguis\RestServer\RestController;

class Api extends RestController {

    function __construct(){
        parent::__construct();

    }
    
    /**
     * MESA REST
     */
    function mesa_get(){
        try {
            
            $this->load->model('Mesa_model');

            if($idMesa = $this->get('id')){
                // Si no existe la mesa
                if(!$mesa = $this->Mesa_model->getRow($idMesa)){ throw new Exception('Mesa no encontrada', 404); }

                // Creamos la respuesta
                $response = [
                    'status' => 200,
                    'message' => 'Mesa encontrada',
                    'response' => $mesa
                ];

                return $this->response($response, 200);
    
            }else{
                $mesas = $this->Mesa_model->getRowsBy();

                // Creamos la respuesta
                $response = [
                    'status' => 200,
                    'message' => 'Mesas encontradas',
                    'response' => $mesas
                ];
    
                return $this->response($response, 200);
            }
        } catch (\Throwable $error) {
            // Creamos la respuesta
            $response = [
                'status' => $error->getCode(),
                'message' => $error->getMessage(),
                'response' => NULL
            ];

            return $this->response($response, $error->getCode());
        }
    }

    function mesa_post(){
        try {

            $this->load->model('Mesa_model');

            if(!$this->post('aforoMin')){ throw new Exception('Parámetro aforoMin no enviado', 400); }
            if(!$this->post('aforoMax')){ throw new Exception('Parámetro aforoMax no enviado', 400); }

            foreach ($this->post() as $field => $value) {
                $params[$field] = $value;
            }

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
    
                return $this->response($response, 200);
            }

        } catch (\Throwable $error) {
            // Creamos la respuesta
            $response = [
                'status' => $error->getCode(),
                'message' => $error->getMessage(),
                'response' => NULL
            ];

            return $this->response($response, $error->getCode());
        }

    }

    function mesa_put(){
        try {

            if(!$idMesa = $this->get('id')){ throw new Exception('Parámetro id no enviado', 400); }

            $this->load->model('Mesa_model');

            // Si no existe la mesa
            if(!$this->Mesa_model->getRow($idMesa)){ throw new Exception('Mesa no encontrada', 404); }

            if(!$this->put('aforoMin')){ throw new Exception('Parámetro aforoMin no enviado', 400); }
            if(!$this->put('aforoMax')){ throw new Exception('Parámetro aforoMax no enviado', 400); }

            foreach ($this->put() as $field => $value) {
                $params[$field] = $value;
            }

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
    
                return $this->response($response, 200);
            }

        } catch (\Throwable $error) {
            // Creamos la respuesta
            $response = [
                'status' => $error->getCode(),
                'message' => $error->getMessage(),
                'response' => NULL
            ];

            return $this->response($response, $error->getCode());
        }

    }

    function mesa_delete(){
        try {

            if(!$idMesa = $this->get('id')){ throw new Exception('Parámetro id no enviado', 400); }

            $this->load->model('Mesa_model');

            // Si no existe la mesa
            if(!$this->Mesa_model->getRow($idMesa)){ throw new Exception('Mesa no encontrada', 404); }

            // Si se actualiza
            if($this->Mesa_model->deleteRow($idMesa)){

                // Creamos la respuesta
                $response = [
                    'status' => 200,
                    'message' => 'Mesa borrada correctamente',
                    'response' => NULL
                ];
    
                return $this->response($response, 200);
            }

        } catch (\Throwable $error) {
            // Creamos la respuesta
            $response = [
                'status' => $error->getCode(),
                'message' => $error->getMessage(),
                'response' => NULL
            ];

            return $this->response($response, $error->getCode());
        }

    }

    /**
     * RESERVA REST
     */
    function reserva_get(){
        try {

            $this->load->model('Reserva_model');

            if($idReserva = $this->get('id')){
                // Si no existe la mesa
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

                return $this->response($response, 200);
    
            }else{
                
                // Obtenemos las reservas, pudiendo pasarle como filtro adicional, el idMesa
                $reservasTmp = $this->Reserva_model->getRowsBy(['idMesa' => $this->get('mesa') ?? '']);

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
    
                return $this->response($response, 200);
            }
        } catch (\Throwable $error) {
            // Creamos la respuesta
            $response = [
                'status' => $error->getCode(),
                'message' => $error->getMessage(),
                'response' => NULL
            ];

            return $this->response($response, $error->getCode());
        }
    }

    function reserva_post(){
        try {

            $this->load->model('Reserva_model');

            if(!$this->post('idMesa')){ throw new Exception('Parámetro idMesa no enviado', 400); }
            if(!$this->post('fecha')){ throw new Exception('Parámetro fecha no enviado', 400); }
            if(!$this->post('comensales')){ throw new Exception('Parámetro comensales no enviado', 400); }
            if(!$this->post('nombreReserva')){ throw new Exception('Parámetro nombreReserva no enviado', 400); }

            foreach ($this->post() as $field => $value) {
                $params[$field] = $value;
            }

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
    
                return $this->response($response, 200);
            }

        } catch (\Throwable $error) {
            // Creamos la respuesta
            $response = [
                'status' => $error->getCode(),
                'message' => $error->getMessage(),
                'response' => NULL
            ];

            return $this->response($response, $error->getCode());
        }
    }

    function reserva_put(){
        try {

            if(!$idReserva = $this->get('id')){ throw new Exception('Parámetro id no enviado', 400); }

            $this->load->model('Reserva_model');

            // Si no existe la mesa
            if(!$reserva = $this->Reserva_model->getRow($idReserva)){ throw new Exception('Reserva no encontrada', 404); }

            if(!$this->put('idMesa')){ throw new Exception('Parámetro idMesa no enviado', 400); }
            if(!$this->put('fecha')){ throw new Exception('Parámetro fecha no enviado', 400); }
            if(!$this->put('comensales')){ throw new Exception('Parámetro comensales no enviado', 400); }
            if(!$this->put('nombreReserva')){ throw new Exception('Parámetro nombreReserva no enviado', 400); }

            $params['idReserva'] = $reserva->idReserva;
            foreach ($this->put() as $field => $value) {
                $params[$field] = $value;
            }

            // Comprobamos que los parámetros estén correctos
            $this->Reserva_model->checkParams($params);

            // Parseamos la fecha para guardarla en base de datos
            $params['fecha'] = date('Y-m-d', strtotime($params['fecha']));

            // Si se actualiza
            if($this->Reserva_model->updateRow($idReserva, $params)){

                $this->Reserva_model->getRow($idReserva);

                // Creamos la respuesta
                $response = [
                    'status' => 200,
                    'message' => 'Reserva actualizada correctamente',
                    'response' =>  [ // Devolvemos sólo el identificador y el código de reserva para usar al gusto
                        'idReserva' => $reserva->idReserva,
                        'codigoReserva' => $reserva->codigoReserva
                    ]
                ];
    
                return $this->response($response, 200);
            }

        } catch (\Throwable $error) {
            // Creamos la respuesta
            $response = [
                'status' => $error->getCode(),
                'message' => $error->getMessage(),
                'response' => NULL
            ];

            return $this->response($response, $error->getCode());
        }
    }

    function reserva_delete(){
        try {

            if(!$idReserva = $this->get('id')){ throw new Exception('Parámetro id no enviado', 400); }

            $this->load->model('Reserva_model');

            // Si no existe la mesa
            if(!$this->Reserva_model->getRow($idReserva)){ throw new Exception('Reserva no encontrada', 404); }

            // Si se actualiza
            if($this->Reserva_model->deleteRow($idReserva)){

                // Creamos la respuesta
                $response = [
                    'status' => 200,
                    'message' => 'Reserva borrada correctamente',
                    'response' => NULL
                ];
    
                return $this->response($response, 200);
            }

        } catch (\Throwable $error) {
            // Creamos la respuesta
            $response = [
                'status' => $error->getCode(),
                'message' => $error->getMessage(),
                'response' => NULL
            ];

            return $this->response($response, $error->getCode());
        }
    }

    function disponibilidad_get(){
        try {

            if(!$params['fecha'] = $this->get('fecha')){ throw new Exception('Parámetro fecha no enviado', 400); }
            if(!$params['comensales'] = $this->get('comensales')){ throw new Exception('Parámetro comensales no enviado', 400); }

            $this->load->model('Reserva_model');
            
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

            return $this->response($response, 200);
            
        } catch (\Throwable $error) {
            // Creamos la respuesta
            $response = [
                'status' => $error->getCode(),
                'message' => $error->getMessage(),
                'response' => NULL
            ];
            
            return $this->response($response, $error->getCode());
        }
    }
}