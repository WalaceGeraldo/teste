document.getElementById('loginForm').addEventListener('submit', async function (e) {
    e.preventDefault();

    // Protocol Check
    if (window.location.protocol === 'file:') {
        alert('Erro: Você abriu este arquivo diretamente do disco (file://).\n\nPara que o login funcione, você precisa rodar um servidor web.\n\nAbra o terminal na pasta do projeto e digite:\nphp -S localhost:8000\n\nDepois acesse: http://localhost:8000/public');
        return;
    }

    // UI References
    const submitBtn = document.querySelector('.submit-btn');
    const apiResponse = document.getElementById('apiResponse');
    const usernameInput = document.getElementById('username');
    const passwordInput = document.getElementById('password');
    const loader = document.getElementById('loader');

    // Clear previous errors
    apiResponse.textContent = '';
    apiResponse.className = 'api-response';
    document.querySelectorAll('.error-message').forEach(el => el.textContent = '');

    // Simple Validation
    const username = usernameInput.value.trim();
    const password = passwordInput.value.trim();

    let hasError = false;
    if (!username) {
        document.getElementById('usernameError').textContent = 'Usuário é obrigatório';
        hasError = true;
    }
    if (!password) {
        document.getElementById('passwordError').textContent = 'Senha é obrigatória';
        hasError = true;
    }

    if (hasError) return;

    // Loading State
    submitBtn.classList.add('loading');
    submitBtn.disabled = true;

    try {
        const response = await fetch('../api/login.php', {
            method: 'POST',
            body: JSON.stringify({
                username: username,
                password: password
            }),
            headers: {
                'Content-Type': 'application/json'
            }
        });

        console.log(`Response Status: ${response.status} ${response.statusText}`);

        const text = await response.text();
        console.log('Raw response:', text); // Debug log

        let data;
        try {
            data = JSON.parse(text);
        } catch (e) {
            console.error('Server returned invalid JSON:', text);
            throw new Error(`Erro no servidor (${response.status}): Resposta inválida.`);
        }

        if (response.ok && data.success) {
            apiResponse.textContent = `Login realizado com sucesso! Bem-vindo, ${data.role || 'Usuário'}.`;
            apiResponse.classList.add('success-msg');
            apiResponse.classList.remove('error-msg');

            // Redirect simulation
            setTimeout(() => {
                // window.location.href = 'dashboard.html'; 
                console.log('Redirecting...');
            }, 1000);
        } else {
            throw new Error(data.message || 'Falha no login');
        }

    } catch (error) {
        console.error('Login error:', error);
        apiResponse.textContent = error.message || 'Erro ao conectar com o servidor.';
        apiResponse.classList.add('error-msg');
        apiResponse.classList.remove('success-msg');

        // Shake animation for error
        const panel = document.querySelector('.glass-panel');
        panel.classList.add('shake');
        setTimeout(() => panel.classList.remove('shake'), 400); // Wait for animation
    } finally {
        submitBtn.classList.remove('loading');
        submitBtn.disabled = false;
    }
});

// Add shake animation style dynamically
const styleSheet = document.createElement("style");
styleSheet.innerText = `
@keyframes shake {
    0% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    50% { transform: translateX(5px); }
    75% { transform: translateX(-5px); }
    100% { transform: translateX(0); }
}
.shake {
    animation: shake 0.4s ease-in-out;
}
`;
document.head.appendChild(styleSheet);
