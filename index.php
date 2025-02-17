<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Cliente</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Estilo básico do modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: #fff;
            margin: 15% auto;
            padding: 20px;
            border-radius: 5px;
            width: 80%;
            max-width: 400px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
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

        <!-- Conteúdo Principal -->
        <div class="main-content">
            

            <!-- Formulário de Cadastro de Cliente -->
            <div class="container">
                <h2>Cadastro de Cliente</h2>
                <form id="cadastroClienteForm">
                    <label for="nome">Nome do Cliente:</label>
                    <input type="text" id="nome" name="nome" required>

                    <label for="email">E-mail:</label>
                    <input type="email" id="email" name="email" required>

                    <label for="categoria">Categoria:</label>
                    <select id="categoria" name="categoria_id" required>
                        <?php
                        // Conexão com o banco de dados
                        $conn = new mysqli("localhost", "root", "", "bpo");
                        if ($conn->connect_error) {
                            die("Erro de conexão: " . $conn->connect_error);
                        }

                        // Buscar categorias cadastradas
                        $sql = "SELECT id, categoria_nome FROM categorias";
                        $result = $conn->query($sql);

                        // Preencher o select com as categorias
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['id'] . "'>" . $row['categoria_nome'] . "</option>";
                        }

                        $conn->close();
                        ?>
                    </select>

                    <label for="data_cadastro">Data do Cadastro:</label>
                    <input type="date" id="data_cadastro" name="data_cadastro" value="<?php echo date('Y-m-d'); ?>" required>

                    <button type="submit">Cadastrar Cliente</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para confirmar a geração de mensalidades -->
    <div id="modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <p id="modal-message">Você deseja gerar mensalidades para este cliente por 12 meses?</p>
            <button onclick="gerarMensalidades()">Sim</button>
            <button onclick="closeModal()">Não</button>
        </div>
    </div>

    <script src="script.js"></script>

    <script>
        // Função para mostrar o modal
        function showModal(message) {
            document.getElementById('modal-message').innerText = message;
            document.getElementById('modal').style.display = 'block';
        }

        // Função para fechar o modal
        function closeModal() {
            document.getElementById('modal').style.display = 'none';
        }

        // Função para gerar mensalidades
        function gerarMensalidades() {
            var formData = new FormData(document.getElementById('cadastroClienteForm'));

            var xhr = new XMLHttpRequest();
            xhr.open("POST", "processa_cadastro.php", true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    // Exibe o modal com a mensagem de sucesso ou erro
                    alert(xhr.responseText);
                    closeModal();
                    window.location.href = "lista_clientes.php"; // Redireciona para a lista de clientes após cadastro
                }
            };
            formData.append('gerar_mensalidades', true);  // Envia um parâmetro para gerar mensalidades
            xhr.send(formData);
        }

        // AJAX para envio do formulário sem recarregar a página
        document.getElementById("cadastroClienteForm").addEventListener("submit", function(event){
            event.preventDefault();  // Previne o envio padrão do formulário

            var formData = new FormData(this);

            var xhr = new XMLHttpRequest();
            xhr.open("POST", "processa_cadastro.php", true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    // Exibe o modal com a mensagem de sucesso ou erro
                    showModal(xhr.responseText);
                }
            };
            xhr.send(formData);
        });
    </script>
</body>
</html>