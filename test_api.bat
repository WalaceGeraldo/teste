@echo off
echo Testando API Endpoints...

echo.
echo [GET] Listando usuarios...
curl -s "http://localhost:8000/api.php?route=users"

echo.
echo.
echo [POST] Tentando Login Invalido...
curl -s -X POST -H "Content-Type: application/json" -d "{\"username\":\"admin\", \"password\":\"wrong\"}" "http://localhost:8000/api.php?route=auth/login"

echo.
echo.
echo [GET] Estatisticas do Sistema...
curl -s "http://localhost:8000/api.php?route=stats"

echo.
pause
