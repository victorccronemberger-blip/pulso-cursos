{{-- Seção FAQ V2 - Redesenhada --}}
<section class="pf-faq">
    <div class="container">
        <div class="pf-faq-grid">
            {{-- Coluna esquerda: Título e descrição --}}
            <div class="pf-faq-header">
                <img src="{{ asset('assets/frontend/default/img/icon-faq.png') }}" alt="FAQ" class="pf-faq-icon-header">
                <h2>Perguntas frequentes</h2>
                <p>Tudo o que você precisa saber sobre nossos cursos e certificações do mercado financeiro.</p>
                <div class="pf-faq-contact">
                    <p>Não encontrou sua dúvida?</p>
                    <a href="#" class="pf-faq-contact-link">
                        Entre em contato
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <path d="M3 8H13M13 8L9 4M13 8L9 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                </div>
            </div>

            {{-- Coluna direita: Lista de perguntas --}}
            <div class="pf-faq-list">
                <div class="pf-faq-item">
                    <button class="pf-faq-question">
                        <span class="pf-faq-number">01</span>
                        <span class="pf-faq-text">Quanto tempo tenho acesso ao curso?</span>
                        <span class="pf-faq-toggle">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                                <path d="M10 4V16M4 10H16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        </span>
                    </button>
                    <div class="pf-faq-answer">
                        <div class="pf-faq-answer-inner">
                            Você tem acesso vitalício ao curso. Pode assistir quantas vezes quiser, no seu ritmo, sem limite de tempo.
                        </div>
                    </div>
                </div>

                <div class="pf-faq-item">
                    <button class="pf-faq-question">
                        <span class="pf-faq-number">02</span>
                        <span class="pf-faq-text">Os simulados são no formato oficial da prova?</span>
                        <span class="pf-faq-toggle">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                                <path d="M10 4V16M4 10H16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        </span>
                    </button>
                    <div class="pf-faq-answer">
                        <div class="pf-faq-answer-inner">
                            Sim! Nossos simulados seguem exatamente o formato, tempo e distribuição de questões da prova oficial. Você se prepara nas mesmas condições do exame real.
                        </div>
                    </div>
                </div>

                <div class="pf-faq-item">
                    <button class="pf-faq-question">
                        <span class="pf-faq-number">03</span>
                        <span class="pf-faq-text">Posso parcelar o pagamento?</span>
                        <span class="pf-faq-toggle">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                                <path d="M10 4V16M4 10H16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        </span>
                    </button>
                    <div class="pf-faq-answer">
                        <div class="pf-faq-answer-inner">
                            Sim, você pode parcelar em até 12x no cartão de crédito. Também aceitamos pagamento à vista via PIX com desconto especial.
                        </div>
                    </div>
                </div>

                <div class="pf-faq-item">
                    <button class="pf-faq-question">
                        <span class="pf-faq-number">04</span>
                        <span class="pf-faq-text">E se eu não passar na prova?</span>
                        <span class="pf-faq-toggle">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                                <path d="M10 4V16M4 10H16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        </span>
                    </button>
                    <div class="pf-faq-answer">
                        <div class="pf-faq-answer-inner">
                            Oferecemos suporte completo até sua aprovação. Se seguir nosso método e não passar, você pode refazer o curso gratuitamente até conseguir.
                        </div>
                    </div>
                </div>

                <div class="pf-faq-item">
                    <button class="pf-faq-question">
                        <span class="pf-faq-number">05</span>
                        <span class="pf-faq-text">Os cursos têm certificado?</span>
                        <span class="pf-faq-toggle">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                                <path d="M10 4V16M4 10H16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        </span>
                    </button>
                    <div class="pf-faq-answer">
                        <div class="pf-faq-answer-inner">
                            Sim, ao concluir o curso você recebe um certificado de conclusão. Mas lembre-se: a certificação oficial (CFP®, CFA, CPA-20, etc.) é emitida pelos órgãos competentes após aprovação na prova.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    "use strict";
    (function() {
        var faqItems = document.querySelectorAll('.pf-faq-item');
        faqItems.forEach(function(item) {
            var question = item.querySelector('.pf-faq-question');
            question.addEventListener('click', function() {
                var isActive = item.classList.contains('active');
                faqItems.forEach(function(i) { i.classList.remove('active'); });
                if (!isActive) {
                    item.classList.add('active');
                }
            });
        });
    })();
</script>
