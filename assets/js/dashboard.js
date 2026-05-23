/* ============================================
   BLH MARÍLIA - DASHBOARD SCRIPTS
   Banco de Leite Humano de Marília
   ============================================ */

document.addEventListener('DOMContentLoaded', function() {

    // ==========================================
    // CONFIGURAÇÕES GLOBAIS DO CHART.JS
    // ==========================================
    Chart.defaults.font.family = "'Segoe UI', system-ui, sans-serif";
    Chart.defaults.font.size = 12;
    Chart.defaults.color = '#7f8c8d';

    // ==========================================
    // GRÁFICO DONUT: ESTOQUE POR TIPO DE LEITE
    // ==========================================
    const estoqueCtx = document.getElementById('blh-dashboard-chart-estoque');

    if (estoqueCtx) {
        const estoqueData = {
            labels: ['Colostro', 'Transição', 'Maduro', 'Hipercalórico', 'Hipocalórico'],
            datasets: [{
                data: [12, 28, 67, 19, 16],
                backgroundColor: [
                    '#f39c12',   // Colostro - laranja
                    '#2e86ab',   // Transição - azul
                    '#27ae60',   // Maduro - verde
                    '#9b59b6',   // Hipercalórico - roxo
                    '#e91e63'    // Hipocalórico - rosa
                ],
                borderWidth: 0,
                hoverOffset: 6
            }]
        };

        new Chart(estoqueCtx, {
            type: 'doughnut',
            data: estoqueData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: {
                        display: false  // Legend customizada via HTML
                    },
                    tooltip: {
                        backgroundColor: '#2c3e50',
                        padding: 10,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                return ` ${context.label}: ${context.parsed} frascos`;
                            }
                        }
                    }
                },
                animation: {
                    animateRotate: true,
                    animateScale: false,
                    duration: 1200,
                    easing: 'easeOutQuart'
                }
            }
        });

        // Gerar legend customizada
        const legendContainer = document.getElementById('blh-dashboard-legend-estoque');
        const colors = ['#f39c12', '#2e86ab', '#27ae60', '#9b59b6', '#e91e63'];
        const values = [12, 28, 67, 19, 16];

        estoqueData.labels.forEach((label, index) => {
            const item = document.createElement('div');
            item.className = 'blh-dashboard-legend-item';
            item.innerHTML = `
                <span class="blh-dashboard-legend-color" style="background-color: ${colors[index]}"></span>
                <span class="blh-dashboard-legend-label">${label}</span>
                <span class="blh-dashboard-legend-value">${values[index]}</span>
            `;
            legendContainer.appendChild(item);
        });
    }

    // ==========================================
    // GRÁFICO DE BARRAS: COLETAS DA SEMANA
    // ==========================================
    const coletasCtx = document.getElementById('blh-dashboard-chart-coletas');

    if (coletasCtx) {
        new Chart(coletasCtx, {
            type: 'bar',
            data: {
                labels: ['Seg', 'Ter', 'Qua', 'Qui', 'Sex'],
                datasets: [{
                    label: 'Coletas',
                    data: [8, 12, 7, 15, 10],
                    backgroundColor: '#2e86ab',
                    borderRadius: 6,
                    borderSkipped: false,
                    barThickness: 32,
                    maxBarThickness: 40
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#2c3e50',
                        padding: 10,
                        cornerRadius: 8,
                        callbacks: {
                            title: function() { return ''; },
                            label: function(context) {
                                return ` ${context.parsed.y} coletas`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#7f8c8d',
                            font: {
                                size: 12
                            }
                        },
                        border: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        max: 16,
                        ticks: {
                            stepSize: 4,
                            color: '#7f8c8d',
                            font: {
                                size: 11
                            }
                        },
                        grid: {
                            color: '#f0f0f0',
                            drawBorder: false
                        },
                        border: {
                            display: false
                        }
                    }
                },
                animation: {
                    duration: 1200,
                    easing: 'easeOutQuart'
                }
            }
        });
    }

    // ==========================================
    // ANIMAÇÃO DE CONTAGEM DOS KPIs
    // ==========================================
    const kpiValues = document.querySelectorAll('.blh-dashboard-kpi-value');

    kpiValues.forEach(element => {
        const text = element.textContent.trim();
        const numericMatch = text.match(/[\d,]+/);

        if (numericMatch) {
            const targetValue = parseFloat(numericMatch[0].replace(',', '.'));
            const suffix = text.replace(numericMatch[0], '').trim();
            const isDecimal = text.includes(',');

            let currentValue = 0;
            const duration = 1500;
            const steps = 60;
            const increment = targetValue / steps;
            const stepDuration = duration / steps;

            const counter = setInterval(() => {
                currentValue += increment;

                if (currentValue >= targetValue) {
                    currentValue = targetValue;
                    clearInterval(counter);
                }

                let displayValue;
                if (isDecimal) {
                    displayValue = currentValue.toFixed(1).replace('.', ',');
                } else {
                    displayValue = Math.floor(currentValue);
                }

                element.innerHTML = displayValue + (suffix ? ` <span class="blh-dashboard-kpi-unit">${suffix}</span>` : '');
            }, stepDuration);
        }
    });

    // ==========================================
    // INTERATIVIDADE: HOVER NOS CARDS DE LISTA
    // ==========================================
    const listItems = document.querySelectorAll('.blh-dashboard-list-item');

    listItems.forEach(item => {
        item.addEventListener('click', function() {
            // Simulação de interação - pode ser expandida
            this.style.transform = 'scale(0.98)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 150);
        });
    });

    // ==========================================
    // NOTIFICAÇÕES: CLICK NO SINO
    // ==========================================
    const notificationsBtn = document.querySelector('.blh-dashboard-notifications-btn');

    if (notificationsBtn) {
        notificationsBtn.addEventListener('click', function() {
            // Simulação de abertura de notificações
            const badge = this.querySelector('.blh-dashboard-notifications-badge');
            if (badge) {
                badge.style.transform = 'scale(1.3)';
                setTimeout(() => {
                    badge.style.transform = 'scale(1)';
                }, 200);
            }

            // Aqui você pode abrir um dropdown/modal de notificações
            console.log('Notificações abertas');
        });
    }

    // ==========================================
    // RESPONSIVIDADE: MENU MOBILE
    // ==========================================
    // Verifica se precisa de menu hamburguer em telas pequenas
    const sidebar = document.querySelector('.blh-dashboard-sidebar');
    const main = document.querySelector('.blh-dashboard-main');

    function checkMobile() {
        if (window.innerWidth <= 768) {
            sidebar.classList.add('blh-dashboard-sidebar--mobile');
        } else {
            sidebar.classList.remove('blh-dashboard-sidebar--mobile');
            sidebar.classList.remove('blh-dashboard-sidebar--open');
        }
    }

    window.addEventListener('resize', checkMobile);
    checkMobile();

    // ==========================================
    // ATUALIZAÇÃO DINÂMICA DOS DADOS (SIMULAÇÃO)
    // ==========================================
    // Função para atualizar dados periodicamente (opcional)
    function atualizarDados() {
        // Aqui você pode fazer fetch para uma API
        // Por enquanto, apenas log
        console.log('Dados atualizados em:', new Date().toLocaleTimeString());
    }

    // Atualizar a cada 5 minutos (300000ms)
    // setInterval(atualizarDados, 300000);

});