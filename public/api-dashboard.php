<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Dashboard | Métodos HTTP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.7.0/styles/atom-one-dark.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.7.0/highlight.min.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; }
        .method-get { color: #059669; background: #ecfdf5; border: 1px solid #059669; }
        .method-post { color: #2563eb; background: #eff6ff; border: 1px solid #2563eb; }
        .method-delete { color: #dc2626; background: #fef2f2; border: 1px solid #dc2626; }
        pre { margin: 0; border-radius: 0.375rem; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">

    <div class="min-h-screen flex flex-col">
        <nav class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <span class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600">
                            HTTP Methods Dashboard
                        </span>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="index.php" class="text-gray-500 hover:text-gray-700 text-sm font-medium">Voltar ao App</a>
                    </div>
                </div>
            </div>
        </nav>

        <main class="flex-1 py-10">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900">Documentação & Teste de API</h2>
                    <p class="mt-1 text-gray-500">Teste os endpoints da sua API RESTful diretamente pelo navegador.</p>
                </div>

                <!-- GET /api.php?route=stats -->
                <div class="bg-white shadow rounded-lg mb-6 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between bg-gray-50">
                        <div class="flex items-center space-x-3">
                            <span class="px-2 py-1 text-xs font-bold rounded uppercase method-get">GET</span>
                            <code class="text-sm font-mono text-gray-700">/api.php?route=stats</code>
                            <span class="text-sm text-gray-500">- Estatísticas do Sistema</span>
                        </div>
                        <button onclick="testEndpoint('stats')" class="px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition">
                            Testar ▶
                        </button>
                    </div>
                    <div class="px-6 py-4 bg-gray-900" id="response-stats" style="display:none">
                        <pre><code class="language-json" id="json-stats"></code></pre>
                    </div>
                </div>

                <!-- GET /api.php?route=users -->
                <div class="bg-white shadow rounded-lg mb-6 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between bg-gray-50">
                        <div class="flex items-center space-x-3">
                            <span class="px-2 py-1 text-xs font-bold rounded uppercase method-get">GET</span>
                            <code class="text-sm font-mono text-gray-700">/api.php?route=users</code>
                            <span class="text-sm text-gray-500">- Listar Usuários</span>
                        </div>
                        <button onclick="testEndpoint('users')" class="px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition">
                            Testar ▶
                        </button>
                    </div>
                    <div class="px-6 py-4 bg-gray-900" id="response-users" style="display:none">
                        <pre><code class="language-json" id="json-users"></code></pre>
                    </div>
                </div>

                <!-- DELETE /api.php?route=users/{id} -->
                <div class="bg-white shadow rounded-lg mb-6 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between bg-gray-50">
                        <div class="flex items-center space-x-3">
                            <span class="px-2 py-1 text-xs font-bold rounded uppercase method-delete">DELETE</span>
                            <code class="text-sm font-mono text-gray-700">/api.php?route=users/{id}</code>
                            <span class="text-sm text-gray-500">- Excluir Usuário</span>
                        </div>
                    </div>
                    <div class="px-6 py-4">
                        <div class="flex gap-4 mb-4">
                            <input type="number" id="delete-id" placeholder="ID do Usuário" class="border rounded px-3 py-2 text-sm w-32">
                            <button onclick="testDelete()" class="px-4 py-2 bg-red-600 text-white text-sm rounded hover:bg-red-700 transition">
                                Excluir Usuário 🗑️
                            </button>
                        </div>
                    </div>
                    <div class="px-6 py-4 bg-gray-900" id="response-delete" style="display:none">
                        <pre><code class="language-json" id="json-delete"></code></pre>
                    </div>
                </div>

                <!-- POST /api.php?route=auth/login -->
                <div class="bg-white shadow rounded-lg mb-6 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between bg-gray-50">
                        <div class="flex items-center space-x-3">
                            <span class="px-2 py-1 text-xs font-bold rounded uppercase method-post">POST</span>
                            <code class="text-sm font-mono text-gray-700">/api.php?route=auth/login</code>
                            <span class="text-sm text-gray-500">- Autenticação</span>
                        </div>
                    </div>
                    <div class="px-6 py-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <input type="text" id="login-username" placeholder="Usuário" class="border rounded px-3 py-2 text-sm w-full">
                            <input type="text" id="login-password" placeholder="Senha" class="border rounded px-3 py-2 text-sm w-full">
                        </div>
                        <button onclick="testLogin()" class="px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition w-full md:w-auto">
                            Enviar Request ▶
                        </button>
                    </div>
                    <div class="px-6 py-4 bg-gray-900" id="response-login" style="display:none">
                        <pre><code class="language-json" id="json-login"></code></pre>
                    </div>
                </div>

                 <!-- POST /api.php?route=auth/register -->
                 <div class="bg-white shadow rounded-lg mb-6 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between bg-gray-50">
                        <div class="flex items-center space-x-3">
                            <span class="px-2 py-1 text-xs font-bold rounded uppercase method-post">POST</span>
                            <code class="text-sm font-mono text-gray-700">/api.php?route=auth/register</code>
                            <span class="text-sm text-gray-500">- Cadastro</span>
                        </div>
                    </div>
                    <div class="px-6 py-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <input type="text" id="reg-username" placeholder="Usuário" class="border rounded px-3 py-2 text-sm w-full">
                            <input type="email" id="reg-email" placeholder="Email" class="border rounded px-3 py-2 text-sm w-full">
                            <input type="text" id="reg-password" placeholder="Senha" class="border rounded px-3 py-2 text-sm w-full">
                        </div>
                        <button onclick="testRegister()" class="px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition w-full md:w-auto">
                            Enviar Request ▶
                        </button>
                    </div>
                    <div class="px-6 py-4 bg-gray-900" id="response-register" style="display:none">
                        <pre><code class="language-json" id="json-register"></code></pre>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <script>
        const API_URL = 'http://localhost:8000/api.php?route=';

        async function testEndpoint(route) {
            const responseDiv = document.getElementById(`response-${route}`);
            const jsonCode = document.getElementById(`json-${route}`);
            
            responseDiv.style.display = 'block';
            jsonCode.textContent = 'Carregando...';
            hljs.highlightElement(jsonCode);

            try {
                const res = await fetch(API_URL + route);
                const data = await res.json();
                jsonCode.textContent = JSON.stringify(data, null, 2);
                hljs.highlightElement(jsonCode);
            } catch (err) {
                jsonCode.textContent = 'Erro: ' + err.message;
            }
        }

        async function testLogin() {
            const user = document.getElementById('login-username').value;
            const pass = document.getElementById('login-password').value;
            const responseDiv = document.getElementById('response-login');
            const jsonCode = document.getElementById('json-login');

            responseDiv.style.display = 'block';
            jsonCode.textContent = 'Enviando...';

            try {
                const res = await fetch(API_URL + 'auth/login', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ username: user, password: pass })
                });
                const data = await res.json();
                jsonCode.textContent = JSON.stringify(data, null, 2);
                hljs.highlightElement(jsonCode);
            } catch (err) {
                jsonCode.textContent = 'Erro: ' + err.message;
            }
        }

        async function testRegister() {
            const user = document.getElementById('reg-username').value;
            const email = document.getElementById('reg-email').value;
            const pass = document.getElementById('reg-password').value;
            const responseDiv = document.getElementById('response-register');
            const jsonCode = document.getElementById('json-register');

            responseDiv.style.display = 'block';
            jsonCode.textContent = 'Enviando...';

            try {
                const res = await fetch(API_URL + 'auth/register', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ username: user, email: email, password: pass })
                });
                const data = await res.json();
                jsonCode.textContent = JSON.stringify(data, null, 2);
                hljs.highlightElement(jsonCode);
            } catch (err) {
                jsonCode.textContent = 'Erro: ' + err.message;
            }
        }

        async function testDelete() {
            const id = document.getElementById('delete-id').value;
            if (!id) { alert('Digite o ID do usuário!'); return; }
            
            const responseDiv = document.getElementById('response-delete');
            const jsonCode = document.getElementById('json-delete');

            responseDiv.style.display = 'block';
            jsonCode.textContent = 'Deletando...';

            try {
                const res = await fetch(API_URL + 'users/' + id, {
                    method: 'DELETE'
                });
                const data = await res.json();
                jsonCode.textContent = JSON.stringify(data, null, 2);
                hljs.highlightElement(jsonCode);
                
                // Atualiza a lista de usuários se estiver aberta
                testEndpoint('users');
            } catch (err) {
                jsonCode.textContent = 'Erro: ' + err.message;
            }
        }
    </script>
</body>
</html>
