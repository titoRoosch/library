# Sistema de Gerenciamento de Biblioteca

Este é um sistema de gerenciamento de biblioteca construído com Laravel.

## Requisitos

- Docker
- Docker-compose

## Instruções de Configuração

1. Clone este repositório em sua máquina local.
2. Copie o arquivo `.env.example` para `.env`:
    ```
    cp .env.example .env
    ```
3. Construa os contêineres Docker:
    ```
    docker-compose build
    ```
4. Crie uma rede Docker para o aplicativo:
    ```
    docker network create library_laravel_app_network
    ```
5. Inicie os contêineres Docker:
    ```
    docker-compose up -d
    ```
6. Acesse o contêiner da aplicação:
    ```
    docker-compose exec web bash
    ```
7. Instale as dependências do Composer:
    ```
    composer install
    ```
8. Gere a chave de aplicação:
    ```
    php artisan key:generate
    ```
9. Defina as permissões de armazenamento:
    ```
    chmod -R 775 storage/logs
    chown -R www-data:www-data storage/logs

    chmod -R 775 storage/framework/sessions
    chown -R www-data:www-data storage/framework/sessions

    chmod -R 775 storage/framework/views
    chown -R www-data:www-data storage/framework/views
    ```
10. Limpe a configuração e o cache do Laravel:
    ```
    php artisan config:clear
    php artisan cache:clear
    ```
11. Saia do contêiner:
    ```
    exit
    ```
12. Reinicie os contêineres Docker:
    ```
    docker-compose restart
    ```
13. Acesse novamente o contêiner da aplicação:
    ```
    docker-compose exec web bash
    ```
14. Execute as migrações do banco de dados:
    ```
    php artisan migrate
    ```
15. Popule o banco de dados com dados de super usuário:
    ```
    php artisan db:seed --class=UsersTableSeeder
    ```

## Configuração de E-mail

Para configurar o envio de e-mails, você precisa adicionar as informações de configuração do e-mail no arquivo `.env`. Abaixo estão as variáveis de ambiente relacionadas ao e-mail que você pode configurar:

MAIL_MAILER=smtp
MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USERNAME=user@example.com
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=from@example.com
MAIL_FROM_NAME="${APP_NAME}"

Certifique-se de substituir os valores de exemplo pelos detalhes da sua configuração de e-mail.

## Executando Trabalhos de Fila

Para processar eventos de fila em segundo plano, você precisa executar o worker de fila do Laravel. Você pode fazer isso executando o comando `php artisan queue:work`. Certifique-se de que o worker de fila esteja em execução para que os eventos na fila sejam processados adequadamente.

    
    php artisan queue:work
    
## Executando Testes Unitários

Para executar os testes unitários, execute o seguinte comando:

    
    docker-compose run --rm web vendor/bin/phpunit
    

Isso iniciará os contêineres Docker necessários e executará os testes unitários.
