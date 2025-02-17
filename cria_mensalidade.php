<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerar Mensalidades</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Estilos para o formulário */
        .container {
            margin: 20px;
        }
        
        .btn-gerar {
            margin-top: 10px;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            font-size: 14px;
            border-radius: 5px;
            background-color: #007bff;
            color: white;
        }

        label {
            font-size: 16px;
        }

        input, select {
            padding: 8px;
            margin: 10px 0;
            width: 100%;
            font-size: 14px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
          /* DROPDOWN CONFIG*/ 
    .menu {
            display: flex;
            gap: 20px;
            position: relative;
        }
        .menu-item {
            position: relative;
        }
        .menu a {
            color: white;
            text-decoration: none;
            font-weight: bold;
            padding: 10px;
            display: block;
        }
        .menu a:hover {
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 5px;
        }  
        /* DROPDOWN CONFIG*/ 
          .dropdown-menu {
            display: none;
            position: absolute;
            background-color: white;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2);
            border-radius: 5px;
            min-width: 150px;
            top: 100%;
            left: 0;
        }

        .dropdown-menu a {
            color: black;
            padding: 10px;
            text-decoration: none;
            display: block;
            font-size: 16px;
        }

        .dropdown-menu a:hover {
            background-color: #f1f1f1;
        }

        /* Mostrar dropdown ao passar o mouse */
        .menu-item:hover .dropdown-menu {
            display: block;
        }
    </style>
</head>

<body>
<nav class="navbar">
        <div class="navbar-left">
            <div class="logo">
            <img src="imagem/logo.png" alt="Logo">
            </div>
        </div>

        <div class="menu">
            <!-- Cadastro com Dropdown -->
            <div class="menu-item">
                <a href="#">Cadastro</a>
                <div class="dropdown-menu">
                    <a href="index.php">Cliente</a>
                    <a href="cadcateg.php">Categoria</a>
                    <a href="lista_clientes.php">Editar Cadastro</a>
                    <a href="lista_clientes.php">Operações</a>
                                    </div>
            </div>
            <div class="menu-item">
            <a href="#">Mensalidade</a>
                <div class="dropdown-menu">
                    <a href="cria_mensalidade.php">Gerar Mensalidades</a>
                    
                                    </div>
            </div>
            
            <a href="despesas.php">Despesas</a>
            <a href="extrato.php">Extrato</a>
     
        </div>

        <div class="navbar-right">
            <button class="btn-sair" onclick="sair()">Sair</button>
        </div>
    </nav>
    <div class="container">
        <h2>Gerar Mensalidades</h2>
        <form id="formGerarMensalidades">
            <label for="cliente">Selecione o Cliente:</label>
            <select id="cliente" name="cliente_id" required>
                <option value="">Selecione um cliente</option>
                <?php
                // Conexão com o banco de dados
                $conn = new mysqli("localhost", "root", "", "bpo");
                if ($conn->connect_error) {
                    die("Erro de conexão: " . $conn->connect_error);
                }

                // Buscar clientes cadastrados
                $sql = "SELECT id, nome FROM clientes";
                $result = $conn->query($sql);

                // Preencher o select com os clientes
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['id'] . "'>" . $row['nome'] . "</option>";
                }

                $conn->close();
                ?>
            </select>

            <label for="quantidade">Quantidade de Mensalidades:</label>
            <input type="number" id="quantidade" name="quantidade" required min="1">

            <button type="button" class="btn-gerar" onclick="gerarMensalidades()">Gerar Mensalidades</button>
        </form>
    </div>

    <script>
    function gerarMensalidades() {
    var clienteId = document.getElementById('cliente').value;
    var quantidade = document.getElementById('quantidade').value;

    if (!clienteId || !quantidade) {
        alert('Por favor, preencha todos os campos.');
        return;
    }

    // Faz uma requisição para obter o valor da mensalidade baseado na categoria do cliente
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'get_valor_mensalidade.php?cliente_id=' + clienteId, true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var resposta = JSON.parse(xhr.responseText);
            
            if (resposta.sucesso) {
                var valorMensalidade = resposta.valor_mensalidade;

                // Agora vamos gerar as mensalidades
                var xhr2 = new XMLHttpRequest();
                xhr2.open('POST', 'gerar_mensalidades.php', true);
                xhr2.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr2.onreadystatechange = function() {
                    if (xhr2.readyState == 4 && xhr2.status == 200) {
                        alert(xhr2.responseText); // Exibe o sucesso ou erro da operação
                    }
                };
                xhr2.send('cliente_id=' + encodeURIComponent(clienteId) + 
                    '&quantidade=' + encodeURIComponent(quantidade) + 
                    '&valor_mensalidade=' + encodeURIComponent(valorMensalidade));

            } else {
                alert('Erro ao buscar o valor da mensalidade.');
            }
        }
    };
    xhr.send();
}



    </script>
</body>

</html>
