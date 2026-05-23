<?php
require_once '../dados.php';
require_once '../funcoes.php';
require_once '../auth.php';

verificarLogin();
verificarPerfil('admin');

$usuario = usuarioLogado();

// Estatísticas para os cards
$totalDoadoras = count($doadoras);
$doadorasAtivas = doadorasAtivas($doadoras);
$totalFrascos = count($frascos);
$frascosColetados = totalPorStatus($frascos, 'coletado');
$frascosPasteurizados = totalPorStatus($frascos, 'pasteurizado');
$frascosDistribuidos = totalPorStatus($frascos, 'distribuido');
$frascosDescartados = totalPorStatus($frascos, 'descartado');
$pedidosPendentes = pedidosAguardando($receptores);

// Frascos por tipo
$colostro = totalPorTipo($frascos, 'C');
$transicao = totalPorTipo($frascos, 'T');
$maduro = totalPorTipo($frascos, 'M');

// Últimas distribuições (últimas 5)
$ultimasDist = array_slice(array_reverse($distribuicoes), 0, 5);

// Doadoras da zona de hoje
$zonaHoje = zonaDodia();
$doadorasHoje = doadorasDoDia($doadoras);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — BLH Marília</title>
    <link rel="stylesheet" href="../global.css">
    <style>
        /* Ícones inline */
        .icon-home::before { content: '🏠'; }
        .icon-users::before { content: '👩'; }
        .icon-flask::before { content: '🧪'; }
        .icon-truck::before { content: '🚚'; }
        .icon-search::before { content: '🔍'; }
        .icon-chart::before { content: '📊'; }
        .icon-box::before { content: '📦'; }
        .icon-logout::before { content: '🚪'; }
        .icon-bell::before { content: '🔔'; }
        .icon-temp::before { content: '🌡️'; }
        .icon-baby::before { content: '👶'; }
        .icon-check::before { content: '✅'; }
        .icon-warning::before { content: '⚠️'; }
        .icon-drop::before { content: '💧'; }
        .icon-calendar::before { content: '📅'; }
        .icon-route::before { content: '🗺️'; }
    </style>
</head>
<body>
<div class="dashboard-layout">

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <img src="../img-logo.jpg" alt="BLH Marília" class="sidebar-logo">
            <div class="sidebar-brand">
                BLH Marília
                <span>Sistema de Gestão</span>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section">Principal</div>
            <ul>
                <li><a href="dashboard.php" class="nav-link active">
                    <span class="icon icon-home"></span> Dashboard
                </a></li>
                <li><a href="doadoras.php" class="nav-link">
                    <span class="icon icon-users"></span> Doadoras
                </a></li>
                <li><a href="pasteur.php" class="nav-link">
                    <span class="icon icon-flask"></span> Pasteurização
                </a></li>
                <li><a href="distribuicao.php" class="nav-link">
                    <span class="icon icon-truck"></span> Distribuição
                </a></li>
                <li><a href="rastreabilidade.php" class="nav-link">
                    <span class="icon icon-search"></span> Rastreabilidade
                </a></li>
            </ul>

            <div class="nav-section">Relatórios</div>
            <ul>
                <li><a href="#" class="nav-link">
                    <span class="icon icon-chart"></span> Estatísticas
                </a></li>
                <li><a href="#" class="nav-link">
                    <span class="icon icon-box"></span> Estoque
                </a></li>
            </ul>
        </nav>

        <div class="sidebar-footer">
            <div class="user-card">
                <div class="user-avatar"><?= substr(h($usuario['nome']), 0, 1) ?></div>
                <div class="user-info">
                    <div class="user-name"><?= h($usuario['nome']) ?></div>
                    <div class="user-role">
                        <span class="badge badge-admin"><?= h($usuario['perfil']) ?></span>
                    </div>
                </div>
            </div>
            <a href="../index.php?logout=1" class="nav-link" style="margin-top: var(--space-3);">
                <span class="icon icon-logout"></span> Sair
            </a>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="dashboard-main">

        <!-- TOPBAR -->
        <header class="topbar">
            <h1 class="topbar-title">Dashboard</h1>
            <div class="topbar-actions">
                <span class="text-muted text-sm"><?= h(date('d/m/Y')) ?> · <?= h(zonaDodia() ?: 'Sem coleta') ?></span>
                <span class="badge badge-status-coletado" style="margin-left: var(--space-3);">
                    <?= h($zonaHoje ? 'Coleta: ' . $zonaHoje : 'Fim de semana') ?>
                </span>
            </div>
        </header>

        <!-- CONTENT -->
        <div class="dashboard-content">

            <!-- STATS CARDS -->
            <div class="stats-grid">
                <div class="stat-card animate-fade-in">
                    <div class="stat-icon blue">👩</div>
                    <div class="stat-value"><?= $doadorasAtivas ?></div>
                    <div class="stat-label">Doadoras Ativas</div>
                    <div class="stat-trend up"><?= $totalDoadoras ?> cadastradas</div>
                </div>

                <div class="stat-card animate-fade-in">
                    <div class="stat-icon orange">💧</div>
                    <div class="stat-value"><?= $frascosColetados ?></div>
                    <div class="stat-label">Frascos Coletados Hoje</div>
                    <div class="stat-trend up">Aguardando pasteurização</div>
                </div>

                <div class="stat-card animate-fade-in">
                    <div class="stat-icon green">🧪</div>
                    <div class="stat-value"><?= $frascosPasteurizados ?></div>
                    <div class="stat-label">Frascos Pasteurizados</div>
                    <div class="stat-trend up">Prontos para distribuição</div>
                </div>

                <div class="stat-card animate-fade-in">
                    <div class="stat-icon gold">📦</div>
                    <div class="stat-value"><?= $frascosDistribuidos ?></div>
                    <div class="stat-label">Distribuições Realizadas</div>
                    <div class="stat-trend up">Total acumulado</div>
                </div>

                <div class="stat-card animate-fade-in">
                    <div class="stat-icon red">⚠️</div>
                    <div class="stat-value"><?= $frascosDescartados ?></div>
                    <div class="stat-label">Frascos Descartados</div>
                    <div class="stat-trend down">Não conformes</div>
                </div>

                <div class="stat-card animate-fade-in">
                    <div class="stat-icon blue">👶</div>
                    <div class="stat-value"><?= $pedidosPendentes ?></div>
                    <div class="stat-label">Pedidos Pendentes</div>
                    <div class="stat-trend <?= $pedidosPendentes > 0 ? 'down' : 'up' ?>">
                        <?= $pedidosPendentes > 0 ? 'Aguardando atendimento' : 'Todos atendidos' ?>
                    </div>
                </div>
            </div>

            <!-- SEÇÃO: ROTA DE HOJE + TIPOS DE LEITE -->
            <div class="dashboard-section">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-5);">

                    <!-- ROTA DO DIA -->
                    <div class="card">
                        <div class="card-header">
                            <div>
                                <div class="card-title">🗺️ Rota de Coleta — <?= h(zonaDodia() ?: 'Nenhuma') ?></div>
                                <div class="card-subtitle"><?= h(diaDaZona(zonaDodia())) ?></div>
                            </div>
                            <a href="../coletador/rota.php" class="btn btn-sm btn-outline">Ver rota completa</a>
                        </div>

                        <?php if (count($doadorasHoje) > 0): ?>
                        <div class="table-container">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Doadora</th>
                                        <th>Endereço</th>
                                        <th>Equipamento</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($doadorasHoje as $d): ?>
                                    <tr>
                                        <td>
                                            <strong><?= h($d['nome']) ?></strong>
                                            <br><span class="text-xs text-muted"><?= h($d['telefone']) ?></span>
                                        </td>
                                        <td><?= h($d['endereco']) ?></td>
                                        <td>
                                            <span class="badge badge-status-coletado">
                                                <?= h($d['equipamento_frio']) ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php else: ?>
                        <div class="alert alert-info">
                            <span class="icon icon-calendar"></span>
                            Hoje não há coleta programada (fim de semana ou feriado).
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- ESTOQUE POR TIPO -->
                    <div class="card">
                        <div class="card-header">
                            <div>
                                <div class="card-title">📊 Estoque por Tipo de Leite</div>
                                <div class="card-subtitle">Frascos pasteurizados disponíveis</div>
                            </div>
                        </div>

                        <div style="display: flex; flex-direction: column; gap: var(--space-4);">
                            <!-- Colostro -->
                            <div style="display: flex; align-items: center; gap: var(--space-4);">
                                <div style="flex: 0 0 80px;">
                                    <span class="badge badge-colostro">COLOSTRO</span>
                                </div>
                                <div style="flex: 1; background: var(--blh-border-light); border-radius: var(--radius-full); height: 8px; overflow: hidden;">
                                    <div style="width: <?= $totalFrascos > 0 ? ($colostro / $totalFrascos * 100) : 0 ?>%; height: 100%; background: #D69E2E; border-radius: var(--radius-full);"></div>
                                </div>
                                <div style="flex: 0 0 40px; text-align: right; font-weight: 700;"><?= $colostro ?></div>
                            </div>

                            <!-- Transição -->
                            <div style="display: flex; align-items: center; gap: var(--space-4);">
                                <div style="flex: 0 0 80px;">
                                    <span class="badge badge-transicao">TRANSIÇÃO</span>
                                </div>
                                <div style="flex: 1; background: var(--blh-border-light); border-radius: var(--radius-full); height: 8px; overflow: hidden;">
                                    <div style="width: <?= $totalFrascos > 0 ? ($transicao / $totalFrascos * 100) : 0 ?>%; height: 100%; background: var(--blh-blue); border-radius: var(--radius-full);"></div>
                                </div>
                                <div style="flex: 0 0 40px; text-align: right; font-weight: 700;"><?= $transicao ?></div>
                            </div>

                            <!-- Maduro -->
                            <div style="display: flex; align-items: center; gap: var(--space-4);">
                                <div style="flex: 0 0 80px;">
                                    <span class="badge badge-maduro">MADURO</span>
                                </div>
                                <div style="flex: 1; background: var(--blh-border-light); border-radius: var(--radius-full); height: 8px; overflow: hidden;">
                                    <div style="width: <?= $totalFrascos > 0 ? ($maduro / $totalFrascos * 100) : 0 ?>%; height: 100%; background: var(--blh-green); border-radius: var(--radius-full);"></div>
                                </div>
                                <div style="flex: 0 0 40px; text-align: right; font-weight: 700;"><?= $maduro ?></div>
                            </div>
                        </div>

                        <div style="margin-top: var(--space-5); padding-top: var(--space-4); border-top: 1px solid var(--blh-border-light); text-align: center;">
                            <a href="../nutri/estoque.php" class="btn btn-sm btn-outline">Ver estoque completo</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SEÇÃO: ÚLTIMAS DISTRIBUIÇÕES + PEDIDOS PENDENTES -->
            <div class="dashboard-section">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-5);">

                    <!-- ÚLTIMAS DISTRIBUIÇÕES -->
                    <div class="card">
                        <div class="card-header">
                            <div>
                                <div class="card-title">🚚 Últimas Distribuições</div>
                                <div class="card-subtitle">Rastreabilidade ponta a ponta</div>
                            </div>
                        </div>

                        <?php if (count($ultimasDist) > 0): ?>
                        <div class="table-container">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Frasco</th>
                                        <th>Doadora</th>
                                        <th>Receptor</th>
                                        <th>Data</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($ultimasDist as $dist):
                                        $frasco = buscarPorId($frascos, $dist['id_frasco']);
                                        $doadora = buscarPorId($doadoras, $frasco['id_doadora']);
                                        $receptor = buscarPorId($receptores, $dist['id_receptor']);
                                    ?>
                                    <tr>
                                        <td>
                                            <span class="badge badge-status-distribuido">#<?= h($dist['id_frasco']) ?></span>
                                            <br><span class="text-xs text-muted"><?= h(labelTipo($frasco['tipo_leite'])) ?></span>
                                        </td>
                                        <td><?= h($doadora['nome']) ?></td>
                                        <td>
                                            <strong><?= h($receptor['nome_rn']) ?></strong>
                                            <br><span class="text-xs text-muted"><?= h($receptor['leito']) ?></span>
                                        </td>
                                        <td><?= h(fmtData($dist['data_entrega'])) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php else: ?>
                        <div class="alert alert-info">
                            Nenhuma distribuição registrada ainda.
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- PEDIDOS PENDENTES -->
                    <div class="card">
                        <div class="card-header">
                            <div>
                                <div class="card-title">👶 Pedidos Pendentes</div>
                                <div class="card-subtitle">Aguardando separação de frascos</div>
                            </div>
                            <a href="../nutri/nova_solicit.php" class="btn btn-sm btn-secondary">Novo pedido</a>
                        </div>

                        <?php
                        $pedidosPend = filtrarPor($receptores, 'status', 'aguardando');
                        if (count($pedidosPend) > 0):
                        ?>
                        <div class="table-container">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>RN</th>
                                        <th>Tipo</th>
                                        <th>Volume</th>
                                        <th>Hospital</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($pedidosPend as $p): ?>
                                    <tr>
                                        <td>
                                            <strong><?= h($p['nome_rn']) ?></strong>
                                            <br><span class="text-xs text-muted"><?= h($p['leito']) ?></span>
                                        </td>
                                        <td>
                                            <span class="badge badge-<?= $p['tipo_leite'] === 'C' ? 'colostro' : ($p['tipo_leite'] === 'T' ? 'transicao' : 'maduro') ?>">
                                                <?= h(labelTipo($p['tipo_leite'])) ?>
                                            </span>
                                        </td>
                                        <td><?= h($p['volume_ml']) ?> mL</td>
                                        <td><?= h($p['hospital']) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php else: ?>
                        <div class="alert alert-success">
                            <span class="icon icon-check"></span>
                            Todos os pedidos foram atendidos! 🎉
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- SEÇÃO: RASTREABILIDADE RÁPIDA -->
            <div class="dashboard-section">
                <div class="card">
                    <div class="card-header">
                        <div>
                            <div class="card-title">🔍 Rastreabilidade Rápida</div>
                            <div class="card-subtitle">Consulte a cadeia completa doadora → bebê</div>
                        </div>
                    </div>

                    <form action="rastreabilidade.php" method="GET" style="display: flex; gap: var(--space-3); max-width: 500px;">
                        <input type="number" name="id_frasco" class="form-input" placeholder="Número do frasco..." min="1" required style="flex: 1;">
                        <button type="submit" class="btn btn-primary">Rastrear</button>
                    </form>

                    <p class="text-muted text-sm" style="margin-top: var(--space-3);">
                        Digite o número do frasco para ver: doadora → coleta → pasteurização → análises → distribuição → receptor
                    </p>
                </div>
            </div>

            <!-- FOOTER -->
            <footer style="text-align: center; padding: var(--space-8) 0 var(--space-4); color: var(--blh-text-muted); font-size: var(--font-size-xs);">
                <p>Banco de Leite Humano de Marília · Sistema de Gestão e Rastreabilidade</p>
                <p style="margin-top: var(--space-1);">Hack_Health 2025 · Desenvolvido com 💙 para salvar vidas</p>
            </footer>

        </div>
    </main>

</div>
</body>
</html>