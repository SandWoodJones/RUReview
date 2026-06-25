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

- PHP 8.3+
- Laravel 13
- Laravel Sanctum (autenticação por token)
- Eloquent ORM + Migrations
- SQLite (padrão) — configurável para MySQL / MariaDB / PostgreSQL via `.env`
- Composer
- Bruno (coleção de testes HTTP, open source)

## Requisitos

- PHP 8.3 ou superior
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
| GET | `/api/meals` | Lista refeições (filtro opcional por data via `?date=`) |
| GET | `/api/meals/{id}` | Detalhe de uma refeição |
| GET | `/api/images/{id}` | Retorna os bytes da imagem de uma avaliação |

### Autenticadas (`auth:sanctum`)

| Método | Rota | Descrição |
|--------|------|-----------|
| GET | `/api/me` | Dados do usuário autenticado |
| POST | `/api/logout` | Revoga o token atual |
| GET | `/api/reviews` | Lista avaliações |
| GET | `/api/reviews/{id}` | Detalhe de uma avaliação |
| POST | `/api/reviews` | Cria uma avaliação (com imagem opcional) |
| PUT | `/api/reviews/{id}` | Atualiza a própria avaliação |
| DELETE | `/api/reviews/{id}` | Remove a própria avaliação |

### Administrador (`auth:sanctum` + papel admin)

| Método | Rota | Descrição |
|--------|------|-----------|
| POST | `/api/meals` | Cadastra uma refeição no cardápio |
| PUT | `/api/meals/{id}` | Atualiza uma refeição |
| DELETE | `/api/meals/{id}` | Remove uma refeição |

## Testes

A coleção de testes está em [`api/tests-bruno/`](api/tests-bruno) e foi feita com o
**[Bruno](https://www.usebruno.com)** — um cliente HTTP open source e offline-first.
Como as requisições são salvas em arquivos `.bru` versionados no repositório, não é
preciso "importar" no sentido tradicional: basta abrir a pasta.

**Como abrir e rodar:**

1. Instale o Bruno (app desktop ou extensão de VS Code).
2. _Open Collection_ → selecione a pasta `api/tests/bruno`.
3. No seletor de ambiente (canto superior direito), escolha **local**
   (já aponta para `http://127.0.0.1:8000/api`).
4. Com o servidor rodando (`php artisan serve`), execute as requisições.

**Encadeamento automático de variáveis:** as requisições `Login` e `Register` salvam
o token retornado na variável de ambiente `{{token}}`; as rotas autenticadas já o
utilizam via `Authorization: Bearer`. Da mesma forma, criar uma refeição/avaliação
salva `{{meal_id}}`, `{{review_id}}` e `{{image_id}}`, reaproveitados nas requisições
seguintes. Ou seja, não é necessário copiar tokens ou IDs manualmente.

**Ordem sugerida para exercitar todo o fluxo:**

1. **Auth › Login** (admin `admin` / `admin123`, criado pelo seeder) — obtém o token.
2. **Meals › Create meal** — cadastra uma refeição (salva `meal_id`).
3. **Meals › List meals** / **Show meal** — consulta pública.
4. **Auth › Register** — cria um usuário comum e troca o token ativo.
5. **Reviews › Create review** — avalia a refeição (habilite o campo `image` para
   testar upload). Salva `review_id` e `image_id`.
6. **Reviews › List reviews** e **Images › Show image**.
7. **Casos de erro:** avaliar a mesma refeição de novo → `409`; editar avaliação de
   outro usuário → `403`; criar refeição com usuário comum → `403`.

> **Upload de imagem:** o campo `image` em _Create review_ vem desabilitado por padrão
> (não há como fixar um caminho de arquivo válido em outra máquina). Habilite-o no Bruno
> e selecione um arquivo JPG/PNG/WEBP de até 2 MB. A edição de avaliação usa
> `POST` + `_method=PUT` (method spoofing), pois alguns clientes não enviam arquivos
> corretamente em requisições `PUT` multipart.

Cada requisição também inclui asserções automáticas (aba _Tests_ do Bruno) verificando
o status HTTP e o formato da resposta, executadas ao rodar a coleção.
