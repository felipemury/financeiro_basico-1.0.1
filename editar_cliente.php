<?php
// Verificar se o ID foi passado
if (isset($_GET['id'])) {
    $cliente_id = $_GET['id'];

    // Conexão com o banco de dados
    $conn = new mysqli("localhost", "root", "", "bpo");
    if ($conn->connect_error) {
        die("Erro de conexão: " . $conn->connect_error);
    }

    // Buscar as informações do cliente
    $sql = "SELECT id, nome, email, categoria_id FROM clientes WHERE id = $cliente_id";
    $result = $conn->query($sql);
    $cliente = $result->fetch_assoc();

    // Buscar as categorias para o select
    $sql_categorias = "SELECT id, categoria_nome FROM categorias";
    $result_categorias = $conn->query($sql_categorias);
} else {
    echo "ID do cliente não fornecido.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cliente</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="wrapper">
        <!-- Menu Lateral -->
        <nav class="sidebar">
            <h2>Menu</h2>
            <ul>
                <li><a href="index.php">Cadastro de Cliente</a></li>
                <li><a href="clientes.php">Lista de Clientes</a></li>
                <li><a href="categorias.php">Cadastro de Categorias</a></li>
                <li><a href="mensalidades.php">Mensalidades</a></li>
                <li><a href="despesas.php.php">Cadastro Despesas</a></li>
                <li><a href="extrato.php">Extrato</a></li>
            </ul>
        </nav>

        <!-- Conteúdo Principal -->
        <div class="main-content">
            <!-- Navbar -->
            <header class="navbar">
                <div class="logo">
                    <img src="logo.png" alt="Logo">
                </div>
                <div class="welcome">
                    Bem-vindo, <strong>Usuário</strong>!
                </div>
                <div class="logout">
                    <a href="logout.php">Sair</a>
                </div>
            </header>

            <div class="container">
                <h2>Editar Cliente</h2>
                <form action="processa_edicao.php" method="POST">
                    <input type="hidden" name="id" value="<?php echo $cliente['id']; ?>">

                    <label for="nome">Nome do Cliente:</label>
                    <input type="text" id="nome" name="nome" value="<?php echo $cliente['nome']; ?>" required>

                    <label for="email">E-mail:</label>
                    <input type="email" id="email" name="email" value="<?php echo $cliente['email']; ?>" required>

                    <label for="categoria">Categoria:</label>
                    <select id="categoria" name="categoria_id" required>
                        <?php while ($categoria = $result_categorias->fetch_assoc()) { ?>
                            <option value="<?php echo $categoria['id']; ?>" <?php if ($categoria['id'] == $cliente['categoria_id']) echo "selected"; ?>>
                                <?php echo $categoria['categoria_nome']; ?>
                            </option>
                        <?php } ?>
                    </select>

                    <button type="submit">Salvar Alterações</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>
