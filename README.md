Descrição do Sistema
Este sistema foi desenvolvido como um backend para realizar as seguintes operações:

Gerenciamento de Clientes: Criação de novos clientes e alteração de senhas.
Gerenciamento de Domínios e Bancos de Dados: Criação de domínios e bancos de dados com cotas de uso.
Envio Assíncrono de E-mails: O sistema envia e-mails de forma assíncrona para todas as operações realizadas.

## Requisitos

- **Ubuntu Server 24**
- **Laravel 9.***
- **PHP 8.***
- **dos2unix**
- **RabbitMQ**
   
## Comandos para Instalar o Sistema

1. Instale a dependência `dos2unix`:
   sudo apt install dos2unix

2. Clone o repositório do sistema:
    git clone https://github.com/higinofe/system

3. Acesse o diretório do sistema:
    cd system

4. Converta o arquivo setup.sh para o formato Unix:
    sudo dos2unix setup.sh

5. Alterar arquivo de setup:
    nano setup.sh
    Alterar DB_USER e DB_PASS do arquivo setup, configuracao referente ao banco de dados local.

7. Converta o arquivo setup.sh para o formato Unix:
    chmod +x setup.sh

8. Execute o script para finalizar a instalação e configuração:
    ./setup.sh
