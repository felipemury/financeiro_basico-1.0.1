<?php
// Conexão com o banco de dados
$conn = new mysqli("localhost", "root", "", "bpo");

// Verificar se a conexão foi bem-sucedida
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Buscar todos os clientes
$sql = "SELECT id, nome, email, categoria_id FROM clientes";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Clientes</title>
    <link rel="stylesheet" href="styles.css">
 <style>   /* DROPDOWN CONFIG*/ 
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
                <h2>Lista de Clientes</h2>

                <?php if ($result->num_rows > 0) { ?>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>E-mail</th>
                                <th>Categoria</th>
                                <th>Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()) { ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo $row['nome']; ?></td>
                                    <td><?php echo $row['email']; ?></td>
                                    <td><?php 
                                        // Buscar nome da categoria
                                        $categoria_id = $row['categoria_id'];
                                        $sql_categoria = "SELECT categoria_nome FROM categorias WHERE id = $categoria_id";
                                        $result_categoria = $conn->query($sql_categoria);
                                        $categoria = $result_categoria->fetch_assoc();
                                        echo $categoria['categoria_nome'];
                                    ?></td>
                                    <td>
                                        <a href="editar_cliente.php?id=<?php echo $row['id']; ?>">Editar</a> |
                                        <a href="excluir_cliente.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Tem certeza que deseja excluir este cliente?')">Excluir</a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                <?php } else { ?>
                    <p>Não há clientes cadastrados.</p>
                <?php } ?>
            </div>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>
