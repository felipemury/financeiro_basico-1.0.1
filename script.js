document.getElementById('cadastroForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Impede o envio padrão do formulário

    let formData = new FormData(this);

    fetch('processa_cadastro.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('mensagem').textContent = data.mensagem;
        if (data.sucesso) {
            document.getElementById('cadastroForm').reset();
        }
    })
    .catch(error => console.error('Erro:', error));
});
// Função para alternar a visibilidade do menu lateral
function toggleMenu() {
    const sidebar = document.querySelector('.sidebar');
    sidebar.classList.toggle('show');
}
document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("btnVerMensalidades").addEventListener("click", verMensalidades);
    document.getElementById("btnGerarMensalidades").addEventListener("click", gerarMensalidades);
});

function verMensalidades() {
    let clienteId = document.getElementById("cliente").value;
    if (!clienteId) {
        alert("Selecione um cliente!");
        return;
    }

    fetch("ver_mensalidades.php?cliente_id=" + clienteId)
    .then(response => response.text())
    .then(data => {
        document.getElementById("mensalidades-container").innerHTML = data;
    })
    .catch(error => {
        console.error("Erro ao buscar mensalidades:", error);
        alert("Erro ao carregar as mensalidades.");
    });
}

function gerarMensalidades() {
    let clienteId = document.getElementById("cliente").value;
    if (!clienteId) {
        alert("Selecione um cliente!");
        return;
    }

    let formData = new FormData();
    formData.append("cliente_id", clienteId);

    fetch("gerar_mensalidades.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            showModal(data.message);
            verMensalidades(); // Atualiza a lista após gerar
        } else {
            alert("Erro: " + data.message);
        }
    })
    .catch(error => {
        console.error("Erro ao gerar mensalidades:", error);
        alert("Erro ao gerar mensalidades.");
    });
}

function showModal(message) {
    document.getElementById("modalMessage").innerHTML = message;
    document.getElementById("myModal").style.display = "block";
}

function fecharModal() {
    document.getElementById("myModal").style.display = "none";
}
// Função para mostrar o modal de confirmação
function showModal(message, clienteId) {
    document.getElementById('modal-message').innerText = message;
    document.getElementById('modal').style.display = 'block';

    // Guardar o cliente ID para usar na geração das mensalidades
    window.clienteId = clienteId;
}

// Função para gerar mensalidades se o usuário confirmar
function gerarMensalidades(clienteId) {
    var formData = new FormData();
    formData.append('cliente_id', clienteId);
    formData.append('gerar_mensalidades', 'true');  // Informa ao back-end que deve gerar as mensalidades

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "gerar_mensalidades.php", true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            alert("Mensalidades geradas com sucesso!");
            window.location.href = "lista_clientes.php"; // Redireciona para a lista de clientes
        }
    };
    xhr.send(formData);
}

// Função para fechar o modal
function closeModal() {
    document.getElementById('modal').style.display = 'none';
}

// Função para enviar o formulário de cadastro de cliente
document.getElementById("cadastroClienteForm").addEventListener("submit", function(event){
    event.preventDefault();  // Previne o envio padrão do formulário

    var formData = new FormData(this);

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "processa_cadastro.php", true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var response = JSON.parse(xhr.responseText);
            if (response.cliente_id) {
                // Mostra o modal perguntando se deseja gerar mensalidades
                showModal("Cliente cadastrado com sucesso. Deseja gerar as mensalidades para os próximos 12 meses?", response.cliente_id);
            } else {
                alert("Erro ao cadastrar o cliente.");
            }
        }
    };
    xhr.send(formData);
});
// Função para selecionar ou desmarcar todos os checkboxes
function selectAllCheckboxes() {
    var checkboxes = document.querySelectorAll('.mensalidade-checkbox');
    var selectAllCheckbox = document.getElementById('select-all');
    checkboxes.forEach(function(checkbox) {
        checkbox.checked = selectAllCheckbox.checked;
    });
}

// Função AJAX para alterar o status das mensalidades selecionadas
document.getElementById('marcarPago').addEventListener('click', function() {
    changeStatus('pago');
});

document.getElementById('btn-cancelar').addEventListener('click', function() {
    changeStatus('nao_pago');
});

function changeStatus(status) {
    var selectedMensalidades = [];
    var checkboxes = document.querySelectorAll('.mensalidade-checkbox:checked');

    checkboxes.forEach(function(checkbox) {
        selectedMensalidades.push(checkbox.value);
    });

    if (selectedMensalidades.length > 0) {
        var formData = new FormData();
        formData.append('mensalidades', JSON.stringify(selectedMensalidades));
        formData.append('status', status);

        // Envia a requisição AJAX
        fetch('atualizar_status.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // Atualiza os status das mensalidades selecionadas sem recarregar a página
                selectedMensalidades.forEach(function(id) {
                    const statusCell = document.getElementById('status_' + id);
                    statusCell.textContent = status === 'pago' ? 'Pago' : 'Em aberto';
                });
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Erro ao atualizar o status:', error);
        });
    } else {
        alert('Por favor, selecione ao menos uma mensalidade.');
    }
}

