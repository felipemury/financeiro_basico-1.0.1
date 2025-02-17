<?php
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "bpo");

if ($conn->connect_error) {
    die(json_encode(["sucesso" => false, "erro" => "Erro de conexão: " . $conn->connect_error]));
}

if (isset($_GET['cliente_id'])) {
    $cliente_id = intval($_GET['cliente_id']);

    // Buscar a categoria do cliente
    $sql = "SELECT categoria_id FROM clientes WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $cliente_id);
    $stmt->execute();
    $stmt->bind_result($categoria_id);
    
    if ($stmt->fetch()) {
        $stmt->close();

        // Buscar o valor da mensalidade da categoria
        $sql2 = "SELECT mensalidade FROM categorias WHERE id = ?";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param("i", $categoria_id);
        $stmt2->execute();
        $stmt2->bind_result($valor_mensalidade);

        if ($stmt2->fetch()) {
            echo json_encode(["sucesso" => true, "valor_mensalidade" => $valor_mensalidade]);
        } else {
            echo json_encode(["sucesso" => false, "erro" => "Categoria não encontrada"]);
        }
        $stmt2->close();
    } else {
        echo json_encode(["sucesso" => false, "erro" => "Cliente não encontrado"]);
    }
}

$conn->close();
?>
