<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Model extends CI_Model {

    public function __construct() {
        // Comprobaciones de que en el modelo está configurado correctamente al menos lo esencial
        if(!isset($this->table)){
            throw new Exception('Parámetro protected $table no está definido en el modelo', 500);
        }
        if(!isset($this->id)){
            throw new Exception('Parámetro protected $id no está definido en el modelo', 500);
        }
        if(!isset($this->fields)){
            throw new Exception('parámetro protected $fields no está definido en el modelo', 500);
        }
        if(!isset($this->allowedFields)){
            throw new Exception('Parámetro protected $allowedFields no está definido en el modelo', 500);
        }
        if(!isset($this->protectedFields)){
            throw new Exception('Parámetro protected $protectedFields no está definido en el modelo', 500);
        }
    }

    /**
     * Función para insertar en la base de datos. Devuelve el identificador de la inserción.
     * 
     * @param Array $params Parámetros que se van a insertar en la base de datos
     */
    public function addRow(array $params = []) : int{
        // Comprobamos si el modelo tiene campos para cifrar
        if(isset($this->encrypted)){
            // Generamos 16 bytes y lo pasamos a base64 para guardarlos en la base de datos
            $iv = $this->security->get_random_bytes(16);
            $params['iv'] = base64_encode($iv);
        }

        foreach ($params as $field => $value) {
            // Comprobamos que el campo tenga permitida la inserción
            if(in_array($field, $this->allowedFields)){
                // Si el campo hay que cifrarlo
                if(isset($this->encrypted) && in_array($field, $this->encrypted['fields'])){
                    $this->db->set($field, 'AES_ENCRYPT("'.$value.'", "'.hash('sha512', config_item('encryption_key')).'", '.$this->db->escape($iv).')', FALSE);
                    unset($params[$field]);
                }
            }else{
                // Si no tiene permitida inserción, lo quitamos
                unset($params[$field]);
            }
        }

        // Insertamos en base de datos y devolvemos el identificador
        $this->db->insert($this->table, $params);
        return $this->db->insert_id();
    }

    /**
     * Función para actualizar en la base de datos un registro. Devuelve TRUE si ha completado la operación o FALSE si hay algún problema.
     * 
     * @param Int $id Identificador del registro que se va a actualizar
     * @param Array $params Parámetros que se van a actualizar en la base de datos
     */
    public function updateRow(int $id = NULL, array $params = []) : bool{
        // Comprobamos que se haya pasado un identificador válido
        if(!$id || $id <= 0){
            throw new Exception('El identificador no es correcto', 500);
        }

        foreach ($params as $field => $value) {
            // Comprobamos que el campo tenga permitida la edición
            if(in_array($field, $this->allowedFields)){
                // Quitamos aquellos campos que una vez insertados no se deben modificar
                if(in_array($field, $this->protectedFields)){
                    unset($params[$field]);
                    continue;
                }
                // Si el campo hay que cifrarlo
                if(isset($this->encrypted) && in_array($field, $this->encrypted['fields'])){
                    $iv = 'FROM_BASE64('.$this->encrypted['iv'].')';
                    $this->db->set($field, 'AES_ENCRYPT("'.$value.'", "'.hash('sha512', config_item('encryption_key')).'", '.$iv.')', FALSE);
                    unset($params[$field]);
                }
            }else{
                // Si no tiene permitida edición, lo quitamos
                unset($params[$field]);
            }
        }
        
        return $this->db->where($this->id, $id)->update($this->table, $params);
    }

    /**
     * Función para obtener un registro por su identificador. Devuelve, si existe, el objeto.
     * 
     * @param Int $id Identificador del registro que se quiere obtener
     */
    public function getRow(int $id = NULL) : ?object{
        // Comprobamos que se haya pasado un identificador válido
        if(!$id || $id <= 0){
            throw new Exception('El identificador no es correcto', 500);
        }
        
        return $this->getRowsBy([$this->id => $id]);
    }

    /**
     * Función para obtener registros en base a unos parámetros.
     * 
     * @param Int $id Identificador del registro que se va a actualizar
     * @param Array $params Parámetros que se van a actualizar en la base de datos
     */
    public function getRowsBy(array $params = []){
        // Hacemos select de cada uno de los campos definidos en el modelo
        foreach ($this->fields as $field) {

            // Si el campo viene cifrado
            if(isset($this->encrypted) && in_array($field, $this->encrypted['fields'])){
                $this->db->select('AES_DECRYPT('.$field.', "'.hash('sha512', config_item('encryption_key')).'", FROM_BASE64('.$this->encrypted['iv'].')) AS '.$field);
            }else{
                $this->db->select($field);
            }
        }

        $this->db->from($this->table);

        // Si el array de parametros no viene vacío
        if($params){
            // Si lo vamos a obtener por el id, devolvemos el objeto único
            if(array_key_exists($this->id, $params)){
                $this->db->where($this->id, $params[$this->id]);
                return $this->db->get()->row();
            }
            foreach ($params as $field => $value) {
                // Por cada campo, comprobamos que exista como parámetro en el modelo. Para no hacer un like o where de un campo no válido
                if(in_array($field, $this->fields)){
                    // Si el campo es cifrado
                    if(in_array($field, $this->encrypted['fields'])){
                        $this->db->where('CAST(AES_DECRYPT('.$field.', "'.hash('sha512', config_item('encryption_key')).'", FROM_BASE64('.$this->encrypted['iv'].')) AS CHARACTER) LIKE "%'.$value.'%"');
                    }else{
                        $this->db->like($field, $value);
                    }
                }
            }
        }

        $objects = $this->db->get()->result();

        return $objects;
    }

    /**
     * Función para eliminar un registro de la base de datos.
     * 
     * @param Int $id Identificador del registro que se va a eliminar
     */
    public function deleteRow(int $id = NULL) : bool{
        // Comprobamos que se haya pasado un identificador válido
        if(!$id || $id <= 0){
            throw new Exception('El identificador no es correcto', 500);
        }

        $this->db->from($this->table);

        return $this->db->where($this->id, $id)->delete();
    }
}