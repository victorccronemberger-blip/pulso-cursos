# bimbangladesh71 — Plataforma LMS (Laravel)

Plataforma de e-learning completa (Learning Management System) em **Laravel 11 /
PHP 8.1**, com múltiplos papéis — **Admin**, **Instructor**, **Student** —
além de frontend público e API mobile.

## Funcionalidades

- **Cursos** com curriculum (sections/lessons), player de vídeo com
  watermark, progresso, quiz, exames, assignments, certificados e reviews.
- **Ebooks**, **Bootcamps** (módulos, recursos, aulas ao vivo) e **Course
  bundles**.
- **Venda corporativa** (team training packages) e **tutoria** (tutor booking).
- **Blog**, **fórum** (Q&A), **base de conhecimento**, **live classes (Zoom)**,
  mensagens/chat e **customer support** (ticketing).
- **Cupons**, carrinho, wishlist, **múltiplos gateways de pagamento** e
  payouts de instrutor.
- **Page builder**, SEO, **i18n** (multi-idioma) e **API mobile** (Sanctum).

## Stack

Laravel 11 · MySQL · Blade + Tailwind + Vite · Sanctum · laravel-ffmpeg ·
Stripe / Razorpay / Paytm / PayPal / SSLCommerz / Flutterwave / Paystack /
Aamarpay / Doku / Maxicash.

## Documentação

| Documento | Conteúdo |
| --------- | -------- |
| [`docs/ARCHITECTURE.md`](docs/ARCHITECTURE.md) | Visão geral, stack, áreas, domínios |
| [`docs/DATABASE.md`](docs/DATABASE.md) | Esquema do banco (98 migrations) por domínio |
| [`docs/ROUTES.md`](docs/ROUTES.md) | Mapa de rotas por arquivo |
| [`docs/CONTROLLERS.md`](docs/CONTROLLERS.md) | Mapa dos controllers por área |
| [`docs/PAYMENTS.md`](docs/PAYMENTS.md) | Gateways e fluxo de pagamento |
| [`docs/API.md`](docs/API.md) | Endpoints da API mobile |
| [`docs/FRONTEND-CUSTOMIZATION.md`](docs/FRONTEND-CUSTOMIZATION.md) | Área de trabalho ativa (páginas de curso, CSS, imagens) |
| [`AGENTS.md`](AGENTS.md) | Guia de onboarding para LLMs/desenvolvedores |

> A documentação é **somente leitura** e não altera o código-fonte.

## Estrutura principal

```
app/                    # Controllers, Models (incl. payment_gateway/), Helpers, Console
config/                 # Configuração Laravel + laravel-ffmpeg
database/migrations/    # 98 migrations
resources/views/        # 770 views Blade (admin/ instructor/ frontend/ ...)
routes/                 # 13 arquivos de rotas (admin.php, instructor.php, ...)
public/                 # index.php, assets/, uploads/
tests/                  # Testes
```

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

Há também um assistente de instalação web (`/install/step0` →
`/install/success`) e scripts de atualização em `upload/update_1.10/`.

## Nota

`README.md` base era o boilerplate padrão do Laravel; foi substituído por este
resumo. Toda a documentação de arquitetura está em `docs/`.