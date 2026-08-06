{{-- "Por que escolher a nossa escola?" — Seção premium com layout assimétrico --}}

<section class="toro-why-section">
    <div class="container">
        <div class="toro-section-head">
            <span class="toro-section-badge">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                    <path d="M8 1L10 5.5L15 6.5L11.5 10L12.5 15L8 12.5L3.5 15L4.5 10L1 6.5L6 5.5L8 1Z" fill="currentColor"/>
                </svg>
                {{ get_phrase('Diferenciais') }}
            </span>
            <h2>{{ get_phrase('Por que escolher a nossa escola?') }}</h2>
            <p>{{ get_phrase('Nossa missão é clara: transformar vidas por meio da educação. Com uma metodologia eficaz e suporte próximo, ajudamos você a conquistar as certificações do mercado financeiro.') }}</p>
        </div>

        <div class="toro-why-grid-premium">
            {{-- Card destaque (maior) --}}
            <div class="toro-why-card-premium toro-why-card--featured">
                <div class="toro-why-card-bg"></div>
                <div class="toro-why-card-content">
                    <div class="toro-why-icon-premium">
                        <svg width="48" height="48" viewBox="0 0 48 48" fill="none">
                            <path d="M24 4L30 16L44 18L34 28L36 42L24 36L12 42L14 28L4 18L18 16L24 4Z" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M24 14L27 20L34 21L29 26L30 33L24 30L18 33L19 26L14 21L21 20L24 14Z" fill="currentColor" opacity="0.3"/>
                        </svg>
                        <div class="toro-why-icon-glow"></div>
                    </div>
                    <div class="toro-why-card-stat">
                        <span class="toro-stat-number">98%</span>
                        <span class="toro-stat-label">{{ get_phrase('de aprovação') }}</span>
                    </div>
                    <h3>{{ get_phrase('Metodologia própria de aprovação') }}</h3>
                    <p>{{ get_phrase('Conteúdo organizado por módulos, com revisões periódicas e foco exato no que as bancas cobram em cada exame. Nossa taxa de aprovação é a mais alta do mercado.') }}</p>
                    <div class="toro-why-card-features">
                        <span><i class="fas fa-check"></i> {{ get_phrase('Módulos estruturados') }}</span>
                        <span><i class="fas fa-check"></i> {{ get_phrase('Revisões estratégicas') }}</span>
                        <span><i class="fas fa-check"></i> {{ get_phrase('Foco na banca') }}</span>
                    </div>
                </div>
            </div>

            {{-- Cards regulares --}}
            <div class="toro-why-card-premium">
                <div class="toro-why-card-bg"></div>
                <div class="toro-why-card-content">
                    <div class="toro-why-icon-premium">
                        <svg width="32" height="32" viewBox="0 0 32 32" fill="none">
                            <circle cx="16" cy="12" r="5" stroke="currentColor" stroke-width="2"/>
                            <path d="M8 26C8 22.6863 10.6863 20 14 20H18C21.3137 20 24 22.6863 24 26" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            <path d="M22 10L26 10M24 8L24 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <h3>{{ get_phrase('Suporte 24/7 com professores') }}</h3>
                    <p>{{ get_phrase('Grupos exclusivos de alunos com acompanhamento dos professores. Você nunca estuda sozinho.') }}</p>
                </div>
            </div>

            <div class="toro-why-card-premium">
                <div class="toro-why-card-bg"></div>
                <div class="toro-why-card-content">
                    <div class="toro-why-icon-premium">
                        <svg width="32" height="32" viewBox="0 0 32 32" fill="none">
                            <rect x="6" y="6" width="20" height="20" rx="3" stroke="currentColor" stroke-width="2"/>
                            <path d="M10 16L14 20L22 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <h3>{{ get_phrase('Simulados atualizados') }}</h3>
                    <p>{{ get_phrase('Simulados no formato oficial dos exames, com banco de questões constantemente revisado.') }}</p>
                </div>
            </div>

            <div class="toro-why-card-premium">
                <div class="toro-why-card-bg"></div>
                <div class="toro-why-card-content">
                    <div class="toro-why-icon-premium">
                        <svg width="32" height="32" viewBox="0 0 32 32" fill="none">
                            <rect x="8" y="4" width="16" height="24" rx="2" stroke="currentColor" stroke-width="2"/>
                            <path d="M12 24H20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            <circle cx="16" cy="8" r="1" fill="currentColor"/>
                        </svg>
                    </div>
                    <h3>{{ get_phrase('Estude onde quiser') }}</h3>
                    <p>{{ get_phrase('Plataforma responsiva: aulas, apostilas e quizzes disponíveis no computador, tablet ou celular.') }}</p>
                </div>
            </div>

            <div class="toro-why-card-premium">
                <div class="toro-why-card-bg"></div>
                <div class="toro-why-card-content">
                    <div class="toro-why-icon-premium">
                        <svg width="32" height="32" viewBox="0 0 32 32" fill="none">
                            <path d="M8 6H24C25.1046 6 26 6.89543 26 8V24C26 25.1046 25.1046 26 24 26H8C6.89543 26 6 25.1046 6 24V8C6 6.89543 6.89543 6 8 6Z" stroke="currentColor" stroke-width="2"/>
                            <path d="M12 14L15 17L20 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M6 10H26" stroke="currentColor" stroke-width="2"/>
                        </svg>
                    </div>
                    <h3>{{ get_phrase('Certificado de conclusão') }}</h3>
                    <p>{{ get_phrase('Ao concluir o curso, você recebe seu certificado de participação para comprovar sua formação.') }}</p>
                </div>
            </div>

            <div class="toro-why-card-premium">
                <div class="toro-why-card-bg"></div>
                <div class="toro-why-card-content">
                    <div class="toro-why-icon-premium">
                        <svg width="32" height="32" viewBox="0 0 32 32" fill="none">
                            <path d="M6 24L12 18L16 22L26 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M20 10H26V16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <h3>{{ get_phrase('Acompanhe seu progresso') }}</h3>
                    <p>{{ get_phrase('Painel com seu desempenho em tempo real: aulas assistidas, acertos nos quizzes e evolução.') }}</p>
                </div>
            </div>
        </div>
    </div>
</section>
