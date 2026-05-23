// ==================== dataDoadoras.js ====================
// Array JSON com dados das doadoras do BLH Marília
// Inclui: id, nome, zona (região), tipo de leite, frascos, volume, status

const dataDoadoras = [
  {
    id: "DOA-001",
    nome: "Maria Aparecida Santos",
    zona: "Zona Norte",
    tipoLeite: "Maduro",
    frascos: 24,
    volume: "4.920 mL",
    status: "Ativa",
    cpf: "392.847.XXX-12",
    telefone: "(14) 99823-4521",
    cadastro: "12/03/2024",
    observacoes: "Excelente higiene, frasco sempre limpo."
  },
  {
    id: "DOA-002",
    nome: "Ana Carolina Oliveira",
    zona: "Zona Sul",
    tipoLeite: "Colostro",
    frascos: 8,
    volume: "1.360 mL",
    status: "Ativa",
    cpf: "123.456.XXX-78",
    telefone: "(14) 98765-4321",
    cadastro: "15/03/2024",
    observacoes: "Primeira doação, orientada sobre conservação."
  },
  {
    id: "DOA-003",
    nome: "Fernanda Costa Pereira",
    zona: "Zona Leste",
    tipoLeite: "Transição",
    frascos: 16,
    volume: "3.200 mL",
    status: "Ativa",
    cpf: "456.789.XXX-32",
    telefone: "(14) 91234-5678",
    cadastro: "20/03/2024",
    observacoes: "Doadora frequente, volume consistente."
  },
  {
    id: "DOA-004",
    nome: "Juliana Moraes Lima",
    zona: "Zona Oeste",
    tipoLeite: "Hipercalórico",
    frascos: 31,
    volume: "6.820 mL",
    status: "Inativa",
    cpf: "789.012.XXX-45",
    telefone: "(14) 94567-8901",
    cadastro: "01/04/2024",
    observacoes: "Em pausa temporária por orientação médica."
  },
  {
    id: "DOA-005",
    nome: "Patricia Rodrigues Alves",
    zona: "Zona Rural",
    tipoLeite: "Maduro",
    frascos: 19,
    volume: "3.990 mL",
    status: "Ativa",
    cpf: "321.654.XXX-98",
    telefone: "(14) 97890-1234",
    cadastro: "05/04/2024",
    observacoes: "Coleta domiciliar agendada às quartas."
  },
  {
    id: "DOA-006",
    nome: "Camila Souza Mendes",
    zona: "Zona Norte",
    tipoLeite: "Colostro",
    frascos: 5,
    volume: "850 mL",
    status: "Ativa",
    cpf: "654.987.XXX-21",
    telefone: "(14) 93456-7890",
    cadastro: "10/04/2024",
    observacoes: "Recém-cadastrada, acompanhamento inicial."
  }
];

// ==================== filtroDoadoras ====================
// Função que filtra doadoras por zona, status e/ou nome
// Parâmetros:
//   - zona (string): "Zona Norte", "Zona Sul", etc. ou "Todas"
//   - status (string): "Ativa", "Inativa" ou "Todos"
//   - nomeBusca (string): texto digitado no campo de busca
// Retorna: array filtrado

function filtroDoadoras(zona, status, nomeBusca) {
  let resultado = [...dataDoadoras];

  // Filtro por ZONA
  if (zona && zona !== "Todas" && zona !== "Todas as regiões") {
    resultado = resultado.filter(d => d.zona === zona);
  }

  // Filtro por STATUS
  if (status && status !== "Todos" && status !== "Todos os status") {
    resultado = resultado.filter(d => d.status === status);
  }

  // Filtro por NOME (busca parcial, ignora maiúsculas/minúsculas)
  if (nomeBusca && nomeBusca.trim() !== "") {
    const busca = nomeBusca.toLowerCase().trim();
    resultado = resultado.filter(d =>
      d.nome.toLowerCase().includes(busca) ||
      d.id.toLowerCase().includes(busca)
    );
  }

  return resultado;
}

// ==================== renderTabelaDoadoras ====================
// Função auxiliar para renderizar a tabela no HTML
// Recebe um array de doadoras e atualiza o <tbody>

function renderTabelaDoadoras(doadoras) {
  const tbody = document.querySelector(".blh-donors-table tbody");
  const infoText = document.querySelector(".blh-page-title p");
  if (!tbody) return;

  // Atualiza o contador no header (ex: "3 cadastradas · 2 ativas")
  const ativas = doadoras.filter(d => d.status === "Ativa").length;
  if (infoText) {
    infoText.textContent = `${doadoras.length} cadastradas · ${ativas} ativas`;
  }

  // Se não houver resultados, mostra mensagem
  if (doadoras.length === 0) {
    tbody.innerHTML = `
      <tr>
        <td colspan="7" style="text-align: center; padding: 40px; color: var(--blh-text-light);">
          Nenhuma doadora encontrada com os filtros selecionados.
        </td>
      </tr>
    `;
    return;
  }

  tbody.innerHTML = doadoras.map(d => `
    <tr data-id="${d.id}" class="blh-donor-row">
      <td>
        <div class="blh-donor-name">${d.nome}</div>
        <div class="blh-donor-id">${d.id}</div>
      </td>
      <td>${d.zona}</td>
      <td>${d.tipoLeite}</td>
      <td><strong>${d.frascos}</strong></td>
      <td class="blh-volume-text">${d.volume}</td>
      <td>
        <span class="blh-status-tag ${d.status === 'Ativa' ? 'blh-status-tag--active' : 'blh-status-tag--inactive'}">
          ${d.status}
        </span>
      </td>
      <td>
        <img class="eyesdoadora" src="assets/imgs/view.png" alt="Ver detalhes" />
      </td>
    </tr>
  `).join("");

  // Adiciona evento de clique em cada linha
  document.querySelectorAll(".blh-donor-row").forEach(row => {
    row.addEventListener("click", () => {
      const doadoraId = row.getAttribute("data-id");
      abrirPerfilDoadora(doadoraId, row);
    });
  });
}

// ==================== abrirPerfilDoadora ====================
// Abre o painel lateral com os dados da doadora clicada

function abrirPerfilDoadora(id, rowElement) {
  const doadora = dataDoadoras.find(d => d.id === id);
  if (!doadora) return;

  // Destaca a linha selecionada na tabela
  document.querySelectorAll(".blh-donor-row").forEach(r => {
    r.classList.remove("blh-donor-row--selecionada");
  });
  if (rowElement) {
    rowElement.classList.add("blh-donor-row--selecionada");
  }

  // Cria ou atualiza o painel lateral
  let painel = document.querySelector(".blh-perfil-panel");

  if (!painel) {
    painel = document.createElement("aside");
    painel.className = "blh-perfil-panel";
    document.body.appendChild(painel);
  }

  const statusClass = doadora.status === "Ativa" ? "blh-status-tag--active" : "blh-status-tag--inactive";

  painel.innerHTML = `
    <div class="blh-perfil-header">
      <h3 class="blh-perfil-title">Perfil da Doadora</h3>
      <button class="blh-perfil-close" onclick="fecharPerfilDoadora()">&times;</button>
    </div>
    <div class="blh-perfil-content">
      <div class="blh-perfil-avatar">
        <i class="fa-regular fa-heart"></i>
      </div>
      <div class="blh-perfil-nome">${doadora.nome}</div>
      <div class="blh-perfil-id">${doadora.id}</div>

      <div class="blh-perfil-dados">
        <div class="blh-perfil-linha">
          <span class="blh-perfil-label">CPF</span>
          <span class="blh-perfil-valor">${doadora.cpf}</span>
        </div>
        <div class="blh-perfil-linha">
          <span class="blh-perfil-label">Telefone</span>
          <span class="blh-perfil-valor">${doadora.telefone}</span>
        </div>
        <div class="blh-perfil-linha">
          <span class="blh-perfil-label">Região</span>
          <span class="blh-perfil-valor">${doadora.zona}</span>
        </div>
        <div class="blh-perfil-linha">
          <span class="blh-perfil-label">Tipo de leite</span>
          <span class="blh-perfil-valor">${doadora.tipoLeite}</span>
        </div>
        <div class="blh-perfil-linha">
          <span class="blh-perfil-label">Frascos doados</span>
          <span class="blh-perfil-valor">${doadora.frascos} frascos</span>
        </div>
        <div class="blh-perfil-linha">
          <span class="blh-perfil-label">Volume total</span>
          <span class="blh-perfil-valor">${doadora.volume}</span>
        </div>
        <div class="blh-perfil-linha">
          <span class="blh-perfil-label">Cadastro</span>
          <span class="blh-perfil-valor">${doadora.cadastro}</span>
        </div>
        <div class="blh-perfil-linha">
          <span class="blh-perfil-label">Status</span>
          <span class="blh-status-tag ${statusClass}">${doadora.status}</span>
        </div>
      </div>

      <div class="blh-perfil-obs">
        <span class="blh-perfil-label">Observações</span>
        <p class="blh-perfil-obs-texto">${doadora.observacoes}</p>
      </div>
    </div>
  `;

  painel.classList.add("blh-perfil-panel--aberto");

  // Cria overlay escuro
  let overlay = document.querySelector(".blh-perfil-overlay");
  if (!overlay) {
    overlay = document.createElement("div");
    overlay.className = "blh-perfil-overlay";
    overlay.addEventListener("click", fecharPerfilDoadora);
    document.body.appendChild(overlay);
  }
  overlay.classList.add("blh-perfil-overlay--ativo");
}

// ==================== fecharPerfilDoadora ====================
// Fecha o painel lateral

function fecharPerfilDoadora() {
  const painel = document.querySelector(".blh-perfil-panel");
  const overlay = document.querySelector(".blh-perfil-overlay");

  if (painel) {
    painel.classList.remove("blh-perfil-panel--aberto");
  }
  if (overlay) {
    overlay.classList.remove("blh-perfil-overlay--ativo");
  }

  // Remove destaque da linha
  document.querySelectorAll(".blh-donor-row").forEach(r => {
    r.classList.remove("blh-donor-row--selecionada");
  });
}

// ==================== initFiltros ====================
// Inicializa todos os filtros (zona, status e busca por nome)
// Deve ser chamado após o DOM estar carregado

function initFiltros() {
  const selectZona = document.querySelectorAll(".blh-filter-select")[0];
  const selectStatus = document.querySelectorAll(".blh-filter-select")[1];
  const inputBusca = document.querySelector(".blh-search-input");

  // Função que aplica todos os filtros de uma vez
  function aplicarFiltros() {
    const zona = selectZona ? selectZona.value : "Todas";
    const status = selectStatus ? selectStatus.value : "Todos";
    const nomeBusca = inputBusca ? inputBusca.value : "";

    const filtradas = filtroDoadoras(zona, status, nomeBusca);
    renderTabelaDoadoras(filtradas);
  }

  // Liga os eventos
  if (selectZona) {
    selectZona.addEventListener("change", aplicarFiltros);
  }

  if (selectStatus) {
    selectStatus.addEventListener("change", aplicarFiltros);
  }

  if (inputBusca) {
    inputBusca.addEventListener("input", aplicarFiltros);
  }

  // Renderiza a tabela completa ao carregar
  aplicarFiltros();
}

// Exporta para uso em outros scripts (opcional, se usar modules)
// export { dataDoadoras, filtroDoadoras, renderTabelaDoadoras, initFiltros, abrirPerfilDoadora, fecharPerfilDoadora };