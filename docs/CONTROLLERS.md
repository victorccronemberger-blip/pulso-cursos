# Mapa de Controllers — bimbangladesh71 (LMS)

131 controllers em `app/Http/Controllers/`, organizados por área. Cada área
tem seu próprio conjunto de views em `resources/views/`.

## app/Http/Controllers/ (raiz — 45)

Controllers compartilhados / de negócio transversal:

| Controller | Função |
| ---------- | ------ |
| `ApiController` | API mobile (Sanctum) → `routes/api.php` |
| `PaymentController` | Fluxo de pagamento, gateways, callbacks |
| `OfflinePaymentController` | Pagamento manual/offline |
| `InstallController` | Instalação web do produto |
| `PlayerController` | Player de curso, watch history, watermark |
| `FileController` | Download de arquivos/vídeo/PDF |
| `ForumController` | Fórum Q&A por curso |
| `ModalController` | Renderiza modais dinâmicos |
| `CommonController` | Helpers comuns (detalhes de vídeo, views) |
| `InvoiceController` | Faturas |
| `ReportController` | Relatórios |
| `SeoController`, `SettingController` | SEO e settings |
| `LanguageController` | i18n |
| `NewsletterController` | Newsletter |
| `ReviewController` | Reviews |
| `AssignmentController` | Assignments |
| `CurriculumController` | Curriculum (sections/lessons) |
| `CourseController` | Curso (raiz) |
| `CouponController` | Cupons |
| `ExamController`, `MyExamController`, `InstructorExamController` | Exames |
| `LiveClassController`, `ZoomMeetingController` | Aulas ao vivo / Zoom |
| `WatermarkController` | Watermark de vídeo |
| `ChatController`, `CustomerSupportController` | Chat e suporte |
| `CustomFieldController` | Campos customizados |
| `BlogController`, `BlogCategoryController`, `BlogComment` | Blog |
| `ContactController`, `DashboardController`, `UsersController` | Diversos |
| `Updater` | Atualização do produto |

## app/Http/Controllers/Admin/ (24)

Gestão administrativa completa (294 views em `resources/views/admin/`):
courses, categories, curriculum, quizzes, questions, exames, ebooks,
bootcamps (categorias, módulos, recursos, live classes), course bundles,
blog, knowledge base, page builder, OpenAI, tickets/suporte, notices,
offline payments, tutoria, team training, mensagens.

## app/Http/Controllers/frontend/ (18)

Páginas públicas (124 views em `resources/views/frontend/default/`):
`HomeController`, `CourseController`, `EbookController`, `BootcampController`,
`CourseBundleController`, `BlogController`, `InstructorController`,
`AboutController`, `ContactController`, `ReviewController`,
`KnowledgeBaseTopicController`, `LanguageController`, `NewsletterController`,
`SubscribedController`, `TeamTrainingController`, `TutorBookingController`,
`MycourseController`, `Chatcontroller`.

## app/Http/Controllers/instructor/ (31)

Painel do instrutor (155 views em `resources/views/instructor/`):
`DashboardController`, `CourseController`, `SectionController`,
`LessonController`, `QuizController`, `QuestionController`, `ExamController`,
`EbookController`, `Bootcamp*`, `LiveClassController`, `OpenAiController`,
`PayoutController`, `PayoutSettingsController`, `SalesReportController`,
`CustomFieldController`, `NoticeBoardController`, `TutorBookingController`,
`TeamTrainingController`, `MyProfileController`, `InstructorController`.

## app/Http/Controllers/student/ (24)

Painel do aluno: `MyCoursesController`, `MyEbookController`,
`MyBootcampsController`, `MyCourseBundleController`, `MyTeamPackageController`,
`CartController`, `PurchaseController`, `WishListController`,
`OfflinePaymentController`, `CourseBundlePurchaseController`,
`BootcampPurchaseController`, `QuizController`, `LiveClassController`,
`ReviewController`, `BlogController`, `BlogCommentController`,
`MessageController`, `CustomerSupportController`, `TutorBookingController`,
`MyProfileController`, `BecomeInstructorController`.

## app/Http/Controllers/Auth/ (8)

Controllers do Laravel Breeze: login, registro, verificação de email,
reset de senha, confirmação de senha.

## Guia de uso para LLMs

- **Área = pasta do controller + pasta de views.** Para achar onde uma feature
  é editada, localize o controller pela rota e depois a view em
  `resources/views/{area}/`.
- **Controllers de raiz** lidam com funcionalidades transversais (pagamento,
  player, instalação, API) usadas por várias áreas.
- Muitos controllers são grandes e contêm helpers privados no mesmo arquivo —
  leia o arquivo inteiro antes de editar.