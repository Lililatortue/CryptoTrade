@echo off
echo === DEMARRAGE DE XAMPP ET CREATION DE LA TACHE PLANIFIEE ===

:: Modifier ici les chemins vers ton XAMPP et script PHP
set XAMPP_DIR=C:\xampp
set PHP_SCRIPT=.\backend\service\CryptoMarket.php

:: Aller dans le dossier XAMPP
cd /d "%XAMPP_DIR%"

:: Démarrer Apache et MySQL
start "" xampp_start.exe
timeout /t 10 > nul

:: Créer la tâche planifiée (exécutée toutes les 20 minutes)
schtasks /create /tn "CryptoScriptJob" /tr "php %PHP_SCRIPT%" /sc minute /mo 20 /ru "SYSTEM"

echo === Tâche planifiée créée avec succès. ===
pause
