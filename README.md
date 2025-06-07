# Trust Me API - Backend

Este é o backend da aplicação Trust Me, desenvolvido em Laravel 8.

## Requisitos

- PHP >= 7.4
- Composer
- MySQL/PostgreSQL
- Node.js (para desenvolvimento)

## Instalação

1. Clone o repositório:
```bash
git clone https://github.com/CelDarley/trustme-api.git
cd trustme-api
```

2. Instale as dependências:
```bash
composer install
```

3. Configure o arquivo de ambiente:
```bash
cp .env.example .env
```

4. Configure as variáveis de ambiente no arquivo `.env`:
```env
APP_NAME="Trust Me API"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=trustme
DB_USERNAME=root
DB_PASSWORD=

# CORS Configuration
SANCTUM_STATEFUL_DOMAINS=localhost:3000
SESSION_DOMAIN=localhost
```

5. Gere a chave da aplicação:
```bash
php artisan key:generate
```

6. Execute as migrações:
```bash
php artisan migrate
```

7. Execute os seeders (se houver):
```bash
php artisan db:seed
```

## Executando o servidor

Para desenvolvimento:
```bash
php artisan serve
```

O servidor estará disponível em `http://localhost:8000`

## API Endpoints

A API estará disponível em `http://localhost:8000/api/`

### Autenticação
- `POST /api/register` - Registro de usuário
- `POST /api/login` - Login de usuário
- `POST /api/logout` - Logout de usuário

### Rotas protegidas
Todas as rotas protegidas requerem o header:
```
Authorization: Bearer {token}
```

## Estrutura do Projeto

- `app/` - Código da aplicação (Models, Controllers, etc.)
- `config/` - Arquivos de configuração
- `database/` - Migrações e seeders
- `routes/` - Definição das rotas
- `storage/` - Arquivos de armazenamento

## Desenvolvimento

Para desenvolvimento com hot reload:
```bash
php artisan serve --host=0.0.0.0 --port=8000
```

## Produção

Para produção, configure:
1. `APP_ENV=production`
2. `APP_DEBUG=false`
3. Configure o banco de dados de produção
4. Execute `php artisan config:cache`
5. Execute `php artisan route:cache`
6. Execute `php artisan view:cache`
