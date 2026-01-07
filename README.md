# Sistema de Gestão de Corretores

Este é um sistema desenvolvido em Laravel 12 com Vue 3 (Inertia.js), PrimeVue e Tailwind CSS para gestão de usuários, papéis (perfis) e permissões.

## Estrutura do Projeto

- **Backend**: Laravel 12
- **Frontend**: Vue 3 + PrimeVue 4
- **Autenticação**: Laravel Breeze
- **Permissões**: Spatie Laravel Permission

## Como subir o ambiente

O projeto utiliza o **Laravel Sail** (Docker) para facilitar o desenvolvimento.

### Pré-requisitos
- Docker instalado em sua máquina.

### Passo a passo para execução

1. **Subir os containers**:
   ```bash
   ./vendor/bin/sail up -d
   ```

2. **Instalar dependências do PHP**:
   ```bash
   ./vendor/bin/sail composer install
   ```

3. **Instalar dependências do Node**:
   ```bash
   ./vendor/bin/sail npm install
   ```

4. **Gerar chave da aplicação**:
   ```bash
   ./vendor/bin/sail artisan key:generate
   ```

5. **Executar Migrations e Seeders**:
   ```bash
   ./vendor/bin/sail artisan migrate --seed
   ```
   *Nota: O seeder principal `PermissionSeeder` criará os papéis e o usuário administrador.*

6. **Compilar Assets**:
   ```bash
   ./vendor/bin/sail npm run build
   ```
   *Ou use `./vendor/bin/sail npm run dev` para desenvolvimento em tempo real.*

## Acesso Inicial (Desenvolvimento)

O seeder padrão cria os seguintes acessos para teste:

### Administrador do Sistema (Super Admin)
- **E-mail**: `admin@corretores.com`
- **Senha**: `admin123`

### Usuário de Teste
- **E-mail**: `test@example.com`
- **Senha**: `password` (Senha padrão do Breeze factory)

## Gestão de Permissões e Papéis

O sistema utiliza o pacote `spatie/laravel-permission` para controle de acesso granular.

### Fluxo de Configuração via Interface

1.  **Criar Permissões**: Vá em `Configurações > Permissões`. Clique em "Nova Permissão" para registrar ações específicas (ex: `edit_users`, `manage_reports`).
2.  **Criar/Editar Papéis (Perfis)**: Vá em `Configurações > Papéis (Perfis)`. Clique em "Criar Novo Papel". Defina um nome (ex: `Gerente`) e selecione as permissões que este perfil deve possuir.
3.  **Atribuir a Usuários**: Vá em `Cadastro > Usuários`. Na coluna "Ações", utilize o ícone de escudo para alterar o papel do usuário.

### Uso no Código (Laravel)

Para proteger rotas com base em permissões, utilize o middleware `permission`:

```php
Route::get('/relatorios', [ReportController::class, 'index'])
    ->middleware('permission:view_reports');
```

Para proteger blocos de código ou verificar no Controller:

```php
if ($user->can('edit_users')) {
    // Lógica permitida
}
```

### Uso no Frontend (Vue 3)

As permissões do usuário logado estão disponíveis globalmente via props do Inertia:

```javascript
const canEdit = usePage().props.auth.user.can.includes('edit_users');
```

## Funcionalidades Implementadas

- **Dashboard**: Visão geral com estatísticas básicas.
- **Gestão de Usuários**: Listagem, criação, edição de dados e alteração de papéis.
- **Gestão de Papéis (Perfis)**: Criação, edição e associação de permissões.
- **Gestão de Permissões**: Criação, edição e exclusão de permissões granulares.
