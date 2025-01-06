#!/bin/bash

# Variáveis
APP_DIR=$(pwd)
DB_NAME="system"
DB_USER="YOU-USER-DB"
DB_PASS="YOU-PASS-DB"

# Verificar permissões
if [ "$EUID" -ne 0 ]; then
    echo "Por favor, execute como root (use sudo)."
    exit
fi

# Atualização do sistema
echo "Atualizando o sistema..."
apt update && apt upgrade -y || echo "Erro ao atualizar o sistema, continuando..."

# Configuração do MySQL
echo "Configurando o MySQL..."
mysql -e "CREATE DATABASE IF NOT EXISTS $DB_NAME;" || echo "Erro ao criar o banco de dados, continuando..."
mysql -e "CREATE USER IF NOT EXISTS '$DB_USER'@'localhost' IDENTIFIED BY '$DB_PASS';" || echo "Erro ao criar o usuário do banco de dados, continuando..."
mysql -e "GRANT ALL PRIVILEGES ON $DB_NAME.* TO '$DB_USER'@'localhost';" || echo "Erro ao conceder privilégios ao usuário, continuando..."
mysql -e "FLUSH PRIVILEGES;" || echo "Erro ao aplicar privilégios, continuando..."

# Instalar dependências do Laravel
echo "Instalando dependências do Laravel..."
composer install || echo "Erro ao instalar dependências do Laravel, continuando..."

# Instalar dependências do frontend (npm)
echo "Instalando dependências do frontend..."
npm install || echo "Erro ao instalar dependências do frontend, continuando..."

# Executar build do frontend
echo "Executando build do frontend..."
npm run build || echo "Erro ao executar build do frontend, continuando..."

# Configuração do arquivo .env
if [ ! -f .env ]; then
    echo "Criando o arquivo .env..."
    cp .env.example .env || echo "Erro ao copiar o arquivo .env.example, continuando..."
    sed -i "s/DB_DATABASE=laravel/DB_DATABASE=$DB_NAME/" .env || echo "Erro ao configurar DB_DATABASE, continuando..."
    sed -i "s/DB_USERNAME=root/DB_USERNAME=$DB_USER/" .env || echo "Erro ao configurar DB_USERNAME, continuando..."
    sed -i "s/DB_PASSWORD=/DB_PASSWORD=$DB_PASS/" .env || echo "Erro ao configurar DB_PASSWORD, continuando..."
    echo "QUEUE_CONNECTION=rabbitmq" >> .env || echo "Erro ao adicionar QUEUE_CONNECTION, continuando..."
else
    echo "Arquivo .env já existe. Nenhuma modificação foi feita."
fi

# Gerar chave do aplicativo
echo "Gerando chave do aplicativo..."
php artisan key:generate || echo "Erro ao gerar a chave do aplicativo, continuando..."

# Executar migrações
echo "Executando migrações..."
php artisan migrate || echo "Erro ao executar migrações, continuando..."

# Instalar Pest
echo "Instalando Pest..."
php artisan pest:install || echo "Erro ao instalar Pest, continuando..."

# Configurar permissões
echo "Configurando permissões..."
chown -R www-data:www-data $APP_DIR || echo "Erro ao configurar proprietário, continuando..."
chmod -R 775 $APP_DIR/storage $APP_DIR/bootstrap/cache || echo "Erro ao configurar permissões, continuando..."

# Configurar o Apache
echo "Configurando o Apache..."
VHOST="<VirtualHost *:80>
    ServerAdmin admin@localhost
    DocumentRoot /var/www/system/public
    ServerName provisionamento-api.local

    <Directory /var/www/system/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog \${APACHE_LOG_DIR}/error.log
    CustomLog \${APACHE_LOG_DIR}/access.log combined
</VirtualHost>"
echo "$VHOST" > /etc/apache2/sites-available/system.conf || echo "Erro ao criar o arquivo de configuração do Apache, continuando..."

a2ensite system || echo "Erro ao habilitar o site no Apache, continuando..."
systemctl restart apache2 || echo "Erro ao reiniciar o Apache, continuando..."

# Finalizando
echo "Configuração concluída! Configure o arquivo /etc/hosts para acessar http://provisionamento-api.local"
