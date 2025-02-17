<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mensalidades do Cliente</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* NAVBAR */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #007BFF;
            padding: 15px 20px;
            color: white;
            font-size: 18px;
        }

        .navbar .menu {
            display: flex;
            gap: 20px;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            font-weight: bold;
            padding: 10px;
        }

        .navbar a:hover {
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 5px;
        }

        .navbar .logo img {
            height: 40px;
        }

        .btn-sair {
            background: red;
            color: white;
            padding: 10px;
            border: none;
            cursor: pointer;
            font-weight: bold;
            border-radius: 5px;
        }
  /* Ajuste de layout */
  .navbar-left,
        .navbar-right {
            display: flex;
            align-items: center;
        }

        .btn-sair:hover {
            background: darkred;
        }

        /* Restante do CSS */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 10px;
            text-align: center;
        }

        tr:hover {
            background-color: #f0f0f0;
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
            margin: 10% auto;
            padding: 20px;
            border-radius: 5px;
            width: 80%;
            max-width: 600px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover {
            color: black;
        }

        .table-container {
            max-height: 420px;
            overflow-y: auto;
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

    <script>
        function sair() {
            if (confirm("Tem certeza que deseja sair?")) {
                window.location.href = "login.html"; // Redireciona para a página de login
            }
        }
    </script>
    <!-- Conteúdo principal -->
    <div class="container">
        <h2>Escolher Cliente</h2>
        <label for="cliente-select">Escolha o Cliente:</label>
        <select id="cliente-select">
            <option value="">Selecione um cliente</option>
        </select>

        <button id="verMensalidadesBtn" onclick="buscarMensalidades()">Ver Mensalidades</button>
    </div>

    <!-- Modal de Mensalidades -->
    <div id="modalMensalidades" class="modal">
        <div class="modal-content">
            <span class="close" onclick="fecharModal()">&times;</span>
            <h3>Mensalidades do Cliente</h3>
            <div class="table-container">
                <table id="mensalidadesTable">
                    <thead>
                        <tr>
                            <th>Selecionar</th>
                            <th>Nome</th>
                            <th>Valor</th>
                            <th>Vencimento</th>
                            <th>Pago em</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <button onclick="confirmarPagamento()">Confirmar Pagamento</button>
            <button onclick="cancelarPagamento()">Cancelar Pagamento</button>
        </div>
    </div>

    <script>
        function sair() {
            if (confirm("Tem certeza que deseja sair?")) {
                window.location.href = "login.html"; // Redireciona para a página de login
            }
        }

        // Função para preencher o select com os clientes
        function preencherSelect(clientes) {
            let select = document.getElementById("cliente-select");
            select.innerHTML = '<option value="">Selecione um cliente</option>';
            clientes.forEach(cliente => {
                let option = document.createElement("option");
                option.value = cliente.id;
                option.innerText = cliente.nome;
                select.appendChild(option);
            });
        }

        // Buscar mensalidades do cliente selecionado
        function buscarMensalidades() {
            let select = document.getElementById("cliente-select");
            let clienteNome = select.options[select.selectedIndex].text;

            if (!clienteNome || clienteNome === "Selecione um cliente") {
                alert("Por favor, selecione um cliente válido.");
                return;
            }

            fetch(`get_mensalidades.php?cliente=${encodeURIComponent(clienteNome)}`)
                .then(response => response.json())
                .then(mensalidades => {
                    if (mensalidades.error) {
                        alert("Erro: " + mensalidades.error);
                        return;
                    }

                    let tableBody = document.querySelector('#mensalidadesTable tbody');
                    tableBody.innerHTML = '';

                    if (mensalidades.length === 0) {
                        alert("Nenhuma mensalidade encontrada para este cliente.");
                        return;
                    }

                    mensalidades.forEach(mensalidade => {
                        let row = document.createElement('tr');
                        row.innerHTML = `
                            <td><input type="checkbox" class="selecionar" data-id="${mensalidade.id}"></td>
                            <td>${mensalidade.nome}</td>
                            <td>${mensalidade.valor}</td>
                             <td>${mensalidade.data_vencimento}</td>
                            <td>${mensalidade.data_pagamento}</td>
                            <td>${mensalidade.status}</td>
                        `;
                        tableBody.appendChild(row);
                    });

                    document.getElementById('modalMensalidades').style.display = 'block';
                })
                .catch(error => {
                    console.error('Erro ao buscar mensalidades:', error);
                    alert("Erro ao carregar as mensalidades.");
                });
        }

        function fecharModal() {
            document.getElementById('modalMensalidades').style.display = 'none';
        }

        window.onclick = function(event) {
            let modal = document.getElementById('modalMensalidades');
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        };

        function confirmarPagamento() {
            let selecionados = document.querySelectorAll('.selecionar:checked');
            let ids = [];
            selecionados.forEach(checkbox => {
                ids.push(checkbox.getAttribute('data-id'));
            });

            if (ids.length === 0) {
                alert("Selecione pelo menos uma mensalidade para alterar o status.");
                return;
            }

            fetch('atualizar_status.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ mensalidade_ids: ids })
            })
            .then(response => response.text())
            .then(resultado => {
                alert(resultado);
                buscarMensalidades();
            });
        }

        window.onload = function() {
            fetch('buscar_clientes.php')
                .then(response => response.json())
                .then(clientes => {
                    preencherSelect(clientes);
                });
        }
    </script>
</body>
</html>
