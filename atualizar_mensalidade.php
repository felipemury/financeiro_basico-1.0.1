<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sistema_clientes";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['mensalidades'])) {
    $mensalidades = $_POST['mensalidades'];

    // Atualizar status para 'Pago'
    foreach ($mensalidades as $mensalidade_id) {
        $sql = "UPDATE mensalidades SET status = 'Pago' WHERE id = $mensalidade_id";
        if ($conn->query($sql) === TRUE) {
            echo "Mensalidade atualizada com sucesso!";
        } else {
            echo "Erro ao atualizar mensalidade: " . $conn->error;
        }
    }
}

$conn->close();

header("Location: mensalidades.php?cliente_id=" . $_GET['cliente_id']); // Redireciona para a mesma página com o cliente
exit;
?>
