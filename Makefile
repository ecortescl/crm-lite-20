.PHONY: help build up down restart logs shell artisan migrate seed fresh install clean backup restore

help: ## Mostrar esta ayuda
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}'

build: ## Construir las imágenes Docker
	docker-compose build

up: ## Levantar los contenedores
	docker-compose up -d

down: ## Detener los contenedores
	docker-compose down

restart: ## Reiniciar los contenedores
	docker-compose restart

logs: ## Ver logs de todos los servicios
	docker-compose logs -f

logs-app: ## Ver logs de la aplicación
	docker-compose logs -f app

logs-db: ## Ver logs de PostgreSQL
	docker-compose logs -f db

shell: ## Acceder al shell del contenedor
	docker-compose exec app sh

artisan: ## Ejecutar comando artisan (uso: make artisan CMD="migrate")
	docker-compose exec app php artisan $(CMD)

migrate: ## Ejecutar migraciones
	docker-compose exec app php artisan migrate --force

migrate-fresh: ## Recrear base de datos y ejecutar migraciones
	docker-compose exec app php artisan migrate:fresh --force

seed: ## Ejecutar seeders
	docker-compose exec app php artisan db:seed --force

fresh: ## Recrear DB, migrar y seedear
	docker-compose exec app php artisan migrate:fresh --seed --force

cache-clear: ## Limpiar todos los caches
	docker-compose exec app php artisan cache:clear
	docker-compose exec app php artisan config:clear
	docker-compose exec app php artisan route:clear
	docker-compose exec app php artisan view:clear

cache-optimize: ## Optimizar caches
	docker-compose exec app php artisan config:cache
	docker-compose exec app php artisan route:cache
	docker-compose exec app php artisan view:cache

install: ## Instalación completa (build + up + migrate)
	make build
	make up
	sleep 10
	make migrate
	@echo "✅ Instalación completa. Accede a http://localhost"

clean: ## Limpiar contenedores y volúmenes (⚠️ ELIMINA LA BASE DE DATOS)
	docker-compose down -v
	docker system prune -f

backup-db: ## Backup de la base de datos
	docker-compose exec -T db pg_dump -U laravel laravel > backup-$(shell date +%Y%m%d-%H%M%S).sql
	@echo "✅ Backup creado: backup-$(shell date +%Y%m%d-%H%M%S).sql"

restore-db: ## Restaurar base de datos (uso: make restore-db FILE=backup.sql)
	docker-compose exec -T db psql -U laravel laravel < $(FILE)
	@echo "✅ Base de datos restaurada desde $(FILE)"

backup-storage: ## Backup de archivos subidos
	docker run --rm -v crm-lite-20_storage-data:/data -v $(PWD):/backup alpine tar czf /backup/storage-backup-$(shell date +%Y%m%d-%H%M%S).tar.gz -C /data .
	@echo "✅ Backup de storage creado"

restore-storage: ## Restaurar archivos (uso: make restore-storage FILE=storage-backup.tar.gz)
	docker run --rm -v crm-lite-20_storage-data:/data -v $(PWD):/backup alpine tar xzf /backup/$(FILE) -C /data
	@echo "✅ Storage restaurado desde $(FILE)"

ps: ## Ver estado de los contenedores
	docker-compose ps

stats: ## Ver estadísticas de recursos
	docker stats

test: ## Ejecutar tests
	docker-compose exec app php artisan test

tinker: ## Abrir Laravel Tinker
	docker-compose exec app php artisan tinker
