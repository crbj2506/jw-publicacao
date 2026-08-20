# JW Publicação

Sistema web para gestão de publicações, congregações, estoque, inventário e permissões de usuários em ambiente de organização religiosa.

## Sobre o projeto

Este projeto foi desenvolvido em Laravel e tem como objetivo centralizar o controle de material de publicação e a administração dos usuários vinculados a congregações.

A aplicação permite:

- gerir congregações;
- controlar usuários e permissões por perfil;
- cadastrar publicações, volumes e conteúdos;
- registrar locais e estoque;
- acompanhar inventários e movimentações;
- organizar envios e distribuição por hierarquia;
- manter histórico e auditoria das ações realizadas no sistema.

## Stack tecnológica

- PHP 8.2
- Laravel 12
- MySQL
- Composer
- Node.js + npm
- Vite
- Bootstrap (utilizado pela interface do projeto)

## Funcionalidades principais

### 1. Autenticação e autorização

- login com verificação de e-mail;
- controle de acesso por perfil;
- permissões com papéis como Publicador, Servo, Ancião e Administrador;
- rotas protegidas por middleware de permissão.

### 2. Gestão de congregações

- cadastro e manutenção de congregações;
- definição da congregação ativa;
- organização por unidade administrativa.

### 3. Usuários e permissões

- cadastro de usuários;
- associação com congregação;
- controle de acessos por perfil;
- gestão centralizada de permissões e auditoria da criação e alteração de registros.

### 4. Publicações, conteúdos e volumes

- cadastro de publicações com nome, código, observação, proporção e imagem;
- estrutura de volumes e conteúdos;
- organização dos materiais por categoria e contexto operacional.

### 5. Estoque e locais

- cadastro de locais de armazenamento;
- controle de estoque por publicação;
- manutenção do histórico de movimentações e disponibilidade.

### 6. Inventário e pedidos

- acompanhamento de materiais disponíveis;
- registros de pedidos e movimentações;
- controle operacional para suporte do fluxo de distribuição.

### 7. Envio por hierarquia

- interface para envio e organização por níveis;
- gestão de volumes, conteúdos e publicações de forma rápida;
- apoio ao fluxo de distribuição entre grupos da estrutura.

### 8. Auditoria e logs

- registro de ações realizadas no sistema;
- histórico de alterações e acompanhamento de uso.

## Requisitos do ambiente

Antes de instalar, certifique-se de ter instalado:

- PHP 8.2 ou superior
- Composer
- Node.js 18+ e npm
- MySQL 8+
- Git

Extensões PHP recomendadas:

```ini
extension=openssl
extension=fileinfo
extension=curl
extension=pdo_mysql
extension=mbstring
```

## Instalação

### 1. Clone o repositório

```bash
git clone <url-do-repositorio>
cd jw-publicacao
```

### 2. Instale as dependências do PHP

```bash
composer install
```

### 3. Configure o ambiente

```bash
cp .env.example .env
```

Edite o arquivo `.env` e defina as informações do banco de dados, por exemplo:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=jw-publicacao
DB_USERNAME=root
DB_PASSWORD=
```

> Se preferir outro nome para o banco, ajuste o valor do `DB_DATABASE` conforme sua necessidade.

### 4. Crie o banco de dados

No MySQL, crie a base de dados antes de rodar as migrations:

```sql
CREATE DATABASE `jw-publicacao`;
```

### 5. Gere a chave da aplicação

```bash
php artisan key:generate
```

### 6. Inicialize o banco

```bash
php artisan migrate
```

Se quiser também criar links de armazenamento para arquivos de imagens e uploads:

```bash
php artisan storage:link
```

### 7. Instale as dependências do front-end

```bash
npm install
```

### 8. Rode o ambiente de desenvolvimento

```bash
npm run dev
```

Em outro terminal, inicie o servidor local do Laravel:

```bash
php artisan serve
```

A aplicação ficará disponível em:

```text
http://localhost:8000
```

## Verificação de e-mail e permissões

A aplicação já está configurada para exigir verificação de e-mail do usuário e desabilitar registro público.

Após o primeiro acesso, o administrador deve configurar os perfis e as permissões no banco ou pela área administrativa do sistema, de acordo com o fluxo da organização.

Os perfis esperados pelo sistema incluem:

- Publicador
- Servo
- Ancião
- Administrador

## Fluxo de uso recomendado

1. Crie ou configure as congregações.
2. Cadastre os usuários e atribua as permissões corretas.
3. Defina a congregação ativa.
4. Cadastre publicações, volumes e conteúdos.
5. Configure locais e estoque.
6. Realize os controles de inventário e envio.
7. Acompanhe logs e auditoria para manter o histórico das ações.

## Scripts úteis

### Desenvolvimento front-end

```bash
npm run dev
```

### Build para produção

```bash
npm run build
```

### Limpeza do cache do Laravel

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## Observações importantes

- O sistema foi pensado para uso interno e com controle de acessos por perfil.
- O cadastro de usuários e permissões deve ser administrado com atenção para manter a segurança e organização do ambiente.
- Em ambientes de produção, configure corretamente as variáveis do `.env`, incluindo mail, banco de dados e URL pública da aplicação.

## Licença

Este projeto segue a licença do Laravel e pode ser adaptado conforme a necessidade da organização responsável pelo sistema.