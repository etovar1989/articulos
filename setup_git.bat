@echo off
cd /d "D:\workSpace\eduteka_new"

echo ========================================
echo  Setup Git - eduteka_new
echo ========================================

rem Eliminar .git roto si existe
if exist ".git" (
    echo Eliminando .git anterior...
    rmdir /s /q ".git"
)

git config --global user.email "etovar1989@gmail.com"
git config --global user.name "Elisxeneth Tovar"
git config --global init.defaultBranch main

git init
git branch -M main
git add .
git commit -m "Setup inicial: Docker + PHP + PostgreSQL + articulos"

git remote add origin https://github.com/etovar1989/articulos.git

echo.
echo ========================================
echo  Push a GitHub
echo  Usuario: etovar1989
echo  Contrasena: TOKEN de GitHub (no tu pass)
echo ========================================
git push -u origin main

echo.
echo ========================================
echo  COMPLETADO
echo  https://github.com/etovar1989/articulos
echo ========================================
pause
