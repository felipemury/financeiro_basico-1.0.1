<?php
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "bpo");

if ($conn->connect_error) {
    die(json_encode(["error" => "Erro de conexão: " . $conn->connect_error]));
}

if (isset($_GET['cliente'])) {
    $cliente_nome = $_GET['cliente'];

    $sql = "SELECT m.id, c.nome, m.valor, m.data_vencimento, m.data_pagamento, 
                   CASE 
                       WHEN m.data_pagamento IS NOT NULL THEN 'Pago'
                       ELSE 'Em aberto'
                   END AS status
            FROM mensalidades m
            JOIN clientes c ON m.cliente_id = c.id
            WHERE c.nome = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $cliente_nome);
    $stmt->execute();
    $result = $stmt->get_result();

    $mensalidades = [];
    while ($row = $result->fetch_assoc()) {
        $mensalidades[] = [
            "id" => $row["id"],
            "nome" => $row["nome"],
            "valor" => $row["valor"],
            "data_vencimento" => $row["data_vencimento"],
            "data_pagamento" => $row["data_pagamento"] ?? "Pendente",
            "status" => $row["status"]
        ];
    }

    echo json_encode($mensalidades);
} else {
    echo json_encode(["error" => "Cliente não informado"]);
}

$conn->close();
?>
