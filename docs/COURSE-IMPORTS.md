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
