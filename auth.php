<?php
// ============================================================
//  BANCO DE LEITE HUMANO — MARÍLIA
//  auth.php — sessão, login e controle de perfil
//  Chamar session_start() ANTES de qualquer output HTML
// ============================================================

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ------------------------------------------------------------
// Verifica se o usuário está logado.
// Se não estiver, redireciona para o login.
// Chamar no topo de TODA página protegida.
// ------------------------------------------------------------
function verificarLogin() {
    if (!isset($_SESSION['usuario'])) {
        header('Location: /HackHealth/index.php');
        exit;
    }
}

// ------------------------------------------------------------
// Verifica se o usuário tem o perfil exigido.
// Chamar depois de verificarLogin().
// Aceita string ou array de perfis permitidos.
// ------------------------------------------------------------
function verificarPerfil($perfisPermitidos) {
    verificarLogin();
    $perfil = $_SESSION['usuario']['perfil'];
    if (is_string($perfisPermitidos)) {
        $perfisPermitidos = [$perfisPermitidos];
    }
    if (!in_array($perfil, $perfisPermitidos)) {
        header('Location: /HackHealth/index.php?erro=acesso');
        exit;
    }
}

// ------------------------------------------------------------
// Retorna o array do usuário logado (ou null)
// ------------------------------------------------------------
function usuarioLogado() {
    return $_SESSION['usuario'] ?? null;
}

// ------------------------------------------------------------
// Tenta fazer login. Retorna true/false.
// ------------------------------------------------------------
function tentarLogin($login, $senha, $usuarios) {
    foreach ($usuarios as $u) {
        if ($u['login'] === $login && $u['senha'] === $senha) {
            $_SESSION['usuario'] = $u;
            return true;
        }
    }
    return false;
}

// ------------------------------------------------------------
// Redireciona para a página correta conforme perfil
// ------------------------------------------------------------
function redirecionarPorPerfil($perfil) {
    $destinos = [
        'admin'          => '/HackHealth/admin/dashboard.php',
        'coletador'      => '/HackHealth/coletador/rota.php',
        'nutricionista'  => '/HackHealth/nutri/estoque.php',
    ];
    $url = $destinos[$perfil] ?? '/HackHealth/index.php';
    header("Location: {$url}");
    exit;
}

// ------------------------------------------------------------
// Logout
// ------------------------------------------------------------
function logout() {
    $_SESSION = [];
    session_destroy();
    header('Location: /HackHealth/index.php?msg=logout');
    exit;
}   