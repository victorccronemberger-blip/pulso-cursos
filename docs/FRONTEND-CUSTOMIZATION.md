# Customização do Frontend — Área de Trabalho Ativa

> ⚠️ **Esta é a área de trabalho em andamento do projeto.** As views e assets
> listados aqui estão sendo modificados (rebranding/landing de cursos). Leia
> este arquivo antes de mexer em qualquer página pública de curso, home ou
> CSS custom.

## O que está sendo customizado

A customização gira em torno das **páginas públicas de curso** e da **home**,
com um novo visual (banners, cards de curso, ícones FAQ, seção de garantia).

### Views envolvidas

| View | Papel |
| ---- | ----- |
| `resources/views/frontend/default/course/course_details.blade.php` | Detalhe do curso (customizado) |
| `resources/views/frontend/default/course/course_grid.blade.php` | Grade de cursos |
| `resources/views/frontend/default/course/index.blade.php` | Listagem de cursos |
| `resources/views/layouts/default.blade.php` | Layout público (carrega os CSS custom) |
| `resources/views/components/home_made_by_builder/hero_banner.blade.php` | Hero da home (page builder) |

### CSS custom (novos arquivos)

- `public/assets/frontend/default/css/course_detail.css`
- `public/assets/frontend/default/css/courses_page.css`
- `public/assets/frontend/default/css/login_page.css`
- `public/assets/frontend/default/css/toro_home_v2.css`

### Player de aulas

- O player recebe aulas por `play-course/{slug}/{id?}` e a aula informada no URL
  precisa pertencer ao curso correspondente.
- Para Bunny Stream, instrutores selecionam **Bunny Stream** ao criar a aula e
  colam somente a URL de incorporação `https://iframe.mediadelivery.net/embed/{library}/{video}`.
  Não há chave Bunny no frontend; a proteção do vídeo continua configurada no
  próprio Bunny Stream.

O player do aluno usa a identidade Pulso/Academy em
`public/assets/global/course_player/css/pulso-player.css`. A navegação segue a
"trilha de aprovação": vídeo, apostila vinculada, prática do tema e simulados
do módulo aparecem no mesmo contexto. As views principais são:

- `course_player/header.blade.php` — curso e progresso global;
- `course_player/lesson_tools.blade.php` — ações da aula e checkpoints;
- `course_player/side_bar.blade.php` — currículo intercalando aulas e quizzes;
- `course_player/quiz/*` — preparação, questões e resultado do simulado.

Materiais não ficam em `public/`. `course_materials` guarda metadados e o PDF;
o download autenticado é feito por `course.material.download`. Quizzes
importados continuam sendo `lessons.lesson_type = quiz`, preservando o fluxo
nativo de progresso e submissões.

### Importação modular de conteúdo

O mesmo manifesto usado por `courses:sync-videos` aceita `curriculum.sort_step`
e `content`. O comando `courses:sync-content` recebe pastas de PDFs e JSONs,
associa códigos equivalentes à aula, cria quizzes nativos e registra o contexto
em `course_quiz_contexts`.

Sempre execute o comando com `--dry-run` antes da carga real. O relatório
precisa terminar com zero arquivos não resolvidos.

**Onde são carregados** (confirmado no código):
- `layouts/default.blade.php` → `courses_page.css` (l.66) e `course_detail.css` (l.72).
- `course_details.blade.php` → `course_detail.css` (l.5).
- `toro_home_v2.css` → `hero_banner.blade.php` e `layouts/default.blade.php`.

Note que `course_detail.css` é carregado **duas vezes** (layout + view) — tome
cuidado com regras conflitantes.

## Pipeline de imagens (assets gerados)

As imagens são **geradas** em `output/imagegen/` (PNGs grandes, ~1.5–2 MB —
banners, cards de curso por certificação, garantia, ícones FAQ) e **copiadas**
para `public/assets/frontend/default/img/` para servir via `asset()`.

- Origem (design): `output/imagegen/*.png`
- Destino (runtime): `public/assets/frontend/default/img/*.png`

> ⚠️ `output/` está fora do doc de arquitetura — é uma área de trabalho de
> design, não parte do runtime. Não servida pelo app.

## Padrões de conteúdo (cards por certificação)

Os cards de curso em `output/imagegen/card-{cfa,cfg,cfp,cpa,cpni,cproI,cproR,ancord,01..04}.png`
sugerem um catálogo voltado a **certificações financeira/contábil** (CFA, CFG,
CFP, CPA, CFP, ANCORD, etc.). Use essas referências visuais ao ajustar a grade
de cursos.

## Regras para edição

- **Não quebre o layout duplo de CSS**: ao editar `course_detail.css`,
  confirme a intenção (layout global vs. página de curso).
- **Imagens**: se regenerar um PNG em `output/imagegen/`, copie para
  `public/assets/frontend/default/img/` com o **mesmo nome** para não quebrar
  referências `asset()`.
- **Não "otimize refatorando"** este emaranhado de CSS/views sem testes — é
  trabalho manual em progresso.
- Consulte `docs/ROUTES.md` para achar as rotas que renderizam cada view.
