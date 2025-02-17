function buscarMensalidades() {
    let clienteNome = document.getElementById('cliente').value.trim();

    if (!clienteNome) {
        alert("Por favor, selecione um cliente.");
        return;
    }

    console.log("Buscando mensalidades para:", clienteNome); // Verifica se o nome do cliente está correto

    fetch(`get_mensalidades.php?cliente=${encodeURIComponent(clienteNome)}`)
        .then(response => {
            if (!response.ok) {
                throw new Error("Erro na requisição: " + response.statusText);
            }
            return response.json();
        })
        .then(mensalidades => {
            console.log("Resposta do servidor:", mensalidades); // Log para depuração

            if (mensalidades.error) {
                alert(mensalidades.error);
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
                    <td>R$ ${parseFloat(mensalidade.valor).toFixed(2)}</td>
                    <td>${mensalidade.vencimento}</td>
                    <td>${mensalidade.categoria}</td>
                    <td>${mensalidade.status}</td>
                `;
                tableBody.appendChild(row);
            });

            document.getElementById('modalMensalidades').style.display = 'block';
        })
        .catch(error => {
            alert("Erro ao buscar mensalidades. Verifique o console para mais detalhes.");
            console.error("Erro:", error);
        });
}
