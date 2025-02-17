<?php
// Conexão com o banco de dados
$conn = new mysqli("localhost", "root", "", "bpo");

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

$cliente_id = $_GET['cliente_id'];

// Verificar se o cliente_id está presente
if (!isset($cliente_id) || empty($cliente_id)) {
    echo json_encode(["mensalidades" => [], "error" => "Cliente não encontrado"]);
    exit();
}

// Buscar as mensalidades do cliente
$sql = "SELECT mensalidades.valor, categorias.categoria_nome, mensalidades.data_vencimento, mensalidades.status
        FROM mensalidades
        INNER JOIN categorias ON mensalidades.categoria_id = categorias.id
        WHERE mensalidades.cliente_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $cliente_id);
$stmt->execute();
$result = $stmt->get_result();

$mensalidades = [];
while ($row = $result->fetch_assoc()) {
    $mensalidades[] = $row;
}

if (empty($mensalidades)) {
    echo json_encode(["mensalidades" => [], "error" => "Nenhuma mensalidade encontrada"]);
} else {
    echo json_encode(["mensalidades" => $mensalidades]);
}

$conn->close();
?>
