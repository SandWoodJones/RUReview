# RUReview API

API (Web Service) em **Laravel** para avaliação do Restaurante Universitário da UTFPR-PG,
desenvolvida como projeto prático da disciplina de **Desenvolvimento Web — Servidor** (Trabalho 3).

Esta API dá continuidade ao projeto RUReview (Trabalhos 1 e 2). Enquanto os trabalhos anteriores
entregaram a aplicação web em PHP, este trabalho expõe os dados e funcionalidades do sistema por
meio de uma **API REST autenticada**, para que aplicações/empresas terceiras possam se integrar.

## Integrantes

- Raul Rodrigues de Oliveira (242272)
- Gabryel Kopp (2398419)

## Sobre

O RUReview permite que alunos cadastrados avaliem as refeições do RU de forma simples e organizada:
acompanhar a qualidade das refeições ao longo do tempo, comparar o cardápio divulgado com o que foi
servido e registrar reclamações.

Nesta entrega, toda a lógica está disponível via API JSON:

- **Autenticação por token** (Laravel Sanctum) para proteger as rotas.
- **Cardápios e refeições** — administradores cadastram/editam o cardápio de almoço e janta.
- **Avaliações** — usuários autenticados avaliam cada refeição com nota de 1 a 5, comentário e foto opcional.
- **Consulta pública/autenticada** das refeições e avaliações para integração de terceiros.

## Relatório / Particularidades

> **Preencher antes da entrega.** Descreva aqui qualquer particularidade relevante:
> bugs conhecidos, funcionalidades faltantes ou incompletas, decisões de projeto, limitações
> (ex.: upload de imagem, paginação, rate limiting), e qualquer coisa que o avaliador precise saber
> para rodar e testar o projeto.

- _Ex.: A API usa SQLite por padrão para facilitar a avaliação; basta rodar as migrations._
- _Ex.: Upload de imagem aceita JPG/PNG/WEBP até 2 MB._
- _Ex.: (bugs/pendências, se houver)_

## Atividades por integrante

> **Ajustar conforme a divisão real do trabalho.**

| Integrante | Atividades |
|------------|------------|
| Raul Rodrigues de Oliveira | _Ex.: models e migrations, autenticação (Sanctum), controllers de avaliação, coleção de testes_ |
| Gabryel Kopp | _Ex.: controllers de cardápio/refeições, validações (Form Requests), padrão de resposta JSON, documentação_ |

## Tecnologias

- PHP 8.2+
- Laravel 12
- Laravel Sanctum (autenticação por token)
- Eloquent ORM + Migrations
- SQLite (padrão) — configurável para MySQL / MariaDB / PostgreSQL via `.env`
- Composer

## Requisitos

- PHP 8.2 ou superior
- Composer
- SQLite (já incluso no PHP) **ou** MySQL/MariaDB/PostgreSQL

## Instalação e configuração

> Ajuste o caminho caso a API esteja em uma subpasta do repositório (ex.: `cd api`).

1. Clone o repositório e entre na pasta da API:

   ```bash
   git clone <url-do-repositorio>
   cd <pasta-do-projeto>
   ```

2. Instale as dependências:

   ```bash
   composer install
   ```

3. Crie o arquivo de ambiente e gere a chave da aplicação:

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. Configure o banco no `.env`.

   - **SQLite (padrão, mais simples):**

     ```bash
     touch database/database.sqlite
     ```

     E no `.env`:

     ```dotenv
     DB_CONNECTION=sqlite
     # comente/remova DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD
     ```

   - **MySQL/MariaDB:** preencha `DB_CONNECTION=mysql`, `DB_HOST`, `DB_PORT`,
     `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`.

5. (Se o Sanctum ainda não estiver instalado no projeto) instale a camada de API:

   ```bash
   php artisan install:api
   ```

6. Rode as migrations (e os seeders, se houver dados iniciais):

   ```bash
   php artisan migrate --seed
   ```

7. Suba o servidor:

   ```bash
   php artisan serve
   ```

8. A API ficará disponível em `http://localhost:8000/api`.

## Autenticação (Sanctum)

A API usa tokens do Laravel Sanctum. O fluxo é:

1. Cadastre-se (`POST /api/register`) ou faça login (`POST /api/login`).
2. A resposta retorna um **token**.
3. Envie o token no header de todas as rotas protegidas:

   ```
   Authorization: Bearer <token>
   Accept: application/json
   ```

## Padrão de resposta JSON

Todas as respostas seguem um formato unificado.

**Sucesso:**

```json
{
  "status": "success",
  "message": "Operação realizada com sucesso.",
  "data": { }
}
```

**Erro / validação:**

```json
{
  "status": "error",
  "message": "Os dados enviados são inválidos.",
  "errors": {
    "rating": ["A nota deve estar entre 1 e 5."]
  }
}
```

## Rotas da API

> Tabela de referência da superfície da API. Mantenha em sincronia com `routes/api.php`.

### Públicas

| Método | Rota | Descrição |
|--------|------|-----------|
| POST | `/api/register` | Cadastro de usuário |
| POST | `/api/login` | Login, retorna token |
| GET | `/api/meals` | Lista refeições (filtro opcional por data) |
| GET | `/api/meals/{id}` | Detalhe de uma refeição |

### Autenticadas (`auth:sanctum`)

| Método | Rota | Descrição |
|--------|------|-----------|
| GET | `/api/me` | Dados do usuário autenticado |
| POST | `/api/logout` | Revoga o token atual |
| GET | `/api/reviews` | Lista avaliações |
| GET | `/api/reviews/{id}` | Detalhe de uma avaliação |
| POST | `/api/reviews` | Cria uma avaliação |
| PUT | `/api/reviews/{id}` | Atualiza a própria avaliação |
| DELETE | `/api/reviews/{id}` | Remove a própria avaliação |
| GET | `/api/images/{id}` | Retorna a imagem de uma avaliação |

### Administrador (`auth:sanctum` + papel admin)

| Método | Rota | Descrição |
|--------|------|-----------|
| POST | `/api/meals` | Cadastra uma refeição no cardápio |
| PUT | `/api/meals/{id}` | Atualiza uma refeição |
| DELETE | `/api/meals/{id}` | Remove uma refeição |

## Testes

> **Preencher conforme o cliente HTTP escolhido.**

A coleção de testes está em [`tests/`](tests/) (ou `docs/`), exportada do
**_<Insomnia / Postman / Thunder Client>_**, com as rotas e dados de teste.

**Como importar (exemplo com Insomnia):**

1. Abra o cliente HTTP.
2. _Import_ → selecione o arquivo `tests/RUReview.<ext>`.
3. Configure a variável de ambiente `base_url` para `http://localhost:8000/api`.
4. Execute primeiro `register`/`login` para obter o token; ele é reaproveitado
   automaticamente nas demais requisições via variável `token`.

Alternativamente, é possível rodar os testes automatizados em PHP:

```bash
php artisan test
```
