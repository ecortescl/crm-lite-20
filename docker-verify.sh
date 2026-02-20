#!/bin/bash

# Script de verificación de configuración Docker
set -e

echo "🔍 Verificando configuración Docker..."
echo ""

# Colores
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

ERRORS=0

# Verificar Docker
echo -n "Verificando Docker... "
if command -v docker &> /dev/null; then
    echo -e "${GREEN}✓${NC}"
else
    echo -e "${RED}✗${NC}"
    echo -e "${RED}Docker no está instalado${NC}"
    ERRORS=$((ERRORS + 1))
fi

# Verificar Docker Compose
echo -n "Verificando Docker Compose... "
if command -v docker-compose &> /dev/null; then
    echo -e "${GREEN}✓${NC}"
else
    echo -e "${RED}✗${NC}"
    echo -e "${RED}Docker Compose no está instalado${NC}"
    ERRORS=$((ERRORS + 1))
fi

# Verificar archivos necesarios
echo ""
echo "Verificando archivos necesarios:"

FILES=(
    "Dockerfile"
    "docker-compose.yml"
    ".env.docker"
    "docker/entrypoint.sh"
    "docker/nginx/nginx.conf"
    "docker/nginx/default.conf"
    "docker/php/php.ini"
    "docker/php/php-fpm.conf"
    "docker/supervisor/supervisord.conf"
)

for file in "${FILES[@]}"; do
    echo -n "  $file... "
    if [ -f "$file" ]; then
        echo -e "${GREEN}✓${NC}"
    else
        echo -e "${RED}✗${NC}"
        ERRORS=$((ERRORS + 1))
    fi
done

# Verificar .env
echo ""
echo -n "Verificando archivo .env... "
if [ -f ".env" ]; then
    echo -e "${GREEN}✓${NC}"
    
    # Verificar APP_KEY
    echo -n "  Verificando APP_KEY... "
    if grep -q "APP_KEY=base64:" .env && ! grep -q "APP_KEY=$" .env; then
        echo -e "${GREEN}✓${NC}"
    else
        echo -e "${YELLOW}⚠${NC}"
        echo -e "${YELLOW}  APP_KEY no está configurada o está vacía${NC}"
    fi
    
    # Verificar DB_PASSWORD
    echo -n "  Verificando DB_PASSWORD... "
    if grep -q "DB_PASSWORD=secret" .env; then
        echo -e "${YELLOW}⚠${NC}"
        echo -e "${YELLOW}  Usando contraseña por defecto (cambia esto en producción)${NC}"
    elif grep -q "DB_PASSWORD=" .env && ! grep -q "DB_PASSWORD=$" .env; then
        echo -e "${GREEN}✓${NC}"
    else
        echo -e "${RED}✗${NC}"
        echo -e "${RED}  DB_PASSWORD no está configurada${NC}"
        ERRORS=$((ERRORS + 1))
    fi
else
    echo -e "${YELLOW}⚠${NC}"
    echo -e "${YELLOW}  Archivo .env no existe. Ejecuta: cp .env.docker .env${NC}"
fi

# Verificar permisos de scripts
echo ""
echo "Verificando permisos de scripts:"

SCRIPTS=(
    "docker/entrypoint.sh"
    "docker-start.sh"
    "docker-verify.sh"
)

for script in "${SCRIPTS[@]}"; do
    if [ -f "$script" ]; then
        echo -n "  $script... "
        if [ -x "$script" ]; then
            echo -e "${GREEN}✓${NC}"
        else
            echo -e "${YELLOW}⚠${NC}"
            echo -e "${YELLOW}  Ejecuta: chmod +x $script${NC}"
        fi
    fi
done

# Verificar si Docker está corriendo
echo ""
echo -n "Verificando si Docker está corriendo... "
if docker info &> /dev/null; then
    echo -e "${GREEN}✓${NC}"
else
    echo -e "${RED}✗${NC}"
    echo -e "${RED}Docker no está corriendo. Inicia Docker Desktop o el daemon de Docker${NC}"
    ERRORS=$((ERRORS + 1))
fi

# Verificar contenedores existentes
echo ""
echo "Verificando contenedores existentes:"
if docker-compose ps 2>/dev/null | grep -q "Up"; then
    echo -e "${BLUE}Contenedores activos encontrados:${NC}"
    docker-compose ps
else
    echo -e "${YELLOW}No hay contenedores activos${NC}"
fi

# Resumen
echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
if [ $ERRORS -eq 0 ]; then
    echo -e "${GREEN}✅ Verificación completada sin errores${NC}"
    echo ""
    echo -e "${BLUE}Siguiente paso:${NC}"
    if [ ! -f ".env" ]; then
        echo "  1. Copia el archivo de configuración: cp .env.docker .env"
        echo "  2. Edita .env y configura tus variables"
        echo "  3. Ejecuta: ./docker-start.sh"
    else
        echo "  Ejecuta: ./docker-start.sh"
    fi
else
    echo -e "${RED}❌ Se encontraron $ERRORS error(es)${NC}"
    echo -e "${YELLOW}Por favor corrige los errores antes de continuar${NC}"
fi
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
