<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Extrato</title>
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

            <div class="container">
                <h2>Extrato de Movimentações</h2>

                <!-- Filtro de Mês/Ano -->
                <!-- Filtro de Mês/Ano -->
<form action="extrato.php" method="GET">
    <label for="mes_ano">Filtrar por mês e ano:</label>
    <input type="month" id="mes_ano" name="mes_ano" value="<?php echo isset($_GET['mes_ano']) ? $_GET['mes_ano'] : ''; ?>" onchange="this.form.submit()">
</form>


                <?php
                // Conectar ao banco de dados
                $conn = new mysqli("localhost", "root", "", "bpo");

                if ($conn->connect_error) {
                    die("Erro de conexão: " . $conn->connect_error);
                }

                // Variáveis para totalizar as receitas e despesas
                $total_receitas = 0;
                $total_despesas = 0;

                // Definir a variável $mes_ano caso o formulário tenha sido enviado
                $mes_ano = "";
                $where = "";
                if (isset($_GET['mes_ano']) && !empty($_GET['mes_ano'])) {
                    $mes_ano = $_GET['mes_ano'];
                    $where = "WHERE DATE_FORMAT(m.vencimento, '%Y-%m') = '$mes_ano' OR DATE_FORMAT(d.data, '%Y-%m') = '$mes_ano'";
                }

           // Consultar mensalidades pagas (como receita)
           $sql_receitas = "
           SELECT SUM(valor) AS total_receita
           FROM mensalidades
           WHERE status = 'Pago' 
           AND DATE_FORMAT(data_vencimento, '%Y-%m') = '$mes_ano'
       ";
       

       $result_receitas = $conn->query($sql_receitas);

       if ($result_receitas && $row = $result_receitas->fetch_assoc()) {
           $total_receitas = $row['total_receita'] ?? 0;
       }
       

// Verificar se a consulta de receitas foi bem-sucedida
if ($result_receitas === false) {
    echo "<p>Erro ao consultar receitas.</p>";
    $result_receitas = null; // Garantir que a variável seja inicializada
}

// Exibir receitas
echo "<h3>Receitas (Mensalidades Pagas)</h3>";
if ($result_receitas && $result_receitas->num_rows > 0) {
    echo "<table>";
    
    while ($receita = $result_receitas->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $receita['vencimento'] . "</td>";
        echo "<td>" . number_format($receita['total_receita'], 2, ',', '.') . "</td>";
        echo "</tr>";
        $total_receitas += $receita['total_receita']; // Acumulando o total de receitas
    }
    echo "</tbody></table>";
} else {
    echo "<p>Não há receitas para o período selecionado.</p>";
}

$sql_despesas = "
    SELECT SUM(valor) AS total_despesa 
    FROM despesas 
    WHERE DATE_FORMAT(created_at, '%Y-%m') = '$mes_ano'
";

$result_despesas = $conn->query($sql_despesas);

if ($result_despesas && $row = $result_despesas->fetch_assoc()) {
    $total_despesas = $row['total_despesa'] ?? 0;
}

                // Exibir o total
                echo "<h3>Total de Receitas: R$ " . number_format($total_receitas, 2, ',', '.') . "</h3>";
                echo "<h3>Total de Despesas: R$ " . number_format($total_despesas, 2, ',', '.') . "</h3>";
                echo "<h3>Saldo: R$ " . number_format($total_receitas - $total_despesas, 2, ',', '.') . "</h3>";
                
                
                ?>
                
            </div>
        </div>
    </div>

</body>

</html>
