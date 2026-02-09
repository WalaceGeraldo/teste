@echo off
:: Muda para a raiz do projeto (uma pasta acima de /tests)
cd /d "%~dp0.."

echo ==========================================
echo  RODANDO TESTES UNITARIOS (Logica)
echo ==========================================
php tests/Unit/AuthTest.php

echo.
echo ==========================================
echo  RODANDO TESTES DE INTEGRACAO (API)
echo ==========================================
echo ==========================================
echo  RODANDO TESTES DE INTEGRACAO (API)
echo ==========================================
:: O proprio script PHP vai verificar se o servidor esta rodando
php tests/Integration/ApiTest.php

echo.
echo ==========================================
echo  TESTES FINALIZADOS
echo ==========================================
pause
