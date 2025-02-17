<?php
// Conectar ao banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bpo"; // Nome do banco de dados

$conn = new mysqli('localhost', 'root', '', 'bpo');

// Receber os dados JSON enviados
$data = json_decode(file_get_contents('php://input'), true);

// Verificar se a chave 'mensalidade_ids' está presente
if (isset($data['mensalidade_ids']) && !empty($data['mensalidade_ids'])) {
    $mensalidade_ids = $data['mensalidade_ids'];
    
    // Conectar ao banco de dados (ajustar as credenciais conforme necessário)
    $conn = new mysqli('localhost', 'root', '', 'bpo');
    
    // Verificar se a conexão foi bem-sucedida
    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    // Atualizar o status para 'Em aberto' para cada mensalidade
    foreach ($mensalidade_ids as $id) {
        $sql = "UPDATE mensalidades SET status = 'Em aberto' WHERE id = $id";
        if ($conn->query($sql) !== TRUE) {
            echo "Erro ao cancelar pagamento: " . $conn->error;
            exit;
        }
    }

    echo "Pagamento cancelado com sucesso para as mensalidades selecionadas.";
} else {
    echo "Nenhuma mensalidade selecionada.";
}
$conn->close();
?>
