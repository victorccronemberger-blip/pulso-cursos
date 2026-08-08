# Importação modular de cursos

O currículo em vídeo é versionado em manifestos JSON. O manifesto identifica o
curso por `slug`, nunca por ID de banco, descreve os módulos e liga cada aula ao
ID imutável do provedor. O sincronizador resolve os IDs locais e grava tudo em
uma transação.

## Comandos

```bash
php artisan courses:sync-videos resources/course-imports/cfp-2026.json --dry-run
php artisan courses:sync-videos resources/course-imports/cfp-2026.json
```

### Ingestão em lote a partir do acervo local

O catálogo de origem fica em
`resources/course-imports/source-catalog-2026.json`. Ele registra seções,
coleções Bunny, exceções de posicionamento e exclusões de arquivos duplicados
ou incompletos. Os manifests intermediários e o estado retomável ficam em
`storage/app/course-imports/` e não são versionados.

```powershell
npm run courses:manifest
npm run courses:upload -- --dry-run
npm run courses:upload
npm run courses:finalize
```

O upload usa TUS em blocos, grava o GUID assim que o objeto é criado e retoma
do último offset depois de uma interrupção. A chave de escrita existe somente
no `.env` local como `BUNNY_STREAM_API_KEY`; ela não é necessária em produção e
nunca deve ser enviada ao frontend.

Antes de sincronizar conteúdo, rode as auditorias locais:

```powershell
php scripts/course-imports/audit-source-content.php C:\caminho\dos\videos
php scripts/course-imports/audit-question-banks.php C:\caminho\dos\videos
```

Após `courses:finalize`, sincronize primeiro as aulas para criar as seções e só
então apostilas e bancos de questões:

```powershell
php artisan courses:sync-videos resources/course-imports/ancord-2026.json --dry-run
php artisan courses:sync-videos resources/course-imports/ancord-2026.json
php artisan courses:sync-content resources/course-imports/ancord-2026.json \
  --materials=C:\acervo\ANCORD\PDFs \
  --questions=C:\acervo\ANCORD\Questoes \
  --dry-run
```

O normalizador de questões ignora enunciados vazios, alternativas-placeholder,
questões sem gabarito e itens com menos de duas opções. Notas e percentual de
aprovação são calculados somente sobre as questões válidas.

O comando é idempotente: a identidade da aula é `course_id + lesson_src`.
Reexecutar atualiza título, módulo, duração, ordem e status sem duplicar. A opção
`--prune` remove somente aulas do mesmo provedor que não estejam no manifesto e
deve ser usada explicitamente.

Para inventariar uma biblioteca Bunny sem expor a chave no repositório:

```bash
BUNNY_STREAM_READ_KEY=... php artisan courses:bunny-catalog 723013 \
  --output=storage/app/course-imports/cfp-bunny-catalog.json
```

## Novo curso

1. Exporte o catálogo do provedor com uma chave somente de leitura.
2. Crie um manifesto em `resources/course-imports/` usando o CFP como modelo.
3. Mapeie cada aula para uma chave de seção e defina sua ordem.
4. Rode `--dry-run` no ambiente de destino.
5. Sincronize e valide contagem, unicidade e reprodução com uma conta de aluno.

Novos provedores implementam `VideoProvider` e são registrados em
`config/course-imports.php`. Nenhum segredo pertence ao manifesto.
