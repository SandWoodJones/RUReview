# Requisitos do trabalho 1

## Requisitos obrigatórios

- [x] PHP 8+ `0.1`
- [x] Banco de dados relacional (MySQL, MariaDB ou similar) `0.1`
- [x] Documentação de configuração e instalação `0.1`
- [x] Padrão de estruturação de código separando lógica (controllers, models, validações) do HTML (views) — estilo MVC `0.2`
- [x] Formulários com envio de dados ao servidor e validações server-side em PHP (não no HTML)
- [x] Autenticação com controle de login para áreas protegidas (preferencialmente via sessão) `0.2`
- [x] Interface adequada com mensagens de feedback e erros tratados no servidor `0.2`
- [x] Pelo menos **3 telas** com formulários distintos de cadastro/edição + listagens ou tabelas (login não conta como formulário) `0.2`

## Restrições

- **Não usar** Laravel ou qualquer outro framework PHP
- Packages Composer são permitidos, desde que não sejam frameworks
- **Temas proibidos:**
  - Sistema de Blog ou Notícias
  - Sistema de Controle de Tarefas
  - Sistema de Controle de Estoque
  - Formulários simples estilo "Contact Form"
  - Sistemas com poucas páginas

---

# Requisitos do trabalho 2

## Requisitos obrigatórios

- [x] PHP 8+ com Orientação a Objetos
- [x] Composer com Autoload e uso de packages PHP
- [x] Banco de dados via PDO (MySQL, MariaDB, PostgreSQL ou outro)
- [x] Sistema de rotas com URLs transparentes
- [x] Padrão MVC mantido e melhorado em relação ao Trabalho 1
- [x] Documentação de configuração e instalação atualizada
- [x] Interface adequada com mensagens de feedback e erros
- [x] Melhorias e correções aplicadas a partir do Trabalho 1

## Requisitos opcionais

- [ ] Framework de interface (Bootstrap, Tailwind, Shadcn ou outro)
- [ ] Uso de API de terceiro (Email, Vídeo, Imagem, Search, etc.)

## Restrições

- **Não usar** Laravel ou qualquer outro framework PHP
- **Temas proibidos:** mesmos do Trabalho 1

## Critérios de avaliação

- Aderência aos conceitos vistos em aula
- Cobertura dos requisitos obrigatórios
- Originalidade e profundidade no uso dos conceitos
- Padronização e comentários no código
- Estruturação do código e arquitetura
- Corretude do aplicativo
- Qualidade das melhorias em relação ao Trabalho 1

A nota final é composta pela **apresentação + análise do código fonte**.

---

# Requisitos do trabalho 3

## Sobre

Desenvolvimento de uma **API (Web Service) em Laravel** no mesmo contexto/tema do projeto
(RUReview). A API expõe os dados e funcionalidades do sistema (usuários, cardápios, refeições
e avaliações) para que aplicações/empresas terceiras possam se integrar. Os controllers retornam
**apenas JSON** — não há views. A API é protegida por autenticação baseada em tokens (Laravel Sanctum).

## Requisitos obrigatórios

- [ ] API desenvolvida em **Laravel** (estrutura MVC do framework)
- [ ] Definição de rotas **exclusivamente para API** (`routes/api.php`)
- [ ] Controllers retornando **somente JSON** (sem construção de views)
- [ ] Validação de campos nos controllers para **gravação e atualização** (validators do Laravel ou customizados)
- [ ] Models conectados ao banco via **Eloquent**, com criação das **migrations**
- [ ] Relacionamentos entre models e **propriedades protegidas** (ex.: senha em `$hidden`, `$fillable`/`$guarded`)
- [ ] Padrão de **resposta JSON unificado** (ex.: campos `status`, `data`, `message`)
- [ ] Rotas de criação, atualização, consulta e remoção — **POST, PUT, GET e DELETE**
- [ ] **Autenticação da API com tokens** (Laravel Sanctum), enviados no header `Authorization: Bearer <token>`

## Entrega

- [ ] Repositório **público** no GitHub com histórico de commits de **ambos** os integrantes
- [ ] `main` com a versão a ser entregue (desenvolvimento em outras branches)
- [ ] README com nomes dos integrantes e **relatório** (particularidades, bugs, funcionalidades faltantes, instalação)
- [ ] Descrição das **atividades desenvolvidas por cada integrante**
- [ ] Código-fonte em PHP/Laravel
- [ ] Documentação de instalação e configuração
- [ ] **Coleção de testes** exportada (Insomnia / Postman / Thunder Client) salva na pasta do projeto,
      com instruções de importação — **ou** testes em PHP (Guzzle / PHPUnit)
- [ ] Lista de testes documentada: rotas + dados de teste
- [ ] Apenas **um** integrante envia o link do repositório no Moodle

## Observações

- Diferentemente dos trabalhos 1 e 2, **o uso do Laravel é obrigatório** neste trabalho.
- Recomenda-se clonar o projeto em outra máquina e validar a instalação a partir da documentação
  antes da entrega.
