<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$servername = 'segapp.mysql.uhserver.com';
$username = 'segapp';
$password = 'prog-3';
$dbname = 'segapp';

$con = new mysqli($servername, $username, $password, $dbname);

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Retrieve the JSON parameter from the POST request
$jsonParam = json_decode(file_get_contents('php://input'), true);

if (!empty($jsonParam)) {
    // Prepare the data for deletion
    $idUsuario = isset($jsonParam['idUsuario']) ? intval($jsonParam['idUsuario']) : 0;

    // Prepare the SQL statement for deletion
    $deleteQuery = "DELETE FROM Usuario WHERE idUsuario = '$idUsuario'";

    if ($con->query($deleteQuery) === true) {
        // Deletion successful
        $response = array(
            'success' => true,
            'message' => 'Usuário excluído com sucesso!'
        );
        echo json_encode($response);
    } else {
        // Error in deletion
        $response = array(
            'success' => false,
            'message' => 'Erro ao excluir o usuário: ' . $con->error
        );
        echo json_encode($response);
    }
} else {
    // No data provided
    $response = array(
        'success' => false,
        'message' => 'Dados insuficientes para excluir o usuário!'
    );
    echo json_encode($response);
}

$con->close();

?>