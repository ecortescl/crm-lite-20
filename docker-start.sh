#!/bin/bash

# Script de inicio rápido para Docker
set -e

echo "🐳 Iniciando Laravel CRM con Docker..."
echo ""

# Colores
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Verificar si Docker está instalado
if ! command -v docker &> /dev/null; then
    echo -e "${RED}❌ Docker no está instalado${NC}"
    echo "Por favor instala Docker desde: https://docs.docker.com/get-docker/"
    exit 1
fi

# Verificar si Docker Compose está instalado
if ! command -v docker-compose &> /dev/null; then
    echo -e "${RED}❌ Docker Compose no está instalado${NC}"
    echo "Por favor instala Docker Compose desde: https://docs.docker.com/compose/install/"
    exit 1
fi

# Verificar si existe .env
if [ ! -f .env ]; then
    echo -e "${YELLOW}⚠️  No se encontró archivo .env${NC}"
    echo -e "${BLUE}📝 Copiando .env.docker a .env...${NC}"
    cp .env.docker .env
    
    echo -e "${BLUE}🔑 Generando APP_KEY...${NC}"
    # Generar APP_KEY
    APP_KEY=$(docker run --rm -v $(pwd):/app -w /app php:8.4-cli php -r "echo 'base64:' . base64_encode(random_bytes(32));")
    
    # Reemplazar APP_KEY en .env
    if [[ "$OSTYPE" == "darwin"* ]]; then
        # macOS
        sed -i '' "s|APP_KEY=|APP_KEY=$APP_KEY|g" .env
    else
        # Linux
        sed -i "s|APP_KEY=|APP_KEY=$APP_KEY|g" .env
    fi
    
    echo -e "${GREEN}✅ Archivo .env creado${NC}"
    echo ""
    echo -e "${YELLOW}⚠️  IMPORTANTE: Edita el archivo .env y configura:${NC}"
    echo "   - APP_NAME: Nombre de tu aplicación"
    echo "   - APP_URL: URL de tu aplicación"
    echo "   - DB_PASSWORD: Contraseña segura para PostgreSQL"
    echo ""
    read -p "Presiona Enter cuando hayas configurado el .env..."
fi

# Construir imágenes
echo -e "${BLUE}🔨 Construyendo imágenes Docker...${NC}"
docker-compose build

# Levantar contenedores
echo -e "${BLUE}🚀 Levantando contenedores...${NC}"
docker-compose up -d

# Esperar a que los servicios estén listos
echo -e "${BLUE}⏳ Esperando a que los servicios estén listos...${NC}"
sleep 10

# Verificar estado
echo -e "${BLUE}📊 Estado de los contenedores:${NC}"
docker-compose ps

echo ""
echo -e "${GREEN}✅ ¡Aplicación iniciada correctamente!${NC}"
echo ""
echo -e "${BLUE}📍 Accede a tu aplicación en:${NC}"
echo -e "   ${GREEN}http://localhost${NC}"
echo ""
echo -e "${BLUE}📝 Comandos útiles:${NC}"
echo "   docker-compose logs -f          # Ver logs"
echo "   docker-compose exec app sh      # Acceder al contenedor"
echo "   docker-compose down             # Detener contenedores"
echo "   make help                       # Ver todos los comandos disponibles"
echo ""
echo -e "${YELLOW}💡 Tip: Usa 'make' para comandos rápidos (make help para ver opciones)${NC}"
