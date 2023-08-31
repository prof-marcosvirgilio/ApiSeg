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

// Retrieve the JSON parameter
$jsonParam = json_decode(file_get_contents('php://input'), true);

if (!empty($jsonParam)) {
    // Prepare the WHERE clause
    $whereClause = '';
    foreach ($jsonParam as $field => $value) {
        if ($value !== '' && $value !== '0') {
            $whereClause .= "$field = '$value' AND ";
        }
    }
    $whereClause = rtrim($whereClause, ' AND ');

    // Prepare the SQL statement
    $consulta = "SELECT idUsuario, nmUsuario, deEmail, deSenha, cdSexo, cdTipo, dtNascimento, opTermo 
                 FROM Usuario $whereClause";

    $result = $con->query($consulta);

    $json = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Convert character encoding for each field
            foreach ($row as &$value) {
                $value = mb_convert_encoding($value, 'UTF-8', 'ISO-8859-1');
            }

            $usuario = array(
                "idUsuario" => $row['idUsuario'],
                "nmUsuario" => $row['nmUsuario'],
                "deEmail" => $row['deEmail'],
                "deSenha" => $row['deSenha'],
                "cdSexo" => $row['cdSexo'],
                "cdTipo" => $row['cdTipo'],
                "dtNascimento" => $row['dtNascimento'],
                "opTermo" => $row['opTermo']
            );
            $json[] = $usuario;
        }
    } else {
        $usuario = array(
            "idUsuario" => 0,
            "nmUsuario" => "",
            "deEmail" => "",
            "deSenha" => "",
            "cdSexo" => 0,
            "cdTipo" => 0,
            "dtNascimento" => "",
            "opTermo" => false
        );
        $json[] = $usuario;
    }

    if ($json) {
        $encoded_json = json_encode($json);
        if ($encoded_json === false) {
            echo "Error encoding JSON: " . json_last_error_msg();
        } else {
            header('Content-Type: application/json; charset=utf-8');
            echo $encoded_json;
        }
    } else {
        echo "Empty JSON data.";
    }

    $result->free_result();
}

$con->close();

?>