<?php
// Conexão com o banco de dados
$conn = new mysqli("localhost", "root", "", "bpo");
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
                                    <td><a href="editar_cliente.php?id=<?php echo $row['id']; ?>">Editar</a></td>
                                    <td><a href="excluir_cliente.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Tem certeza que deseja excluir este cliente?')">Excluir</a></td>

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
<!-- Modal de Confirmação -->
<div id="modalConfirmacao" class="modal">
    <div class="modal-content">
        <span class="close" onclick="fecharModal()">&times;</span>
        <p id="modalMessage"></p>
    </div>
</div>
<script>
function gerarMensalidades() {
    let formData = new FormData(document.getElementById('gerarMensalidadesForm'));

    fetch('gerar_mensalidades.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            showModal(data.message);
            setTimeout(() => { location.reload(); }, 2000);
        } else {
            alert("Erro: " + data.message);
        }
    })
    .catch(error => {
        console.error("Erro ao gerar mensalidades:", error);
    });
}
</script>

<style>
  .modal {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.4);
  }

  .modal-content {
    background-color: #fff;
    margin: 15% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 50%;
    text-align: center;
  }

  .close {
    color: #aaa;
    float: right;
    font-size: 28px;
    cursor: pointer;
  }

  .close:hover {
    color: black;
  }
</style>

<script>
function showModal(message) {
    document.getElementById("modalMessage").innerText = message;
    document.getElementById("modalConfirmacao").style.display = "block";
}

function fecharModal() {
    document.getElementById("modalConfirmacao").style.display = "none";
}
</script>

</html>

<?php
$conn->close();
?>
