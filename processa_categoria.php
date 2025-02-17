<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bpo"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["sucesso" => false, "mensagem" => "Erro na conexão com o banco de dados."]));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $categoria_nome = trim($_POST["categoria_nome"]);
    $mensalidade = trim($_POST["mensalidade"]);

    if (empty($categoria_nome) || empty($mensalidade)) {
        echo json_encode(["sucesso" => false, "mensagem" => "Nome da categoria e mensalidade são obrigatórios."]);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO categorias (categoria_nome, mensalidade) VALUES (?, ?)");
    $stmt->bind_param("sd", $categoria_nome, $mensalidade); // "sd" => string e double

    if ($stmt->execute()) {
        echo json_encode(["sucesso" => true, "mensagem" => "Categoria cadastrada com sucesso!"]);
    } else {
        echo json_encode(["sucesso" => false, "mensagem" => "Erro ao cadastrar categoria."]);
    }

    $stmt->close();
}

$conn->close();
?>
