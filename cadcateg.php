<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Categorias</title>
    <link rel="stylesheet" href="styles.css">
    <style>  /* DROPDOWN CONFIG*/ 
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


       
            <!-- Formulário de Cadastro de Categorias -->
            <div class="container">
                <h2>Cadastro de Categoria</h2>
                <form id="cadastroCategoriaForm" action="processa_categoria.php" method="POST">
                    <label for="categoria_nome">Nome da Categoria:</label>
                    <input type="text" id="categoria_nome" name="categoria_nome" required>

                    <label for="mensalidade">Valor da Mensalidade (R$):</label>
                    <input type="number" id="mensalidade" name="mensalidade" required>

                    <button type="submit">Cadastrar Categoria</button>
                </form>
                <p id="mensagem"></p>
            </div>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>
