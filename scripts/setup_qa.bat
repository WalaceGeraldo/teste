@echo off
echo Resetting database for QA...
copy "scripts\seed_qa.json" "data\users.json" /Y
echo Database reset with QA credentials.
echo User: qa / qatest
pause
