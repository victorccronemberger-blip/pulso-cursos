# Architecture — bimbangladesh71 (LMS)

Visão geral da arquitetura do projeto para orientação de LLMs e novos
desenvolvedores. Este arquivo é **documentação apenas** — não altera código.

## O que é este projeto

Um **Learning Management System (LMS)** completo, monolítico, construído em
Laravel, com áreas separadas para **Admin**, **Instructor**, **Student** e um
**frontend público**, além de uma **API mobile** (Sanctum).

> Repositório "academy-laravel" renomeado localmente como `bimbangladesh71`.
> É um produto comercial de LMS (monetização de cursos, ebooks, bootcamps,
> tutoria e venda corporativa em equipe).

## Stack principal

| Camada        | Tecnologia                                                        |
| ------------- | ----------------------------------------------------------------- |
| Framework     | Laravel **11** (PHP ^8.1)                                          |
| Auth          | Laravel Breeze (sessão/chave) + **Sanctum** (API mobile) + firebase/php-jwt |
| Banco         | MySQL (padrão), acesso via Eloquent, 98 migrations                 |
| Frontend      | Blade + Tailwind CSS + Vite (compila assets)                       |
| Vídeo         | pbmedia/laravel-ffmpeg + php-ffmpeg (player, watermark, duração)   |
| Imagens       | intervention/image                                                 |
| Pagamentos    | Stripe, Razorpay, Paytm, PayPal, Flutterwave, Paystack, SSLCommerz, Aamarpay, Doku, Maxicash + offline (ver `docs/PAYMENTS.md`) |
| Outros        | guzzlehttp, simple-qrcode, laravel/tinker, laravel/pint            |

## Estrutura de pastas (o que importa)

```
app/
  Console/Commands/AppSetup.php        # setup CLI do produto
  Exceptions/Handler.php
  Helpers/                             # Common_helper.php, Api_helper.php (autoload global)
  Http/Controllers/                    # 131 controllers (ver docs/CONTROLLERS.md)
    Admin/                             # 294 views de admin
    frontend/                          # páginas públicas
    instructor/                        # painel do instrutor
    student/                           # painel do aluno
    Auth/  + raiz (Api, Payment, Instal...) 
  Models/                              # ~120 modelos Eloquent (ver docs/DATABASE.md)
    payment_gateway/                   # 10 adapters de gateway
config/                                # config padrão + laravel-ffmpeg.php
database/migrations/                   # 98 migrations (todas com timestamp 2026_*)
database/seeders/DatabaseSeeder.php
public/                                # index.php, assets/, uploads/, install.sql
resources/views/                       # 770 views Blade
  admin/ instructor/ frontend/default/ layouts/ auth/ course_player/ ...
routes/                                # 13 arquivos de rotas (ver docs/ROUTES.md)
tests/
upload/update_1.10/                    # pacote de update/instalação do produto
```

## Áreas do sistema (multi-role)

O acesso é controlado por `role` na tabela `users` (`admin` / `instructor` /
`student`). Um mesmo usuário pode acumular papéis (ex.: instrutor que também
compra cursos).

1. **Frontend público** — `resources/views/frontend/default/` + controllers em
   `frontend/`. Home, catálogo de cursos/ebooks/bootcamps, tutoria, blog.
2. **Admin** — `Admin/*` controllers + `resources/views/admin/`. Gestão total:
   usuários, cursos, categorias, cupons, pagamentos, tickets, settings, page
   builder, OpenAI, certificados.
3. **Instructor** — `instructor/*` controllers + `resources/views/instructor/`.
   Cria gerencia cursos/curriculum/quiz/ebooks/bootcamps, payout, relatórios.
4. **Student** — `student/*` controllers. Meus cursos, carrinho, wishlist,
   pagamento, compras, inscrições em bootcamps/pacotes.
5. **API mobile** — `ApiController` + `routes/api.php`, protegida por
   `auth:sanctum`.

## Domínios de negócio

- **Course / Curriculum** — `Course`, `Section`, `Lesson`, `Enrollment`,
  progresso (`Watch_history`, `WatchDuration`), quizzes, exames, assignments,
  certificados, reviews.
- **Ebook** — `Ebook`, `EbookCategory`, `EbookPurchase`, `EbookReview`.
- **Bootcamp** — `Bootcamp`, `BootcampModule`, `BootcampResource`,
  `BootcampLiveClass`, `BootcampPurchase`.
- **Team training** — `TeamTrainingPackage`, `TeamPackageMember`,
  `TeamPackagePurchase`, `TeamMember`.
- **Tutor booking** — `TutorBooking`, `TutorCategory`, `TutorSubject`,
  `TutorSchedule`, `TutorCanTeach`, `TutorReview`.
- **Course bundle** — `CourseBundle`, `BundlePayment`, `BundleRating`.
- **Vendas/pagamento** — `CartItem`, `Coupon`, `Payment_gateway`,
  `Payment_history`, `Payout`, `OfflinePayment`, `Invoice`.
- **Comunicação** — `Message`/`MessageThread` (chat), `Forum`, `Chat`,
  `Ticket*` (customer support), `Newsletter`.
- **Conteúdo** — `Blog`, `BlogCategory`, `Knowledge_base`, FAQ, `Builder_page`
  (page builder), `SeoField`, `Language`/`Language_phrase` (i18n).

## Fluxo típico de compra

1. Aluno adiciona curso ao **carrinho** (`CartItem`).
2. Aplica **cupom** (`Coupon`) se houver.
3. `PaymentController` renderiza o gateway escolhido (Stripe, Razorpay, ...).
4. Callback do gateway grava `Payment_history` + `Enrollment` / `Purchase*`.
5. Curso liberado em "Meus cursos" (`student/MyCoursesController`).

## Instalação / setup

- `php artisan key:generate`
- Rodar migrations + `DatabaseSeeder`.
- Fluxo de instalação web em `InstallController` (`routes/web.php`:
  `/install/step0` ... `/install/success`), validando purchase code.
- Atualização do produto via `upload/update_1.10/` (scripts de upgrade).

## Builder / helpers

- `app/Helpers/Common_helper.php` e `Api_helper.php` são carregados via
  `composer.json` `autoload.files` (funções globais, ex.: helpers de settings,
  pagamento, conversão de vídeo).
- Page builder: `Admin/PageBuilderController` + `config/page-builder/` em
  `public/assets/`.

## Convenções de roteamento

- Rotas separadas por área em `routes/*.php` (ver `docs/ROUTES.md`).
- Muitos controllers usam `Route::controller(...)->group(...)` (roteamento
  implícito por método).
- Helper route `GET /view/{path}` e `GET /modal/{view_path}` renderizam views
  dinâmicas (usado por modais e pelo player).