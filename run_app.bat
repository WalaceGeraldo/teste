@echo off
TITLE PHP Server
echo Iniciando servidor PHP em localhost:8000...
echo Mantenha esta janela aberta enquanto testa o login.
echo.
echo Para parar, pressione Ctrl+C.
echo.

:: Verifica se php esta no path
where php >nul 2>nul
if %errorlevel% neq 0 (
    echo ERRO: PHP nao encontrado no PATH systema.
    echo Por favor instale o PHP ou adicione ao PATH.
    pause
    exit /b
)

:: Inicia o servidor na raiz do projeto
cd /d "%~dp0"
echo.
echo ========================================================
echo  APP: http://localhost:8000/public/index.html
echo  QA : http://localhost:8000/public/qa.html
echo ========================================================
echo.
start http://localhost:8000/public/index.html
php -S localhost:8000 -t .

exit
