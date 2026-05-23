/**
 * BLH Marilia — Login
 * Scripts da tela de autenticacao
 */

(function () {
    'use strict';

    // Elementos do DOM
    const form = document.querySelector('.blh-login-form');
    const perfilSelect = document.getElementById('blh-perfil');
    const cpfEmailInput = document.getElementById('blh-cpf-email');
    const senhaInput = document.getElementById('blh-senha');
    const forgotLink = document.querySelector('.blh-login-forgot-link');

    // Dados de demonstracao (simulacao de backend)
    const usuariosDemo = {
        administrador: { login: 'admin', senha: '1234' },
        nutricionista: { login: 'carla', senha: '1234' },
        coletador: { login: 'marcos', senha: '1234' }
    };

    // Perfis e seus redirecionamentos
    const redirecionamentos = {
        administrador: 'admin/dashboard.html',
        nutricionista: 'nutri/estoque.html',
        coletador: 'coletador/coletas.html'
    };

    /**
     * Exibe mensagem de erro no campo
     */
    function mostrarErro(input, mensagem) {
        removerErro(input);

        input.classList.add('blh-login-input-erro');

        const erroEl = document.createElement('span');
        erroEl.className = 'blh-login-erro-msg';
        erroEl.textContent = mensagem;
        erroEl.style.cssText = `
            color: #e53e3e;
            font-size: 12px;
            margin-top: 4px;
            display: block;
        `;

        input.parentElement.parentElement.appendChild(erroEl);
    }

    /**
     * Remove mensagem de erro
     */
    function removerErro(input) {
        input.classList.remove('blh-login-input-erro');

        const container = input.parentElement.parentElement;
        const erroExistente = container.querySelector('.blh-login-erro-msg');

        if (erroExistente) {
            erroExistente.remove();
        }
    }

    /**
     * Limpa todos os erros
     */
    function limparErros() {
        document
            .querySelectorAll('.blh-login-input-erro')
            .forEach(input => removerErro(input));
    }

    /**
     * Simula login (sem backend)
     */
    function simularLogin(perfil, login, senha) {
        const demo = usuariosDemo[perfil];

        return new Promise((resolve, reject) => {
            setTimeout(() => {
                if (
                    demo &&
                    login === demo.login &&
                    senha === demo.senha
                ) {
                    resolve({
                        sucesso: true,
                        perfil: perfil,
                        nome:
                            perfil === 'administrador'
                                ? 'Dra. Ana Carvalho'
                                : perfil === 'nutricionista'
                                ? 'Nutr. Carla Souza'
                                : 'Marcos Silva',
                        login: login,
                        redirect: redirecionamentos[perfil]
                    });
                } else {
                    reject({
                        mensagem: 'Login ou senha incorretos.'
                    });
                }
            }, 500);
        });
    }

    /**
     * Salva sessao no localStorage
     */
    function salvarSessao(dados) {
        const sessao = {
            perfil: dados.perfil,
            nome: dados.nome,
            login: dados.login,
            timestamp: Date.now()
        };

        localStorage.setItem(
            'blh_sessao',
            JSON.stringify(sessao)
        );
    }

    /**
     * Handler do submit do formulario
     */
    function handleSubmit(event) {
        event.preventDefault();
        limparErros();

        const perfil = perfilSelect.value;
        const identificador = cpfEmailInput.value.trim();
        const senha = senhaInput.value;

        let valido = true;

        // Apenas verifica se o login foi preenchido
        if (!identificador) {
            mostrarErro(
                cpfEmailInput,
                'Informe seu login.'
            );
            valido = false;
        }

        // Valida senha
        if (!senha) {
            mostrarErro(
                senhaInput,
                'Informe sua senha.'
            );
            valido = false;
        } else if (senha.length < 4) {
            mostrarErro(
                senhaInput,
                'Senha deve ter pelo menos 4 caracteres.'
            );
            valido = false;
        }

        if (!valido) return;

        // Feedback visual no botao
        const btn = form.querySelector(
            '.blh-login-submit-btn'
        );

        const textoOriginal = btn.textContent;

        btn.textContent = 'Entrando...';
        btn.disabled = true;
        btn.style.opacity = '0.8';

        // Simula login
        simularLogin(
            perfil,
            identificador,
            senha
        )
            .then(dados => {
                salvarSessao(dados);

                btn.textContent =
                    'Redirecionando...';

                setTimeout(() => {
                    window.location.href =
                        "dashboard.html"
                }, 300);
            })
            .catch(erro => {
                btn.textContent =
                    textoOriginal;

                btn.disabled = false;
                btn.style.opacity = '1';

                mostrarErro(
                    cpfEmailInput,
                    erro.mensagem
                );
            });
    }

    /**
     * Handler do "Esqueci minha senha"
     */
    function handleForgot(event) {
        event.preventDefault();

        alert(
            'Funcionalidade em desenvolvimento.\nEntre em contato com o administrador do sistema.'
        );
    }

    // Event Listeners
    if (form) {
        form.addEventListener(
            'submit',
            handleSubmit
        );
    }

    if (forgotLink) {
        forgotLink.addEventListener(
            'click',
            handleForgot
        );
    }

    if (cpfEmailInput) {
        // Remove erro ao digitar
        cpfEmailInput.addEventListener(
            'input',
            () => removerErro(cpfEmailInput)
        );
    }

    if (senhaInput) {
        senhaInput.addEventListener(
            'input',
            () => removerErro(senhaInput)
        );
    }

    // Foco automatico no primeiro campo
    if (cpfEmailInput) {
        cpfEmailInput.focus();
    }

    console.log(
        'Banco de leite humano — Login carregado'
    );
})();