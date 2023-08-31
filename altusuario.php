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
    // Prepare the data for updating
    $idUsuario = isset($jsonParam['idUsuario']) ? intval($jsonParam['idUsuario']) : 0;
    $nmUsuario = isset($jsonParam['nmUsuario']) ? $jsonParam['nmUsuario'] : '';
    $deEmail = isset($jsonParam['deEmail']) ? $jsonParam['deEmail'] : '';
    $deSenha = isset($jsonParam['deSenha']) ? $jsonParam['deSenha'] : '';
    $cdSexo = isset($jsonParam['cdSexo']) ? intval($jsonParam['cdSexo']) : 0;
    $cdTipo = isset($jsonParam['cdTipo']) ? intval($jsonParam['cdTipo']) : 0;
    $dtNascimento = isset($jsonParam['dtNascimento']) ? $jsonParam['dtNascimento'] : '';
    $opTermo = isset($jsonParam['opTermo']) ? boolval($jsonParam['opTermo']) : false;

    // Prepare the SQL statement for updating
    $updateQuery = "UPDATE Usuario SET nmUsuario = '$nmUsuario', deEmail = '$deEmail', deSenha = '$deSenha', 
        cdSexo = '$cdSexo', cdTipo = '$cdTipo', dtNascimento = '$dtNascimento', opTermo = '$opTermo' WHERE idUsuario = '$idUsuario'";

    if ($con->query($updateQuery) === true) {
        // Update successful
        $response = array(
            'success' => true,
            'message' => 'Usuário atualizado com sucesso!'
        );
        echo json_encode($response);
    } else {
        // Error in update
        $response = array(
            'success' => false,
            'message' => 'Erro ao atualizar o usuário: ' . $con->error
        );
        echo json_encode($response);
    }
} else {
    // No data provided
    $response = array(
        'success' => false,
        'message' => 'Dados insuficientes para atualizar o usuário!'
    );
    echo json_encode($response);
}

$con->close();

?>