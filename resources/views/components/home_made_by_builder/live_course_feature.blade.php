<section class="live-course-features-section section-padding" id="lcfSection">
    <div class="container">

        <!-- TOP ROW -->
        <div class="row g-4 align-items-stretch">
            <div class="col-lg-5">
                <div class="lcf-left-block">
                    <h2 class="lcf-main-title">{{ get_phrase('O que você ganha nos nossos cursos ao vivo') }}</h2>
                    <p class="lcf-main-desc">{{ get_phrase('Veja o que você ganha ao se juntar aos cursos da nossa plataforma') }}</p>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="lcf-card">
                    <div class="lcf-card-inner">
                        <div class="lcf-icon-box">
                            <svg width="26" height="26" viewBox="0 0 24 24" fill="none">
                                <path d="M2 8.5C2 6.57 3.57 5 5.5 5h13C20.43 5 22 6.57 22 8.5v7C22 17.43 20.43 19 18.5 19h-13C3.57 19 2 17.43 2 15.5v-7z" stroke="#fff" stroke-width="1.6" fill="none" stroke-linejoin="round" />
                                <path d="M9 9l5 3-5 3V9z" fill="#fff" />
                                <circle cx="19.5" cy="7" r="1" fill="#fff" />
                                <circle cx="19.5" cy="17" r="1" fill="#fff" />
                                <circle cx="4.5" cy="7" r="1" fill="#fff" />
                                <circle cx="4.5" cy="17" r="1" fill="#fff" />
                            </svg>
                        </div>
                        <h3 class="lcf-card-title">{{ get_phrase('Aprenda ao vivo com especialistas do mercado') }}</h3>
                        <p class="lcf-card-desc">{{ get_phrase('Ao concluir o curso, você recebe suporte integrado de recolocação profissional com consultoria vitalícia, construção de perfil e candidaturas a vagas.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- BOTTOM ROW -->
        <div class="row g-4 mt-2">
            <div class="col-lg-4 col-md-6">
                <div class="lcf-card">
                    <div class="lcf-card-inner">
                        <div class="lcf-icon-box">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect x="3" y="3" width="18" height="18" rx="3" stroke="#fff" stroke-width="1.7" fill="none" />
                                <path d="M7.5 8h9M7.5 12h6M7.5 16h3" stroke="#fff" stroke-width="1.7" stroke-linecap="round" />
                            </svg>
                        </div>
                        <h3 class="lcf-card-title">{{ get_phrase('Plano de estudos por módulos') }}</h3>
                        <p class="lcf-card-desc">{{ get_phrase('Plano de estudos organizado em módulos, com quizzes, tarefas e provas ao vivo — nada de estudar na correria.') }}</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="lcf-card">
                    <div class="lcf-card-inner">
                        <div class="lcf-icon-box">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <circle cx="12" cy="12" r="8.5" stroke="#fff" stroke-width="1.7" fill="none" />
                                <circle cx="12" cy="12" r="4.5" stroke="#fff" stroke-width="1.7" fill="none" />
                                <circle cx="12" cy="12" r="1.3" fill="#fff" />
                            </svg>
                        </div>
                        <h3 class="lcf-card-title">{{ get_phrase('Suporte integrado de recolocação profissional') }}</h3>
                        <p class="lcf-card-desc">{{ get_phrase('Ao concluir o curso, você recebe suporte integrado de recolocação profissional com consultoria vitalícia, construção de perfil e candidaturas a vagas.') }}</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="lcf-card">
                    <div class="lcf-card-inner">
                        <div class="lcf-icon-box">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect x="3" y="3" width="18" height="18" rx="3" stroke="#fff" stroke-width="1.7" fill="none" />
                                <path d="M7 16l3.5-4 3 2.5L19 8" stroke="#fff" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" fill="none" />
                                <circle cx="19" cy="8" r="1.4" fill="#fff" />
                            </svg>
                        </div>
                        <h3 class="lcf-card-title">{{ get_phrase('Acompanhamento de progresso em tempo real') }}</h3>
                        <p class="lcf-card-desc">{{ get_phrase('Acompanhe seu progresso em tempo real, veja sua posição no ranking e avance na competição à frente de todos.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<script>
    (function() {
        const section = document.getElementById('lcfSection');
        const cards = [...document.querySelectorAll('.lcf-card')];
        const REACH = 280; 

        function onMove(e) {
            const mx = e.clientX,
                my = e.clientY;

            cards.forEach(card => {
                const cr = card.getBoundingClientRect();

                const nx = Math.max(cr.left, Math.min(mx, cr.right));
                const ny = Math.max(cr.top, Math.min(my, cr.bottom));
                const dist = Math.hypot(mx - nx, my - ny);

                if (dist > REACH) {
                    card.style.background = '#e8e5f5';
                    return;
                }

                const t = 1 - dist / REACH;

                const px = ((mx - cr.left) / cr.width) * 100;
                const py = ((my - cr.top) / cr.height) * 100;

                card.style.background =
                    `radial-gradient(circle 200px at ${px}% ${py}%, ` +
                    `rgba(108,99,255,${0.9  * t}), ` +
                    `rgba(139,92,246,${0.6  * t}) 25%, ` +
                    `rgba(167,139,250,${0.3 * t}) 45%, ` +
                    `rgba(200,190,240,${0.5 * t}) 60%, ` +
                    `#e8e5f5 80%)`;
            });
        }

        function onLeave() {
            cards.forEach(c => c.style.background = '#e8e5f5');
        }

        section.addEventListener('mousemove', onMove);
        section.addEventListener('mouseleave', onLeave);
    })();
</script>