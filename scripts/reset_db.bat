@echo off
echo Resetting database for QA...
copy "scripts\seed_users.json" "data\users.json" /Y
echo Database reset complete.
pause
