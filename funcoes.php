<?php
// ============================================================
//  BANCO DE LEITE HUMANO — MARÍLIA
//  funcoes.php — helpers de busca, filtro e formatação
//  Incluir APÓS dados.php (depende dos arrays globais)
//  ATENÇÃO: Não incluir auth.php ou dados.php aqui.
// ============================================================

// ------------------------------------------------------------
// BUSCA E FILTRO
// ------------------------------------------------------------

function buscarPorId($array, $id) {
    foreach ($array as $item) {
        if ((int)$item['id'] === (int)$id) return $item;
    }
    return null;
}

function filtrarPor($array, $campo, $valor) {
    return array_values(array_filter($array, fn($item) => $item[$campo] == $valor));
}

function filtrarExceto($array, $campo, $valor) {
    return array_values(array_filter($array, fn($item) => $item[$campo] != $valor));
}

// ------------------------------------------------------------
// DOADORAS
// ------------------------------------------------------------

function zonaDodia() {
    $zonas = [
        1 => 'Norte',
        2 => 'Sul',
        3 => 'Oeste',
        4 => 'Leste',
        5 => 'Rural',
    ];
    return $zonas[(int)date('N')] ?? null;
}

function diaDaZona($zona) {
    $dias = [
        'Norte'  => 'Segunda-feira',
        'Sul'    => 'Terça-feira',
        'Oeste'  => 'Quarta-feira',
        'Leste'  => 'Quinta-feira',
        'Rural'  => 'Sexta-feira',
    ];
    return $dias[$zona] ?? $zona;
}

function doadorasDoDia($doadoras) {
    $zona = zonaDodia();
    if (!$zona) return [];
    return filtrarPor($doadoras, 'zona', $zona);
}

// ------------------------------------------------------------
// FRASCOS
// ------------------------------------------------------------

function labelTipo($codigo) {
    return ['C' => 'Colostro', 'T' => 'Transição', 'M' => 'Maduro'][$codigo] ?? $codigo;
}

function corTipo($codigo) {
    return ['C' => 'badge-colostro', 'T' => 'badge-transicao', 'M' => 'badge-maduro'][$codigo] ?? '';
}

function classificarCaloria($crem) {
    if ($crem >= 6.0) return 'Hipercalórico';
    if ($crem > 0 && $crem <= 3.0) return 'Hipocalórico';
    if ($crem > 3.0) return 'Normocalórico';
    return '—';
}

function labelStatus($status) {
    $map = [
        'coletado'    => 'Coletado',
        'pasteurizado'=> 'Pasteurizado',
        'distribuido' => 'Distribuído',
        'descartado'  => 'Descartado',
    ];
    return $map[$status] ?? ucfirst($status);
}

function corStatus($status) {
    $map = [
        'coletado'    => 'status-coletado',
        'pasteurizado'=> 'status-pasteurizado',
        'distribuido' => 'status-distribuido',
        'descartado'  => 'status-descartado',
    ];
    return $map[$status] ?? '';
}

function tempOk($temp) {
    return $temp >= -5 && $temp <= 8;
}

// ------------------------------------------------------------
// RASTREABILIDADE
// ------------------------------------------------------------

function rastrearFrasco($id_frasco, $frascos, $doadoras, $distribuicoes, $receptores) {
    $frasco = buscarPorId($frascos, $id_frasco);
    if (!$frasco) return null;

    $doadora = buscarPorId($doadoras, $frasco['id_doadora']);

    $distribuicao = null;
    $receptor = null;
    foreach ($distribuicoes as $d) {
        if ((int)$d['id_frasco'] === (int)$id_frasco) {
            $distribuicao = $d;
            $receptor = buscarPorId($receptores, $d['id_receptor']);
            break;
        }
    }

    return [
        'frasco'      => $frasco,
        'doadora'     => $doadora,
        'distribuicao'=> $distribuicao,
        'receptor'    => $receptor,
    ];
}

// ------------------------------------------------------------
// ESTATÍSTICAS
// ------------------------------------------------------------

function totalPorStatus($frascos, $status) {
    return count(filtrarPor($frascos, 'status', $status));
}

function totalPorTipo($frascos, $tipo) {
    return count(filtrarPor($frascos, 'tipo_leite', $tipo));
}

function doadorasAtivas($doadoras) {
    return count(filtrarPor($doadoras, 'status', 'ativa'));
}

function pedidosAguardando($receptores) {
    return count(filtrarPor($receptores, 'status', 'aguardando'));
}

// ------------------------------------------------------------
// FORMATAÇÃO
// ------------------------------------------------------------

function fmtData($data) {
    if (!$data) return '—';
    $d = DateTime::createFromFormat('Y-m-d', $data);
    return $d ? $d->format('d/m/Y') : $data;
}

function fmtTemp($temp) {
    if ($temp === '' || $temp === null) return '—';
    $ok = tempOk($temp);
    $cor = $ok ? 'color:var(--color-success)' : 'color:var(--color-danger)';
    return '<span style="' . $cor . '">' . $temp . '°C</span>';
}

function h($str) {
    return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8');
}