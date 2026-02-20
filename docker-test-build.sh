#!/bin/bash

# Script para probar el build de Docker sin levantar los contenedores
set -e

echo "🧪 Probando build de Docker..."
echo ""

# Colores
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m'

# Verificar que Docker esté corriendo
if ! docker info &> /dev/null; then
    echo -e "${RED}❌ Docker no está corriendo${NC}"
    exit 1
fi

echo -e "${BLUE}🔨 Construyendo imagen Docker...${NC}"
echo ""

# Construir solo la imagen, sin levantar contenedores
if docker-compose build --no-cache app; then
    echo ""
    echo -e "${GREEN}✅ Build completado exitosamente${NC}"
    echo ""
    
    # Mostrar información de la imagen
    echo -e "${BLUE}📦 Información de la imagen:${NC}"
    docker images | grep crm-lite-20 | head -1
    
    echo ""
    echo -e "${GREEN}✨ La imagen está lista para usar${NC}"
    echo ""
    echo -e "${BLUE}Siguiente paso:${NC}"
    echo "  docker-compose up -d    # Levantar contenedores"
    echo "  make install            # O usar make para instalación completa"
else
    echo ""
    echo -e "${RED}❌ Build falló${NC}"
    echo ""
    echo -e "${YELLOW}Revisa los errores arriba y verifica:${NC}"
    echo "  1. Que todos los archivos de configuración existan"
    echo "  2. Que .env.docker esté presente"
    echo "  3. Que las dependencias en package.json sean correctas"
    echo ""
    exit 1
fi
