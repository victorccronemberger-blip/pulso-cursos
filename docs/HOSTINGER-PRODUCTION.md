# Produção na Hostinger

## Arquitetura atual

O frontend estático fica em `static/` e deve ser publicado em
`academy.cyara.com.br`. Ele é uma SPA leve em HTML/CSS/JavaScript: carrega
catálogo, detalhes e login pela API, e inicia o checkout no backend. Portanto,
ele continua dinâmico sem executar PHP no domínio Academy.

Os arquivos públicos (inclusive as imagens dos cards) estão versionados em
`public/`. Eles devem ser distribuídos junto com a aplicação que os referencia;
não devem ser gravados no banco de dados.

## Backend

Use `api-pulso.cyara.com.br` para o Laravel conectado ao banco
`u291739043_pulsefire`. O modelo seguro de variáveis está em
`.env.hostinger.example`. Preencha no hPanel o `APP_KEY` existente e a senha do
banco; os dois valores nunca entram no Git.

Depois de atualizar o código e o `.env`, no diretório do backend execute:

```text
composer install --no-dev --optimize-autoloader
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
```

O *document root* deve ser o diretório que contém o `index.php` publicado pelo
projeto. A raiz deste repositório já possui esse ponto de entrada para o fluxo
de Git Deploy usado nesta hospedagem.

## Academy

No Git Deploy estático, publique o conteúdo de `static/` como raiz do
`academy.cyara.com.br`. Não copie `.env`, `app/`, `vendor/`, banco ou código
PHP para esse domínio. As imagens de curso são fornecidas pelo backend através
das URLs devolvidas pela API.

O botão de compra primeiro cria uma URL assinada e válida por cinco minutos;
ela abre o carrinho/pagamento existente no backend. O valor, a matrícula e o
gateway nunca são controlados pelo JavaScript do navegador.

## Verificação após deploy

1. Abra `/`, `/courses/cfp` e `/login` no Academy.
2. Confirme que as imagens em `/public/uploads/course-thumbnail/` respondem.
3. Abra o endpoint de saúde/raiz do backend e confira o log Laravel, sem expor
   `APP_DEBUG` em produção.
4. Limpe o cache do website na Hostinger após cada troca de código.
