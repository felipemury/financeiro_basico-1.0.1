<?php
// Verificar se o ID do cliente foi passado
if (isset($_GET['id'])) {
    $cliente_id = $_GET['id'];

    // Conexão com o banco de dados
    $conn = new mysqli("localhost", "root", "", "bpo");

    // Verificar se a conexão foi bem-sucedida
    if ($conn->connect_error) {
        die("Erro de conexão: " . $conn->connect_error);
    }

    // Iniciar uma transação para garantir que a exclusão ocorra de forma segura
    $conn->begin_transaction();

    try {
        // Excluir as mensalidades associadas ao cliente
        $sql_mensalidades = "DELETE FROM mensalidades WHERE cliente_id = ?";
        $stmt_mensalidades = $conn->prepare($sql_mensalidades);
        $stmt_mensalidades->bind_param("i", $cliente_id);
        $stmt_mensalidades->execute();

        // Agora excluir o cliente
        $sql_cliente = "DELETE FROM clientes WHERE id = ?";
        $stmt_cliente = $conn->prepare($sql_cliente);
        $stmt_cliente->bind_param("i", $cliente_id);
        $stmt_cliente->execute();

        // Confirmar a transação
        $conn->commit();

        // Redirecionar para a lista de clientes após a exclusão
        header("Location: lista_clientes.php");
        exit;

    } catch (Exception $e) {
        // Se algo deu errado, faz o rollback da transação
        $conn->rollback();
        echo "Erro ao excluir o cliente: " . $e->getMessage();
    }

    // Fechar a conexão
    $conn->close();
} else {
    echo "ID do cliente não fornecido.";
}
?>
