# API Mobile — bimbangladesh71 (LMS)

Endpoints em `routes/api.php`, implementados por `App\Http\Controllers\ApiController`.
Autenticação via **Laravel Sanctum** (`auth:sanctum`).

> Base URL: `{APP_URL}/api`. Prefixo `api` aplicado pelo `RouteServiceProvider`.

## Públicos (sem auth)

| Método | Rota | Função |
| ------ | ---- | ------ |
| POST | `/login` | Login (emite token) |
| POST | `/signup` | Cadastro |
| POST | `/forgot_password` | Esqueci a senha |

## Autenticados (`auth:sanctum`)

| Método | Rota | Função |
| ------ | ---- | ------ |
| GET | `/user` | Usuário atual |
| GET | `/top_courses` | Cursos em destaque |
| GET | `/all_categories` / `/categories` / `/category_details` | Categorias |
| GET | `/sub_categories/{id}` | Subcategorias |
| GET | `/category_wise_course` / `/category_subcategory_wise_course` | Cursos por categoria |
| GET | `/filter_course` | Filtrar cursos |
| GET | `/courses_by_search_string` | Busca |
| GET | `/course_details_by_id` | Detalhe de curso |
| GET | `/sections` | Seções de um curso |
| GET | `/my_courses` | Meus cursos |
| GET | `/save_course_progress` | Salvar progresso |
| GET | `/my_wishlist` / `/toggle_wishlist_items` | Wishlist |
| GET | `/cart_list` / `/toggle_cart_items` / `/cart_tools` | Carrinho |
| GET | `/languages` | Idiomas |
| GET | `/top_bootcamps` / `/bootcamp_details_by_id` | Bootcamps |
| GET | `/purchase/bootcamp/{bootcamp_id}` | Comprar bootcamp |
| GET | `/free_course_enroll/{course_id}` | Matrícula em curso grátis |
| GET | `/zoom/settings` / `/zoom/meetings` | Aulas ao vivo (Zoom) |
| GET | `/payment/{token}` | Pagamento via token |
| GET | `/token` | Emitir token de pagamento |
| POST | `/update_password` | Alterar senha |
| POST | `/update_userdata` | Atualizar dados |
| POST | `/account_disable` | Desativar conta |
| POST | `/logout` | Logout |

## Observações

- A API é **leitura/pagamento** fortemente focada em catálogo e carrinho —
  comportamento similar ao frontend web.
- Contratos de resposta seguem o padrão do `ApiController` (JSON com status).
- Para o mapa de payloads exatos, ler `app/Http/Controllers/ApiController.php`.