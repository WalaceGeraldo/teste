# 🔐 Sistema de Login Seguro com QA Tooling

Um sistema completo de autenticação de usuários (Cadastro, Login, Sessão) com foco em **Qualidade de Software (QA)** e Testes Automatizados.

O projeto inclui uma **API RESTful**, uma **Dashboard de Testes HTTP** e uma **Ferramenta de Linha de Comando (CLI)** para facilitar a validação e manutenção.

## 🚀 Funcionalidades Principais

*   **Autenticação Segura**: Hash de senha com Bcrypt (`password_hash`), Proteção contra SQL Injection (PDO).
*   **Gestão de Sessão**: Controle de acesso, logout e validação de existência do usuário a cada requisição.
*   **API RESTful**: Endpoints para integração externa (`GET /users`, `POST /login`, `DELETE /users/{id}`).
*   **QA Tooling**:
    *   **Dashboard HTTP**: Interface web para testar endpoints da API visualmente.
    *   **CLI Tool (`qa.bat`)**: Menu interativo no terminal para gerenciar usuários e ver logs.
    *   **Automated Tests**: Scripts PHP que validam o fluxo de cadastro e login.

---

## 🛠️ Configuração e Instalação

### Pré-requisitos
*   PHP 7.4 ou superior.
*   MySQL Server rodando.
*   Extensões PHP `pdo_mysql` e `mysqli` ativadas.

### 1. Configurar Banco de Dados
Edite o arquivo `config/database.php` com suas credenciais do MySQL:
```php
return [
    'host' => '127.0.0.1',
    'dbname' => 'qa_login_system',
    'username' => 'root',
    'password' => 'SuaSenhaAqui',
    // ...
];
```

### 2. Inicializar o Banco
Rode o script para criar o banco e as tabelas automaticamente:
```bash
php scripts/init_db.php
```

### 3. Iniciar o Servidor
Para rodar o servidor local embutido do PHP:
*   Clique duas vezes em `run_server.bat`
*   Ou rode no terminal: `./run_server.bat`

O sistema estará acessível em: **[http://localhost:8000](http://localhost:8000)**

---

## 📡 API e Dashboards QA

Acesse a dashboard de testes da API para validar os endpoints visualmente:
👉 **[http://localhost:8000/api-dashboard.php](http://localhost:8000/api-dashboard.php)**

### Endpoints Disponíveis

| Método | Rota | Descrição |
| :--- | :--- | :--- |
| `GET` | `/api.php?route=stats` | Retorna total de usuários e falhas de login. |
| `GET` | `/api.php?route=users` | Lista todos os usuários cadastrados. |
| `POST` | `/api.php?route=auth/login` | Realiza login (JSON Body: `username`, `password`). |
| `POST` | `/api.php?route=auth/register` | Cadastra usuário (JSON Body: `username`, `email`, `password`). |
| `DELETE` | `/api.php?route=users/{id}` | Remove um usuário permanentemente pelo ID. |

---

## 💻 Ferramenta de Linha de Comando (CLI)

Para quem prefere o terminal, criei uma ferramenta interativa poderosa.

Basta rodar o comando:
```bash
qa
```
(Ou executar o arquivo `qa.bat` na raiz)

**O que você pode fazer nela:**
1.  Listar todos os usuários.
2.  Ver logs de tentativas de login (com IP e Status).
3.  Ver estatísticas gerais do sistema.
4.  **Deletar usuários** rapidamente.
5.  Rodar a suíte de testes automatizados.

---

## ✅ Executando Testes Automatizados

Para garantir que o core do sistema (Cadastro/Login) está funcionando:

```bash
php tests/run_tests_simple.php
```
Este script cria um usuário de teste, tenta logar, verifica se o login funciona e depois limpa o banco de dados.

---

## 📁 Estrutura do Projeto

```
/
├── config/             # Configurações (Database)
├── public/             # Arquivos Web (Frontend)
│   ├── css/            # Estilos CSS
│   ├── api.php         # Entrypoint da API
│   ├── api-dashboard.php # Dashboard de Testes da API
│   ├── dashboard.php   # Área logada do usuário
│   ├── index.php       # Tela de Login
│   └── register.php    # Tela de Cadastro
├── scripts/            # Scripts utilitários (CLI)
│   ├── init_db.php     # Setup do Banco
│   ├── qa_tool.php     # Ferramenta interativa de QA
│   └── delete_user.php # Script de deleção Avulso
├── src/                # Backend (Classes PHP)
│   ├── User.php        # Model de Usuário
│   ├── Database.php    # Conexão PDO
│   ├── ApiController.php # Controlador da API
│   └── QAService.php   # Serviços de QA (Logs/Stats)
├── tests/              # Testes Automatizados
└── run_server.bat      # Launcher do Servidor
└── qa.bat              # Launcher da Ferramenta CLI
```

## 🛡️ Segurança Implementada

1.  **Password Hashing**: Senhas salvas com `password_hash()` (Bcrypt).
2.  **Prepared Statements**: Todas as queries SQL usam `prepare()` para evitar injeção.
3.  **Session Security**: Validação de "User Exists" a cada request na dashboard para evitar sessões órfãs.
4.  **Audit Logs**: Tabela `login_logs` registra IPs e status de todas as tentativas.
