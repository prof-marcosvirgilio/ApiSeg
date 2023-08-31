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
    // Prepare the data for insertion
    $nmUsuario = isset($jsonParam['nmUsuario']) ? $jsonParam['nmUsuario'] : '';
    $deEmail = isset($jsonParam['deEmail']) ? $jsonParam['deEmail'] : '';
    $deSenha = isset($jsonParam['deSenha']) ? $jsonParam['deSenha'] : '';
    $cdSexo = isset($jsonParam['cdSexo']) ? intval($jsonParam['cdSexo']) : 0;
    $cdTipo = isset($jsonParam['cdTipo']) ? intval($jsonParam['cdTipo']) : 0;
    $dtNascimento = isset($jsonParam['dtNascimento']) ? date('Y-m-d', strtotime($jsonParam['dtNascimento'])) : '';
    $opTermo = isset($jsonParam['opTermo']) ? boolval($jsonParam['opTermo']) : false;

    // Prepare the SQL statement for insertion
    $insertQuery = "INSERT INTO Usuario (nmUsuario, deEmail, deSenha, cdSexo, cdTipo, dtNascimento, opTermo) 
		VALUES ('$nmUsuario', '$deEmail', '$deSenha', $cdSexo, $cdTipo, '$dtNascimento', $opTermo)";

    if ($con->query($insertQuery) === true) {
        // Insertion successful
        $response = array(
            'success' => true,
            'message' => 'Usuário inserido com sucesso!'
        );
        echo json_encode($response);
    } else {
        // Error in insertion
        $response = array(
            'success' => false,
            'message' => 'Erro no registro do usuário: ' . $con->error
        );
        echo json_encode($response);
    }
} else {
    // No data provided
    $response = array(
        'success' => false,
        'message' => 'Dados insuficientes para o registro do usuário!'
    );
    echo json_encode($response);
}

$con->close();

?>