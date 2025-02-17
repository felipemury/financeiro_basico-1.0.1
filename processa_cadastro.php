<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Conectar ao banco de dados
    $conn = new mysqli("localhost", "root", "", "bpo");

    if ($conn->connect_error) {
        die("Erro de conexão: " . $conn->connect_error);
    }

    // Coletar dados do formulário
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $categoria_id = $_POST['categoria_id'];
    $data_cadastro = $_POST['data_cadastro'];

    // Inserir o cliente
    $sql_cliente = "INSERT INTO clientes (nome, email, categoria_id, data_cadastro) VALUES (?, ?, ?, ?)";
    $stmt_cliente = $conn->prepare($sql_cliente);
    $stmt_cliente->bind_param("ssis", $nome, $email, $categoria_id, $data_cadastro);
    if ($stmt_cliente->execute()) {
        $cliente_id = $stmt_cliente->insert_id; // ID do cliente recém inserido
        echo "Cliente inserido com sucesso. Cliente ID: " . $cliente_id . "<br>";

        // Verifica se é para gerar mensalidades
        if (isset($_POST['gerar_mensalidades'])) {
            $data_inicio = new DateTime($data_cadastro);
            echo "Gerando mensalidades...<br>";
            for ($i = 0; $i < 12; $i++) {
                $data_inicio->modify("+1 month");
                $data_vencimento = $data_inicio->format("Y-m-d");

                // Inserir mensalidade
                $sql_mensalidade = "INSERT INTO mensalidades (cliente_id, data_vencimento) VALUES (?, ?)";
                $stmt_mensalidade = $conn->prepare($sql_mensalidade);
                $stmt_mensalidade->bind_param("is", $cliente_id, $data_vencimento);
                if ($stmt_mensalidade->execute()) {
                    echo "Mensalidade gerada para o mês de " . $data_vencimento . "<br>";
                } else {
                    echo "Erro ao gerar mensalidade para o mês de " . $data_vencimento . "<br>";
                }
            }
            echo "Mensalidades geradas com sucesso!";
        } else {
            echo "Cliente cadastrado com sucesso, mas sem mensalidades geradas.";
        }
    } else {
        echo "Erro ao cadastrar cliente.";
    }

    $conn->close();
}
?>
