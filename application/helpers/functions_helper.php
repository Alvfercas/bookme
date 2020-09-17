<?php defined('BASEPATH') OR exit('No direct script access allowed');

// Funciones adicionales

function pre($arr){
    echo '<pre>';
    print_r($arr);
    echo '</pre>';
}

// Para validar una fecha con un formato específico
function validateDate($date, $format = 'd-m-Y'){
    $dateObject = DateTime::createFromFormat($format, $date);

    return $dateObject && $dateObject->format($format) === $date;
}

// Para generar una string aleatoria con una longitud como parámetros
function generateRandomString($length = 6) {
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

// Para devolver como JSON un array
function returnJSON($response = []){
    header('Content-Type: application/json; charset=utf-8');

    echo json_encode($response);
}
