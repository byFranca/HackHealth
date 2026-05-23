<?php
require 'dados.php';
require 'funcoes.php';
require 'auth.php';

// Se já está logado, manda direto pro destino
if (isset($_SESSION['usuario'])) {
    redirecionarPorPerfil($_SESSION['usuario']['perfil']);
}

$erro = '';
$msg  = '';

// Mensagem de logout
if (isset($_GET['msg']) && $_GET['msg'] === 'logout') {
    $msg = 'Você saiu do sistema com sucesso.';
}

// Mensagem de acesso negado
if (isset($_GET['erro']) && $_GET['erro'] === 'acesso') {
    $erro = 'Você não tem permissão para acessar essa página.';
}

// Processar POST do formulário de login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $senha = trim($_POST['senha'] ?? '');

    if (tentarLogin($login, $senha, $usuarios)) {
        redirecionarPorPerfil($_SESSION['usuario']['perfil']);
    } else {
        $erro = 'Login ou senha incorretos. Tente novamente.';
    }
}

// Tratar logout
if (isset($_GET['logout']) && $_GET['logout'] == '1') {
    logout();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="global.css">
    <title>BLH Marília — Login</title>
    <style>
        /* Estilos específicos da página de login */
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 24px;
        }
        .login-card {
            background: var(--blh-bg-card);
            border-radius: var(--radius-lg);
            padding: 48px 40px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 2px 16px var(--blh-shadow);
            border: 1px solid var(--blh-border-light);
        }
        .login-logo {
            text-align: center;
            margin-bottom: 32px;
        }
        .login-logo img {
            width: 80px;
            height: 80px;
            border-radius: var(--radius-lg);
            margin-bottom: 16px;
        }
        .login-logo h1 {
            font-size: var(--font-size-lg);
            font-weight: 600;
            color: var(--blh-text);
            line-height: 1.3;
        }
        .login-logo p {
            font-size: var(--font-size-sm);
            color: var(--blh-text-muted);
            margin-top: 4px;
        }
        .form-group {
            margin-bottom: var(--space-4);
        }
        .form-group label {
            display: block;
            font-size: var(--font-size-sm);
            font-weight: 500;
            color: var(--blh-text);
            margin-bottom: var(--space-2);
        }
        .form-group input {
            width: 100%;
            padding: var(--space-3) var(--space-4);
            border: 1.5px solid var(--blh-border);
            border-radius: var(--radius-md);
            font-size: var(--font-size-base);
            color: var(--blh-text);
            background: var(--blh-white);
            transition: border-color var(--transition-fast), box-shadow var(--transition-fast);
            outline: none;
        }
        .form-group input:focus {
            border-color: var(--blh-blue);
            box-shadow: 0 0 0 3px rgba(91, 163, 198, 0.15);
        }
        .btn-login {
            width: 100%;
            padding: var(--space-3);
            background: var(--blh-blue);
            color: var(--blh-white);
            border: none;
            border-radius: var(--radius-md);
            font-size: var(--font-size-base);
            font-weight: 600;
            cursor: pointer;
            margin-top: var(--space-2);
            transition: background var(--transition-fast);
        }
        .btn-login:hover {
            background: var(--blh-blue-dark);
        }
        .alerta {
            padding: var(--space-3) var(--space-4);
            border-radius: var(--radius-md);
            font-size: var(--font-size-sm);
            margin-bottom: var(--space-4);
        }
        .alerta-erro {
            background: var(--blh-red-light);
            color: var(--blh-red);
            border: 1px solid #FEB2B2;
        }
        .alerta-ok {
            background: var(--blh-green-light);
            color: #276749;
            border: 1px solid #9AE6B4;
        }
        .hint {
            margin-top: var(--space-6);
            padding: var(--space-4);
            background: var(--blh-bg);
            border-radius: var(--radius-md);
            font-size: var(--font-size-sm);
            color: var(--blh-text-muted);
        }
        .hint strong {
            color: var(--blh-text);
        }
        .hint-row {
            display: flex;
            gap: var(--space-2);
            align-items: center;
            margin-top: var(--space-1);
        }
        .badge-perfil {
            font-size: var(--font-size-xs);
            padding: 2px 8px;
            border-radius: var(--radius-full);
            font-weight: 500;
        }
        .p-admin { background: #EBF4FF; color: #2B6CB0; }
        .p-colet { background: var(--blh-yellow-light); color: #975A16; }
        .p-nutri { background: var(--blh-green-light); color: var(--blh-green); }
    </style>
</head>
<body>
<div class="login-card">
    <div class="login-logo">
        <img src="img-logo.jpg" alt="BLH Marília">
        <h1>Banco de Leite Humano<br>de Marília</h1>
        <p>Sistema de Gestão e Rastreabilidade</p>
    </div>

    <?php if ($erro): ?>
        <div class="alerta alerta-erro"><?= h($erro) ?></div>
    <?php endif; ?>

    <?php if ($msg): ?>
        <div class="alerta alerta-ok"><?= h($msg) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-group">
            <label for="login">Login</label>
            <input type="text" id="login" name="login"
                   value="<?= h($_POST['login'] ?? '') ?>"
                   placeholder="seu login" autofocus autocomplete="username">
        </div>

        <div class="form-group">
            <label for="senha">Senha</label>
            <input type="password" id="senha" name="senha"
                   placeholder="sua senha" autocomplete="current-password">
        </div>

        <button type="submit" class="btn-login">Entrar</button>
    </form>

    <div class="hint">
        <strong>Acessos de demonstração:</strong>
        <div class="hint-row">
            <span class="badge-perfil p-admin">admin</span>
            login: <strong>admin</strong> / senha: <strong>1234</strong>
        </div>
        <div class="hint-row">
            <span class="badge-perfil p-colet">coletador</span>
            login: <strong>marcos</strong> / senha: <strong>1234</strong>
        </div>
        <div class="hint-row">
            <span class="badge-perfil p-nutri">nutricionista</span>
            login: <strong>carla</strong> / senha: <strong>1234</strong>
        </div>
    </div>
</div>
</body>
</html>