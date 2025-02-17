<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Despesas</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Estilos para a tabela e os botões, semelhantes ao seu modelo */
        table {
            width: 100%;
            border-collapse: collapse;
            font-family: Arial, sans-serif;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        th {
            background-color: #007bff;
            color: white;
            padding: 12px;
            text-align: left;
            font-size: 16px;
            border-bottom: 3px solid #0056b3;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            font-size: 14px;
            color: #333;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #ffeb99;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
        }

        .btn-cadastrar {
            margin-top: 10px;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            font-size: 14px;
            border-radius: 5px;
            background-color: #007bff;
            color: white;
        }

        .btn-filtrar {
            margin-top: 10px;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            font-size: 14px;
            border-radius: 5px;
            background-color: #28a745;
            color: white;
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
            </header>

            <div class="container">
                <h2>Cadastro de Despesas</h2>
                <form action="despesas.php" method="POST">
                    <label for="descricao">Descrição da Despesa:</label>
                    <input type="text" id="descricao" name="descricao" required>

                    <label for="valor">Valor (R$):</label>
                    <input type="number" id="valor" name="valor" required>

                    <label for="data">Data:</label>
                    <input type="date" id="data" name="data" required>

                    <label for="responsavel">Responsável:</label>
                    <input type="text" id="responsavel" name="responsavel" required>

                    <label for="eixo">Eixo:</label>
                    <select id="eixo" name="eixo" required>
                        <option value="eixo1">Eixo Tecnologia</option>
                        <option value="eixo2">Eixo Educação</option>
                        <option value="eixo3">Eixo Mobilidade</option>
                    </select>

                    <button type="submit" class="btn-cadastrar">Cadastrar Despesa</button>
                </form>

                <?php
                $conn = new mysqli("localhost", "root", "", "bpo");

                if ($conn->connect_error) {
                    die("Erro de conexão: " . $conn->connect_error);
                }

                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $descricao = $_POST['descricao'];
                    $valor = $_POST['valor'];
                    $data = $_POST['data'];
                    $responsavel = $_POST['responsavel'];
                    $eixo = $_POST['eixo'];

                    $sql = "INSERT INTO despesas (descricao, valor, data, responsavel, eixo) VALUES ('$descricao', '$valor', '$data', '$responsavel', '$eixo')";
                    if ($conn->query($sql) === TRUE) {
                        echo "<p>Despesa cadastrada com sucesso!</p>";
                    } else {
                        echo "<p>Erro ao cadastrar despesa: " . $conn->error . "</p>";
                    }
                }

                $sql_despesas = "SELECT * FROM despesas";
                $result_despesas = $conn->query($sql_despesas);

                if ($result_despesas->num_rows > 0) {
                    echo "<h3>Lista de Despesas</h3>";
                    echo "<form action='despesas.php' method='GET'>";
                    echo "<label for='mes_ano'>Filtrar por mês e ano:</label>";
                    echo "<input type='month' id='mes_ano' name='mes_ano' 
                            value='" . (isset($_GET['mes_ano']) && !empty($_GET['mes_ano']) ? htmlspecialchars($_GET['mes_ano']) : date('Y-m')) . "' 
                            onchange='this.form.submit()'>";
                    echo "</form>";
                    

                    $where = "";
                    if (isset($_GET['mes_ano'])) {
                        $mes_ano = $_GET['mes_ano'];
                        $where = "WHERE DATE_FORMAT(data, '%Y-%m') = '$mes_ano'";
                    }

                    $sql_despesas_filtradas = "SELECT * FROM despesas $where";
                    $result_despesas_filtradas = $conn->query($sql_despesas_filtradas);

                    echo "<table>";
                    echo "<thead><tr><th>Descrição</th><th>Valor (R$)</th><th>Data</th><th>Responsável</th><th>Eixo</th></tr></thead><tbody>";

                    $total = 0;
                    while ($despesa = $result_despesas_filtradas->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $despesa['descricao'] . "</td>";
                        echo "<td>" . number_format($despesa['valor'], 2, ',', '.') . "</td>";
                        echo "<td>" . $despesa['data'] . "</td>";
                        echo "<td>" . $despesa['responsavel'] . "</td>";
                        echo "<td>" . $despesa['eixo'] . "</td>";
                        echo "</tr>";
                        $total += $despesa['valor'];
                    }

                    echo "</tbody></table>";
                    echo "<h3>Total de Despesas: R$ " . number_format($total, 2, ',', '.') . "</h3>";
                } else {
                    echo "<p>Não há despesas cadastradas.</p>";
                }
                ?>
            </div>
        </div>
    </div>

</body>
</html>
