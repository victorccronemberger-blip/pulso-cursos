# Esquema do Banco de Dados — bimbangladesh71 (LMS)

98 migrations em `database/migrations/` (todas com timestamp `2026_*` após a
baseline de `2023_*` / `2024_*`). Mapa agrupado por **domínio de negócio**.
Modelos Eloquent correspondentes em `app/Models/`.

> Convenção: tabelas em `snake_case` (padrão Laravel). Nomes de modelos nem
> sempre normalizados (ex.: `Builder_page.php`, `Knowledge_base.php`,
> `Enrollments.php`).

## Usuários, auth e papéis

| Tabela | Modelo | Observações |
| ------ | ------ | ----------- |
| `users` | `User` | Campo `role` = `admin` / `instructor` / `student` |
| `personal_access_tokens` | — | Tokens do Sanctum (API mobile) |
| `password_reset_tokens` | — | Reset de senha |
| `permissions` | `Permission` | Permissões/roles |
| `notifications` | — | Notificações |
| `notification_settings` | `NotificationSetting` | Prefs de notificação |

## Cursos e curriculum (núcleo)

| Tabela | Modelo | Relações |
| ------ | ------ | -------- |
| `courses` | `Course` | `belongsTo Category`, `hasMany Section/Lesson/Enrollment/Review/Exam/Feature` |
| `categories` | `Category` | Pai/filho (subcategorias) |
| `sections` | `Section` | Agrupa lessons; `belongsTo Course` |
| `lessons` | `Lesson` | `belongsTo Course/Section`; vídeo, duração |
| `course_features` | `CourseFeature` | Features do item do curso |
| `enrollments` | `Enrollment` / `Enrollments` | Matrículas (dois modelos espelhados) |
| `watch_durations` | `WatchDuration` | Tempo assistido |
| `watch_histories` | `Watch_history` | Histórico de assistir |
| `course_materials` | `CourseMaterial` | Apostilas protegidas por curso/seção/aula; binário servido somente após autorização |
| `course_quiz_contexts` | `CourseQuizContext` | Liga cada quiz nativo à aula ou ao checkpoint de módulo correspondente |
| `wishlists` | `Wishlist` | Lista de desejos |
| `reviews` | `Review` | Avaliações de curso |
| `like_dislike_reviews` | `LikeDislikeReview` | Likes/dislikes em reviews |
| `user_reviews` | `UserReview` | Reviews de usuário |
| `instructor_reviews` | `Instructor_review` | Reviews de instrutor |
| `certificates` | `Certificate` | Certificados de conclusão |
| `course_approval_requests` | — | Aprovação de curso |

## Quiz, exames e atribuições

| Tabela | Modelo | Observações |
| ------ | ------ | ----------- |
| `quizzes` | `Quiz` | Quiz por curso/lesson |
| `questions` | `Question` | Questões de quiz |
| `quiz_submissions` | `QuizSubmission` | Submissões de quiz |
| `exams` | `Exam` | Exames (adição recente) |
| `submissions` | `Submission` | Submissão de exames (tem `published_at`) |
| `assignments` | `Assignment` | Tarefas |
| `submitted_assignments` | `SubmittedAssignment` | Tarefa enviada |
| `applications` | `Application` | Inscrições/seleção |

## Cursos em bundle (Course bundles)

| Tabela | Modelo |
| ------ | ------ |
| `course_bundles` | `CourseBundle` |
| `bundle_payment` | `BundlePayment` |
| `bundle_ratings` | `BundleRating` |

## Ebooks

| Tabela | Modelo |
| ------ | ------ |
| `ebooks` | `Ebook` |
| `ebook_categories` | `EbookCategory` |
| `ebook_purchases` | `EbookPurchase` |
| `ebook_reviews` | `EbookReview` |

## Bootcamps

| Tabela | Modelo |
| ------ | ------ |
| `bootcamps` | `Bootcamp` |
| `bootcamp_categories` | `BootcampCategory` |
| `bootcamp_modules` | `BootcampModule` |
| `bootcamp_resources` | `BootcampResource` |
| `bootcamp_live_classes` | `BootcampLiveClass` |
| `bootcamp_purchases` | `BootcampPurchase` |

## Vendas em equipe (Team training)

| Tabela | Modelo |
| ------ | ------ |
| `team_training_packages` | `TeamTrainingPackage` |
| `team_package_members` | `TeamPackageMember` |
| `team_package_purchases` | `TeamPackagePurchase` |
| `team_members` | `TeamMember` |

## Tutoria (Tutor booking)

| Tabela | Modelo |
| ------ | ------ |
| `tutor_bookings` | `TutorBooking` |
| `tutor_categories` | `TutorCategory` |
| `tutor_subjects` | `TutorSubject` |
| `tutor_schedules` | `TutorSchedule` |
| `tutor_can_teach` | `TutorCanTeach` |
| `tutor_reviews` | `TutorReview` |

## Carrinho, cupons e vendas

| Tabela | Modelo | Observações |
| ------ | ------ | ----------- |
| `cart_items` | `CartItem` | Carrinho |
| `addtocarts` | — | Carrinho alternativo |
| `coupons` | `Coupon` | Cupons de desconto |
| `coursesbuy` | `Coursesbuy` | Registro de compra de curso |
| `purchase_courses` | `PurchaseCourse` | Outro registro de compra |
| `purchase_ebooks` | `PurchaseEbook` | Compra de ebook |
| `instructor_payments` | `InstructorPayment` | Pagamentos a instrutor |

## Pagamentos e payouts

| Tabela | Modelo | Observações |
| ------ | ------ | ----------- |
| `payment_gateways` | `Payment_gateway` | Config dos gateways |
| `payment_histories` | `Payment_history` | Histórico de pagamentos |
| `offline_payments` | `OfflinePayment` | Pagamento manual |
| `payouts` | `Payout` | Saques de instrutor |
| `instructor_payments` | `InstructorPayment` | Repasses |
| `currencies` | `Currency` | Moedas |
| `countries` | `Country` | Países |

> Adaptadores de gateway: `app/Models/payment_gateway/` (Aamarpay, Doku,
> Flutterwave, Maxicash, Paypal, Paystack, Paytm, Razorpay, Sslcommerz,
> StripePay). Detalhes em `docs/PAYMENTS.md`.

## Comunicação e suporte

| Tabela | Modelo | Observações |
| ------ | ------ | ----------- |
| `messages` | `Message` | Mensagens de chat |
| `message_threads` | `MessageThread` | Conversas |
| `chats` | `Chat` | Chat |
| `forums` | `Forum` | Fórum (Q&A) |
| `tickets` | `Ticket` | Customer support |
| `ticket_categories` | `TicketCategory` | Categoria de ticket |
| `ticket_faqs` | `TicketFaq` | FAQ de ticket |
| `ticket_macros` | `TicketMacro` | Respostas prontas |
| `ticket_messages` | `TicketMessage` | Mensagens de ticket |
| `ticket_priorities` | `TicketPriority` | Prioridade |
| `ticket_status` | `TicketStatus` | Status de ticket |
| `contacts` | `Contact` | Formulário de contato |
| `newsletters` | `Newsletter` | Newsletter |
| `newsletter_subscribers` | `NewsletterSubscriber` / `Newsletter_subscriber` | Assinantes |

## Conteúdo, SEO e builder

| Tabela | Modelo | Observações |
| ------ | ------ | ----------- |
| `blogs` | `Blog` | Blog |
| `blog_categories` | `BlogCategory` | Categorias de blog |
| `blog_comments` | `BlogComment` | Comentários de blog |
| `blog_likes` | `BlogLike` | Likes de blog |
| `knowledge_bases` | `Knowledge_base` | Base de conhecimento |
| `knowledge_base_topicks` | `Knowledge_base_topick` | Tópicos (typo no nome) |
| `seo_fields` | `SeoField` | SEO por página (tem `bundle_id`) |
| `builder_pages` | `Builder_page` | Páginas do page builder |
| `custom_fields` | `CustomField` | Campos customizados |

## Configuração e i18n

| Tabela | Modelo | Observações |
| ------ | ------ | ----------- |
| `settings` | `Setting` | Settings gerais |
| `frontend_settings` | `FrontendSetting` | Settings do frontend |
| `home_page_settings` | `HomePageSetting` | Settings da home |
| `player_settings` | `PlayerSettings` | Config do player de vídeo |
| `device_ips` | `DeviceIp` | Controle de devices |
| `languages` | `Language` | Idiomas |
| `language_phrases` | `Language_phrase` | Traduções por idioma |
| `media_files` | `MediaFile` | Arquivos de mídia |
| `file_uploader` | `FileUploader` | Uploads |
| `noticeboards` | `NoticeBoard` | Avisos |
| `addons` | `Addon` | Add-ons do produto |
| `live_classes` | `Live_class` | Aulas ao vivo |

## Notas e pegadinhas

- **Modelos duplicados/espelhados**: `Enrollment` vs `Enrollments`,
  `NewsletterSubscriber` vs `Newsletter_subscriber` — ambos apontam para a
  mesma tabela. Verificar qual é usado antes de alterar.
- **Nomes não-padrão** de modelos: `Builder_page`, `Knowledge_base`,
  `Watch_history`, `Language_phrase`, `Instructor_review`, `Live_class`.
- **Migration timestamps**: a maioria das tabelas tem `2026_01_04_*`; há
  adições posteriores (`course_features`, `exams`, `course_bundles`,
  `noticeboards`, `team_members`) de `2026_02/03`.
- O `public/assets/install.sql` é o dump SQL da instalação base do produto.
