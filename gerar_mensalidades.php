<?php
// Conexão com o banco de dados
$conn = new mysqli("localhost", "root", "", "bpo");

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Verifica se os dados foram enviados
if (isset($_POST['cliente_id'], $_POST['quantidade'], $_POST['valor_mensalidade'])) {
    $cliente_id = $_POST['cliente_id'];
    $quantidade = $_POST['quantidade'];
    $valor_mensalidade = $_POST['valor_mensalidade'];

    // Valida se os campos estão preenchidos
    if (empty($cliente_id) || empty($quantidade) || empty($valor_mensalidade)) {
        echo "Erro: Dados incompletos!";
        exit;
    }

    // Inserir as mensalidades na tabela
    $sql = "INSERT INTO mensalidades (cliente_id, valor, status) VALUES (?, ?, 'Em aberto')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("id", $cliente_id, $valor_mensalidade);

    // Inserir a quantidade de mensalidades
    for ($i = 0; $i < $quantidade; $i++) {
        $stmt->execute();
    }

    echo "Mensalidades geradas com sucesso!";
} else {
    echo "Erro: Dados incompletos!";
}

$conn->close();
?>
