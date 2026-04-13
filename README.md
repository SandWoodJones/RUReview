# RUReview

 Aplicação web para avaliação do Restaurante Universitário da UTFPR-PG, desenvolvido como projeto prático da disciplina de Desenvolvimento Web - Servidor.

## Integrantes

- Raul Rodrigues de Oliveira (242272)
- Gabryel Kopp

## Sobre

O RUReview permite que alunos cadastrados avaliem as refeições do RU de forma simples e organizada. É possível acompanhar a qualidade das refeições ao longo do tempo, comparar o cardápio divulgado com o que foi servido e registrar reclamações.

## Funcionalidades
- **Autenticação** - cadastro e login de usuários.
- **Cardápio diário** - administradores cadastram o cardápio de almoço e janta providenciado pela AMI serviços.
- **Avaliação de refeições** - usuários autenticados avaliam cada refeição com uma nota de 1 a 5, um comentário livre e uma foto opcional.
- **Listagem de avaliações** - visualização das avaliações.
- **Painel do administrador** - gerenciamento do cardápio mensal e acompanhamento das reclamações recebidas.

## Tecnologias
- PHP 8+
- SQLite 3
- Tailwind CSS (via CDN)
- Font Awesome (via CDN)

## Requisitos
- PHP 8.0 ou superior
- Extensão `sqlite3` habilitada no PHP

## Instalação

1. Clone o repositório
2. Crie o banco de dados: `php database/migrate.php`
3. Inicie o servidor de desenvolvimento: `php -S localhost:8000 -t public`
4. Acesse no navegador: [http://localhost:8000](http://localhost:8000)
