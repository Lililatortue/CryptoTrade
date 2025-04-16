@echo off
echo === ARRET DE XAMPP ET SUPPRESSION DE LA TACHE PLANIFIEE ===

:: Fermer Apache et MySQL (ferme l'interface graphique, si lancée)
taskkill /IM httpd.exe /F > nul
taskkill /IM mysqld.exe /F > nul
taskkill /IM xampp-control.exe /F > nul

:: Supprimer la tâche planifiée
schtasks /delete /tn "CryptoScriptJob" /f

echo === Tâche supprimée et services arrêtés. ===
pause
