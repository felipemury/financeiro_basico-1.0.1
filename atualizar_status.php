<?php
// Conectar ao banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bpo"; // Nome do banco de dados


$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Receber os dados JSON enviados pelo frontend
$data = json_decode(file_get_contents('php://input'), true);

// Verificar se a chave 'mensalidade_ids' está presente
if (isset($data['mensalidade_ids']) && !empty($data['mensalidade_ids'])) {
    $mensalidade_ids = $data['mensalidade_ids'];

    // Iterar sobre os IDs das mensalidades e atualizar o status para 'Pago'
    foreach ($mensalidade_ids as $id) {
        $sql = "UPDATE mensalidades SET status = 'Pago' WHERE id = ?"; // Usar prepared statements para evitar SQL injection

        // Preparar a consulta
        if ($stmt = $conn->prepare($sql)) {
            // Vincular o parâmetro
            $stmt->bind_param("i", $id);
            // Executar a consulta
            if ($stmt->execute()) {
                echo "Pagamento confirmado para a mensalidade com ID: $id\n";
            } else {
                echo "Erro ao atualizar status para a mensalidade com ID: $id\n";
            }
            $stmt->close();
        } else {
            echo "Erro na preparação da consulta.\n";
        }
    }
} else {
    echo "Nenhuma mensalidade selecionada.";
}
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "bpo");

if ($conn->connect_error) {
    die(json_encode(["error" => "Erro de conexão: " . $conn->connect_error]));
}

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['mensalidade_ids']) || empty($data['mensalidade_ids'])) {
    echo json_encode(["error" => "Nenhuma mensalidade selecionada."]);
    exit;
}

$ids = implode(',', array_map('intval', $data['mensalidade_ids']));
$dataAtual = date('Y-m-d');

// Atualizar status e data_pagamento para as mensalidades selecionadas
$sql = "UPDATE mensalidades SET status = 'Pago', data_pagamento = '$dataAtual' WHERE id IN ($ids)";

if ($conn->query($sql) === TRUE) {
    echo json_encode(["success" => "Status atualizado com sucesso."]);
} else {
    echo json_encode(["error" => "Erro ao atualizar status: " . $conn->error]);
}


// Fechar a conexão
$conn->close();
?>
