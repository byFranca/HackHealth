/**
 * BLH Marilia — Login
 * Scripts da tela de autenticacao
 */

(function() {
    'use strict';

    // Elementos do DOM
    const form = document.querySelector('.blh-login-form');
    const perfilSelect = document.getElementById('blh-perfil');
    const cpfEmailInput = document.getElementById('blh-cpf-email');
    const senhaInput = document.getElementById('blh-senha');
    const forgotLink = document.querySelector('.blh-login-forgot-link');

    // Dados de demonstracao (simulacao de backend)
    const usuariosDemo = {
        'administrador': { login: 'admin', senha: '1234' },
        'nutricionista': { login: 'carla', senha: '1234' },
        'coletador': { login: 'marcos', senha: '1234' }
    };

    // Perfis e seus redirecionamentos
    const redirecionamentos = {
        'administrador': 'admin/dashboard.html',
        'nutricionista': 'nutri/estoque.html',
        'coletador': 'coletador/coletas.html'
    };

    /**
     * Validacao de CPF ou E-mail
     */
    function validarIdentificador(valor) {
        // Remove caracteres nao numericos para CPF
        const numeros = valor.replace(/\D/g, '');

        // Se tem 11 digitos, e CPF
        if (numeros.length === 11) {
            return validarCPF(numeros);
        }

        // Se contem @, e e-mail
        if (valor.includes('@')) {
            return validarEmail(valor);
        }

        return false;
    }

    /**
     * Validacao basica de CPF
     */
    function validarCPF(cpf) {
        if (cpf.length !== 11) return false;

        // Verifica digitos repetidos
        if (/^(\d)\1{10}$/.test(cpf)) return false;

        // Calculo dos digitos verificadores
        let soma = 0;
        for (let i = 0; i < 9; i++) {
            soma += parseInt(cpf.charAt(i)) * (10 - i);
        }
        let resto = 11 - (soma % 11);
        let digito1 = resto === 10 || resto === 11 ? 0 : resto;

        if (digito1 !== parseInt(cpf.charAt(9))) return false;

        soma = 0;
        for (let i = 0; i < 10; i++) {
            soma += parseInt(cpf.charAt(i)) * (11 - i);
        }
        resto = 11 - (soma % 11);
        let digito2 = resto === 10 || resto === 11 ? 0 : resto;

        return digito2 === parseInt(cpf.charAt(10));
    }

    /**
     * Validacao de e-mail
     */
    function validarEmail(email) {
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    }

    /**
     * Formata CPF durante digitacao
     */
    function formatarCPF(valor) {
        const numeros = valor.replace(/\D/g, '');
        if (numeros.length <= 11) {
            return numeros.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
        }
        return valor;
    }

    /**
     * Exibe mensagem de erro no campo
     */
    function mostrarErro(input, mensagem) {
        // Remove erro anterior se existir
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
        document.querySelectorAll('.blh-login-input-erro').forEach(input => {
            removerErro(input);
        });
    }

    /**
     * Simula login (sem backend)
     */
    function simularLogin(perfil, login, senha) {
        const demo = usuariosDemo[perfil];

        // Em producao real, isso seria uma chamada AJAX
        return new Promise((resolve, reject) => {
            setTimeout(() => {
                if (login === demo.login && senha === demo.senha) {
                    resolve({
                        sucesso: true,
                        perfil: perfil,
                        nome: perfil === 'administrador' ? 'Dra. Ana Carvalho' : 
                              perfil === 'nutricionista' ? 'Nutr. Carla Souza' : 
                              'Marcos Silva',
                        redirect: redirecionamentos[perfil]
                    });
                } else {
                    reject({ mensagem: 'Login ou senha incorretos.' });
                }
            }, 500); // Simula delay de rede
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
        localStorage.setItem('blh_sessao', JSON.stringify(sessao));
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

        // Valida identificador
        if (!identificador) {
            mostrarErro(cpfEmailInput, 'Informe seu CPF ou e-mail.');
            valido = false;
        } else if (!validarIdentificador(identificador)) {
            mostrarErro(cpfEmailInput, 'CPF ou e-mail invalido.');
            valido = false;
        }

        // Valida senha
        if (!senha) {
            mostrarErro(senhaInput, 'Informe sua senha.');
            valido = false;
        } else if (senha.length < 4) {
            mostrarErro(senhaInput, 'Senha deve ter pelo menos 4 caracteres.');
            valido = false;
        }

        if (!valido) return;

        // Feedback visual no botao
        const btn = form.querySelector('.blh-login-submit-btn');
        const textoOriginal = btn.textContent;
        btn.textContent = 'Entrando...';
        btn.disabled = true;
        btn.style.opacity = '0.8';

        // Simula login
        simularLogin(perfil, identificador.replace(/\D/g, ''), senha)
            .then(dados => {
                salvarSessao(dados);
                btn.textContent = 'Redirecionando...';

                // Redireciona apos breve delay
                setTimeout(() => {
                    window.location.href = dados.redirect;
                }, 300);
            })
            .catch(erro => {
                btn.textContent = textoOriginal;
                btn.disabled = false;
                btn.style.opacity = '1';

                mostrarErro(cpfEmailInput, erro.mensagem);
                mostrarErro(senhaInput, '');
            });
    }

    /**
     * Handler do "Esqueci minha senha"
     */
    function handleForgot(event) {
        event.preventDefault();
        alert('Funcionalidade em desenvolvimento.\nEntre em contato com o administrador do sistema.');
    }

    /**
     * Formata CPF durante digitacao
     */
    function handleCpfInput(event) {
        const valor = event.target.value;
        const numeros = valor.replace(/\D/g, '');

        if (numeros.length <= 11 && !valor.includes('@')) {
            event.target.value = formatarCPF(valor);
        }
    }

    /**
     * Remove formatacao ao perder foco (se for e-mail)
     */
    function handleCpfBlur(event) {
        const valor = event.target.value;
        if (valor.includes('@')) {
            // E e-mail, nao formata
            return;
        }
    }

    // Event Listeners
    if (form) {
        form.addEventListener('submit', handleSubmit);
    }

    if (forgotLink) {
        forgotLink.addEventListener('click', handleForgot);
    }

    if (cpfEmailInput) {
        cpfEmailInput.addEventListener('input', handleCpfInput);
        cpfEmailInput.addEventListener('blur', handleCpfBlur);

        // Remove erro ao digitar
        cpfEmailInput.addEventListener('input', () => removerErro(cpfEmailInput));
    }

    if (senhaInput) {
        senhaInput.addEventListener('input', () => removerErro(senhaInput));
    }

    // Foco automatico no primeiro campo
    if (cpfEmailInput) {
        cpfEmailInput.focus();
    }

    console.log('BLH Marilia — Login carregado');

})();