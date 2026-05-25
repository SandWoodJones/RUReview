# RUReview

Aplicação web para avaliação do Restaurante Universitário da UTFPR-PG, desenvolvido como projeto prático da disciplina de Desenvolvimento Web - Servidor.

## Integrantes

- Raul Rodrigues de Oliveira (242272)
- Gabryel Kopp (2398419)

## Sobre

O RUReview permite que alunos cadastrados avaliem as refeições do RU de forma simples e organizada. É possível acompanhar a qualidade das refeições ao longo do tempo, comparar o cardápio divulgado com o que foi servido e registrar reclamações.

## Funcionalidades

- **Autenticação** — cadastro e login de usuários.
- **Cardápio diário** — administradores cadastram o cardápio de almoço e janta providenciado pela AMI serviços.
- **Avaliação de refeições** — usuários autenticados avaliam cada refeição com uma nota de 1 a 5, um comentário livre e uma foto opcional.
- **Listagem de avaliações** — visualização das avaliações com filtro por ordenação.
- **Painel do administrador** — gerenciamento do cardápio mensal e acompanhamento das avaliações recebidas.

## Tecnologias

- PHP 8+
- PDO + MySQL / MariaDB
- Composer (Autoload + packages)
- Tailwind CSS (via CDN)
- Font Awesome (via CDN)

## Requisitos

- PHP 8.0 ou superior
- MySQL ou MariaDB
- Composer

## Instalação do Composer

**Linux/macOS:**
```bash
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php --install-dir=/usr/local/bin --filename=composer
php -r "unlink('composer-setup.php');"
```

**Windows:** baixe o instalador em [https://getcomposer.org/download](https://getcomposer.org/download)

**Via Nix** (se usar NixOS ou nix-shell):
```bash
nix-shell
```

## Instalação

1. Clone o repositório
2. Instale as dependências: `composer install`
3. Crie o banco de dados: `php database/migrate.php`
4. (Opcional) Popule com dados iniciais: `php database/seed.php`
5. Inicie o servidor: `php -S localhost:8000 -t src/public`
6. Acesse: [http://localhost:8000](http://localhost:8000)
