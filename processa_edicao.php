<?php
// Conexão com o banco de dados
$conn = new mysqli("localhost", "root", "", "bpo");
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Recebendo os dados do formulário
$cliente_id = $_POST['id'];
$nome = $_POST['nome'];
$email = $_POST['email'];
$categoria_id = $_POST['categoria_id'];

// Atualizando os dados do cliente
$sql_cliente = "UPDATE clientes SET nome = '$nome', email = '$email', categoria_id = '$categoria_id' WHERE id = $cliente_id";

if ($conn->query($sql_cliente) === TRUE) {
    // Recuperar o valor da mensalidade da nova categoria
    $sql_categoria = "SELECT valor FROM categorias WHERE id = $categoria_id";
    $result_categoria = $conn->query($sql_categoria);

    if ($result_categoria->num_rows > 0) {
        $categoria = $result_categoria->fetch_assoc();
        $novo_valor_mensalidade = $categoria['valor'];

        // Atualizando as mensalidades do cliente com o novo valor
        $sql_mensalidades = "UPDATE mensalidades SET mensalidade = '$novo_valor_mensalidade' WHERE cliente_id = $cliente_id";

        if ($conn->query($sql_mensalidades) === TRUE) {
            // Redireciona para a página de clientes com a mensagem de sucesso
            header("Location: clientes.php?msg=Cliente%20e%20mensalidades%20atualizados%20com%20sucesso!");
            exit();
        } else {
            echo "Erro ao atualizar as mensalidades: " . $conn->error;
        }
    } else {
        echo "Categoria não encontrada.";
    }
} else {
    echo "Erro ao atualizar o cliente: " . $conn->error;
}

$conn->close();
?>
