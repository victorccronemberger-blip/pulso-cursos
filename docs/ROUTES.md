# Mapa de Rotas — bimbangladesh71 (LMS)

13 arquivos em `routes/`. Carregados pelo `RouteServiceProvider` padrão do
Laravel. Muitos agrupam endpoints via `Route::controller(...)->group(...)`
(rota implícita por método).

| Arquivo         | Linhas | Área | Middleware |
| --------------- | ------ | ---- | ---------- |
| `web.php`       | 121    | Install, cache, redirects, modais | misto |
| `guest.php`     | 137    | Frontend público (auth-free) | `guest` |
| `admin.php`     | 664    | Painel admin | `auth` + admin |
| `instructor.php`| 324    | Painel instrutor | `auth` + instructor |
| `student.php`   | 239    | Painel aluno | `auth` + student |
| `api.php`       | 70     | API mobile | `auth:sanctum` |
| `auth.php`      | 67     | Login/registro/verificação | Breeze |
| `payment.php`   | 25     | Pagamentos/callbacks | `auth` (+ público p/ callbacks) |
| `player.php`    | 39     | Player de curso, fórum, arquivos | `auth` |
| `chat.php`      | 18     | Chat | `auth` |
| `custom_route.php` | 14  | Comparar cursos, invoice | misto |
| `channels.php`  | 18     | Broadcasting channels | — |
| `console.php`   | 19     | Comandos artisan | — |

## `web.php` — núcleo

- `GET /clear-cache` — limpa caches (Artisan).
- `GET /dashboard` — redireciona por `role` (admin/student/outros).
- `GET /modal/{view_path}` — renderiza view de modal (dinâmico).
- `GET /view/{path}` — renderiza view (dinâmico, usado por player).
- `GET /get-video-details/{url?}` — detalhes de vídeo.
- `GET /payment/web_redirect_to_pay_fee` — redirect de pagamento mobile.
- **Instalação** (`InstallController`): `/install/step0`…`/install/success`,
  valida purchase code, importa SQL, finaliza setup.

## `guest.php` — frontend público

Home, cursos, ebooks, bootcamps, blog, tutoria, contato, newsletter,
conhecimento, páginas estáticas (termos, privacidade, FAQ, cookies). Tudo sem
login.

## `admin.php` (664 linhas) — painel administrativo

Rotas `auth` + verificação de papel admin. Cobre:
- Dashboard, usuários, papéis/permissões.
- Gestão de cursos, categorias, curriculum, quiz, exames, ebooks, bootcamps,
  bundles, cupons, certificados.
- Pagamentos/payouts, config de gateways, relatórios.
- Settings, page builder, SEO, idiomas, OpenAI, customer support/tickets,
  tutoria, team training, notificações.

## `instructor.php` (324 linhas) — painel do instrutor

Criação/edição de cursos, curriculum (sections/lessons), quiz, exames,
ebooks, bootcamps, live classes, dashboard próprio, perfil, payout, sales
report, tutor booking, team training.

## `student.php` (239 linhas) — painel do aluno

Meus cursos, carrinho, checkout, wishlist, compras (cursos/ebooks/bootcamps/
bundles/team packages), inscrições, perfil, mensagens, quiz, reviews,
tutor booking, customer support, virar instrutor.

## `api.php` — API mobile (Sanctum)

- Público: `POST /login`, `POST /signup`, `POST /forgot_password`.
- Autenticadas (`auth:sanctum`): catálogo/filtro de cursos, categorias,
  bootcamps, wishlist, carrinho, meus cursos, seções, progresso, seções de
  curso, pagamento via token, Zoom/live classes, cart tools, update de
  perfil/senha, logout, account disable.

## `payment.php` — pagamentos

`auth` para iniciar pagamento; **callbacks públicos** (`payment-notification`)
para os gateways notificarem. Suporta Razorpay, Paytm, Doku e demais gateways.

## `player.php` — player de curso

`auth`. Player de curso (`play-course/{slug}/{id?}`), gravação de watch
history, watermark no vídeo, fórum (Q&A), e download de arquivos/vídeos/PDF.

## `chat.php` — mensagens

Rotas do chat/reporting entre alunos e instrutores.

## `custom_route.php`

`GET /compare` (comparar cursos) e `GET /Invoice/{id}` (fatura do aluno).

## Observações úteis para LLMs

- **Rotas dinâmicas**: `GET /view/{path}` e `GET /modal/{view_path}` permitem
  renderizar *qualquer* view — atenção ao procurar o endpoint de uma página.
- **Roteamento implícito**: como vários controllers usam `Route::controller`,
  o nome da rota/URL nem sempre aparece literal — procure o método no
  controller correspondente.
- Rodando `php artisan route:list` no ambiente gera o mapa completo e
  resolvido (todas as rotas implícitas explicitadas).
- Middlewares de papel são aplicados nos grupos de cada arquivo (admin /
  instructor / student).