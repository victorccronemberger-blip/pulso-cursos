# AGENTS.md — Guia de onboarding para LLMs e desenvolvedores

Plataforma de e-learning (LMS) em **Laravel 11 / PHP 8.1**. Framework padrão
do Laravel, monolítica, com múltiplos papéis. Este arquivo orienta quem (LLM
ou humano) vai trabalhar no código.

## Leia primeiro

- `docs/ARCHITECTURE.md` — visão geral, stack, áreas e domínios.
- `docs/DATABASE.md` — mapa do schema (98 migrations) por domínio.
- `docs/ROUTES.md` — mapa das rotas por arquivo.
- `docs/CONTROLLERS.md` — mapa dos 131 controllers por área.
- `docs/PAYMENTS.md` e `docs/API.md` — pagamentos e API mobile.
- `docs/FRONTEND-CUSTOMIZATION.md` — **área de trabalho ativa** (páginas de
  curso, CSS custom, pipeline de imagens). Leia antes de mexer no frontend.

> Todos os arquivos em `docs/` são **somente documentação** — não alteram
> código. Não confie neles como fonte de verdade final; confirme no código.

## Regras operacionais

- **Área = pasta do controller + pasta de views.** Achar a feature = localizar
  a rota no `routes/*.php`, depois o controller, depois a view em
  `resources/views/{area}/`.
- **Rotas implícitas**: muitos controllers usam `Route::controller(...)` — o
  URL/rota não aparece literalmente; procure o método no controller.
- **Rotas dinâmicas**: `GET /view/{path}` e `GET /modal/{view_path}`
  renderizam views dinamicamente.
- **Nomes não-padrão de modelos**: `Builder_page`, `Knowledge_base`,
  `Watch_history`, `Enrollments`, `Newsletter_subscriber`, etc. — não "corrija"
  sem verificar o uso.
- Para o mapa resolvido e completo de rotas, rode: `php artisan route:list`.

## Stack chave

- Laravel 11, MySQL, Blade + Tailwind + Vite.
- Auth: Breeze (web) + **Sanctum** (API mobile) + firebase/php-jwt.
- Vídeo: pbmedia/laravel-ffmpeg; Imagens: intervention/image.
- Pagamentos: Stripe, Razorpay, Paytm, PayPal, SSLCommerz, Flutterwave,
  Paystack, Aamarpay, Doku, Maxicash + offline.

## Áreas de negócio

Cursos/curriculum, quiz/exames/assignments, ebooks, bootcamps, course bundles,
venda corporativa (team training), tutoria (tutor booking), blog, fórum,
base de conhecimento, live classes (Zoom), mensagens/chat, customer support
(tickets), cupons, SEO/page builder, i18n, newsletters.

## Países / i18n

`Language` + `Language_phrase` gerenciam traduções; `LanguageController`
aplica no frontend.

## Setup

- `composer install`, `cp .env.example .env`, `php artisan key:generate`.
- Migrations + `php artisan db:seed`.
- Instalação web embutida: `InstallController` (`/install/step0` …`/install/success`).
- Atualização do produto: scripts em `upload/update_1.10/`.

## Cuidados

- **Não versionar segredos**: `.env` contém chaves de pagamento/DB. O
  `.gitignore` já cobre `.env` e `vendor/`.
- **Callbacks de pagamento são públicos** — validar antes de liberar acesso.
- Há **modelos duplicados** apontando para a mesma tabela; verifique qual é o
  em uso antes de alterar.

## Limitações conhecidas (honestidade sobre o estado)

- **Testes**: só existem os boilerplate do Breeze
  (`tests/Feature/Auth/*`, `ExampleTest`). **Não há testes de negócio** —
  nenhuma cobertura para cursos, pagamento, bootcamps, etc.
- **Docs são estáticos**: podem ficar desatualizados conforme o código muda.
  Ao alterar rotas/schema/controllers, atualize os arquivos em `docs/`.
- **Área ativa**: o frontend de cursos está em customização constante
  (`docs/FRONTEND-CUSTOMIZATION.md`) e pode divergir do restante.