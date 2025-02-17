<?php
include 'conexao.php';

$nome = isset($_GET['nome']) ? $_GET['nome'] : '';

$sql = "SELECT id, nome FROM clientes WHERE nome LIKE :nome LIMIT 10";
$stmt = $pdo->prepare($sql);
$stmt->execute([':nome' => "%$nome%"]);

$clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($clientes);
?>
