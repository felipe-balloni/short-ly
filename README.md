# Projeto API Short-ly em Laravel

## Sobre o Projeto
Este projeto é uma API para encurtar links, onde o usuário poderá criar um link encurtado e compartilhar com outras pessoas, assim como acompanhar as visitas e origem das visitas.

O projeto é desenvolvido em Laravel 10 e usa Sanctum para autenticação e autorização, assim como Tenancy para multi-tenant.

## Requisitos do Projeto
Este projeto usa Laravel Sail para fornecer uma experiência de desenvolvimento local fácil e sem complicações em qualquer sistema operacional. O Laravel Sail é uma interface de linha de comando leve para interagir com os contêineres Docker do Laravel.

Nossa aplicação Laravel está executando no Docker junto com todos os seus serviços, incluindo MariaDB.

### Pré-requisitos:
1. Docker
2. Docker Compose
3. PHP 8.1
4. Composer

## Rodando o Projeto com Laravel Sail
   
Para rodar o projeto localmente usando Laravel Sail, siga os passos abaixo:

Obs: a opção de usar o Docker é opcional, caso prefira, pode instalar o PHP 8.1 e MariaDB localmente e ajustar o arquivo .env para usar o banco de dados local.

### Etapa 1: Clone o Repositório e entre no Diretório do Projeto
Primeiro, você precisa clonar o repositório a partir do GitHub:

```
git clone https://github.com/felipe-balloni/short-ly.git
```

Após clonar o projeto, navegue para o diretório do projeto:

```
cd short-ly
```

### Etapa 2: Instale as Dependências do PHP
Instale todas as dependências do PHP com o Composer:
```
composer install
```
Obs: caso não tenha o composer instalado, siga as instruções de instalação no link: https://getcomposer.org/download/

Opção: Instale as dependências do PHP com o Docker:
```
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/opt \
    -w /opt \
    laravelsail/php81-composer:latest \
    composer install --ignore-platform-reqs
```
### Etapa 3: Copie o Arquivo .env e ajuste as configurações do ambiente

```
cp .env.example .env
```

Gere a Chave de Criptografia

```
php artisan key:generate
```

Verifique no arquivo .env se as configurações do banco de dados estão corretas:
```dotenv
DB_CONNECTION=mysql
DB_HOST=mariadb
DB_PORT=3306
DB_DATABASE=shortly
DB_USERNAME=sail
DB_PASSWORD=password
```

Obs: caso já possui uma instalação do MySQL ou MariaD que usa a porta 3306, altere a porta no arquivo .env usando

```dotenv
FORWARD_DB_PORT=3307 #ou qualquer outra porta disponível
```

O mesmo poderá ocorrer para aplicação que irá ser executada na porta 80, caso já possua uma aplicação rodando na porta 80, altere a porta no arquivo .env usando

```dotenv
ABLY_KEY=8000 #ou qualquer outra porta disponível
```
IMPORTANTE: essa porta deverá ser ajusta na aplicação frontend que irá consumir a API assim como o APP_URL que deverá ser http://localhost:8000 (no caso de usar a porta 8000)

### Etapa 4: Inicie o Laravel Sail
Agora, você pode usar o Laravel Sail para iniciar a aplicação. Este comando criará e iniciará os contêineres do Docker:

```
./vendor/bin/sail up -d
```

Obs: a opção -d irá executar o docker em background, caso queira ver os logs, execute sem a opção -d

### Etapa 5: Execute as Migrações e os Seeders
Execute as migrações do banco de dados para criar a estrutura necessária:

```
./vendor/bin/sail artisan migrate --seed
```

Agora você deve ser capaz de acessar o projeto Laravel em seu navegador na porta especificada (APP_PORT), que no exemplo neste guia, é http://localhost:8000.

O seed irá criar 11 usuários com senha padrão "password", de 10 a 20 links por usuário e visitas aleatórias para cada link, sendo, dez usuários terão os e-mails aleatórios, o último será test@exemple.com, que será usado para testar o projeto. Pode usar qualquer um dos usuários criados para testar o projeto, ou criar um novo usuário.

Obs: caso prefira, não popular o banco com dados de exemplo remova o --seed do comando acima.

### Etapa 6: Acesse a API
Acesse a API em seu navegador na porta especificada (APP_PORT), que no exemplo neste guia, é http://localhost:8000/api

As APIs do projeto são:

* **POST /api/sanctum/token**: para gerar o token de autenticação, o retorno deste endpoint será o token que deverá token plain text que deverá ser usado no header das requisições para as demais APIs

```shell
POST /api/sanctum/token

curl --request POST \
  --url http://localhost:8000/api/sanctum/token \
  --header 'Accept: application/json' \
  --header 'Content-Type: application/json' \
  --data '{
	"email": "test@example.com",
	"password": "password"
}'
```

Exemplo de retorno: 4|9KG12qbw5jXsPOvwmV2D8UeaqQoWvWi9WiVHS257

* **GET /api/short-ly**: para listar todos os links encurtados

```shell
GET /api/short-ly

curl --request GET \
  --url 'http://localhost:8000/api/short-url?per_page=10&sort_by=visits_count&sort_direction=desc&search=' \
  --header 'Accept: application/json' \
  --header 'Authorization: Bearer 4|9KG12qbw5jXsPOvwmV2D8UeaqQoWvWi9WiVHS257'
```

O retorno será um Json com listagem paginada dos links encurtados, exemplo:

```json
{
    "data": [
        {
            "id": 136,
            "destination_url": "https:\/\/www.yahoo.com\/",
            "short_url": "http:\/\/localhost:8000\/short\/mJkoz6",
            "url_key": "mJkoz6",
            "visits_count": 30,
            "referer_url_count": 11
        },
        ...
        {
            "id": 148,
            "destination_url": "https:\/\/translate.google.com\/?sl=en&tl=pt&op=translate",
            "short_url": "http:\/\/localhost:8000\/short\/translate",
            "url_key": "translate",
            "visits_count": 1,
            "referer_url_count": 1
        }
    ],
    "links": {
        "first": "http:\/\/localhost:8000\/api\/short-url?page=1",
        "last": "http:\/\/localhost:8000\/api\/short-url?page=2",
        "prev": null,
        "next": "http:\/\/localhost:8000\/api\/short-url?page=2"
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 2,
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "http:\/\/localhost:8000\/api\/short-url?page=1",
                "label": "1",
                "active": true
            },
            {
                "url": "http:\/\/localhost:8000\/api\/short-url?page=2",
                "label": "2",
                "active": false
            },
            {
                "url": "http:\/\/localhost:8000\/api\/short-url?page=2",
                "label": "Next &raquo;",
                "active": false
            }
        ],
        "path": "http:\/\/localhost:8000\/api\/short-url",
        "per_page": 10,
        "to": 10,
        "total": 13
    }
}
```

* **GET /api/short-ly/{id}**: para mostrar os dados de apenas um link encurtado, onde {id} é o id do link encurtado

```shell
GET /api/short-ly/{id}
curl --request GET \
  --url http://localhost:8000/api/short-url/139 \
  --header 'Accept: application/json' \
  --header 'Authorization: Bearer 4|9KG12qbw5jXsPOvwmV2D8UeaqQoWvWi9WiVHS257'
```

O retorno será Json similar ao abaixo:

```json
{
	"data": {
		"id": 139,
		"destination_url": "https:\/\/www.google.com\/",
		"short_url": "http:\/\/localhost:8000\/short\/szGq2b",
		"url_key": "szGq2b",
		"visits_count": 20,
		"referer_url_count": 5
	}
}
```

* **POST /api/short-ly**: para criar um novo link encurtado, o retorno deste endpoint será o link encurtado criado

``` shell
POST /api/short-ly

curl --request POST \
  --url http://localhost:8000/api/short-url \
  --header 'Accept: application/json' \
  --header 'Authorization: Bearer 4|9KG12qbw5jXsPOvwmV2D8UeaqQoWvWi9WiVHS257' \
  --header 'Content-Type: application/json' \
  --data '{
	"destination_url": "https://google.com",
	"url_key": "tes12te123"
}'
```

OS parâmetros são:

    "destination_url": endereço que será encurtado, exemplo: "https://google.com", obrigatório.
    "url_key": chave que será usada para encurtar o link, exemplo: "tes12te123", opcional, caso não seja informado, será gerado um hash aleatório.

O retorno será Json com os dados do link criado

```json
{
    "data": {
        "id": 149,
        "destination_url": "https:\/\/google.com",
        "short_url": "http:\/\/localhost:8000\/short\/tes12te123",
        "url_key": "tes12te123",
        "visits_count": 0,
        "referer_url_count": 0
    }
}
```

Caso de problema de validação dos campos iramos receber erro 422 com os dados dos campos que não passaram na validação, exemplo:

```json
{
    "message": "The given data was invalid.",
    "errors": {
        "destination_url": [
            "The destination url field is required."
        ]
    }
}
```

* **PUT /api/short-ly/{id}**: para atualizar um link encurtado, onde {id} é o id do link encurtado

```shell
PUT /api/short-ly/{id}

curl --request PUT \
--url http://localhost:8000/api/short-url/144 \
--header 'Accept: application/json' \
--header 'Authorization: Bearer 4|9KG12qbw5jXsPOvwmV2D8UeaqQoWvWi9WiVHS257' \
--header 'Content-Type: application/json' \
--data '{
"destination_url": "https://tailwindcss.com/docs/",
"url_key": "asdfa12"
}'
```

OS parâmetros são:

    "destination_url": endereço que será alterado, exemplo: "https://google.com", obrigatório.
    "url_key": chave que será usada alterar o link encurtado, exemplo: "asdfa12", opcional, caso não seja informado ou em branco, será usando o mesmo existente.

* **DELETE /api/short-ly/{id}**: para deletar um link encurtado, onde {id} é o id do link encurtado

```shell
DELETE /api/short-ly/{id}

curl --request DELETE \
  --url http://localhost:8000/api/short-url/147 \
  --header 'Accept: application/json' \
  --header 'Authorization: Bearer 4|9KG12qbw5jXsPOvwmV2D8UeaqQoWvWi9WiVHS257'
```

Esse endpoint retorna apenas 204 No Content.

* **GET /api/short-ly/{id}/visits**: para listar as visitas de um link encurtado, onde {id} é o id do link encurtado

```shell
GET /api/short-ly/{id}/visits

curl --request GET \
  --url http://localhost:8000/api/short-url/146/visits \
  --header 'Accept: application/json' \
  --header 'Authorization: Bearer 4|9KG12qbw5jXsPOvwmV2D8UeaqQoWvWi9WiVHS257'
```

* **GET api/statistic**: para listar as estatísticas de todos os links encurtados

```shell
GET api/statistic

curl --request GET \
  --url http://localhost:8000/api/statistic \
  --header 'Accept: application/json' \
  --header 'Authorization: Bearer 4|9KG12qbw5jXsPOvwmV2D8UeaqQoWvWi9WiVHS257'
```

O retorno é Json, exemplo abaixo:

```json
{
	"data": {
		"total": 14,
		"total_visits": 150,
		"total_referer_url": 44
	}
}
```

## Acessar o sistema

Para verificar se o sistema está funcionando corretamente, acesse o endereço http://localhost:8000, que pode ser um backend que fornecerá a API para um frontend ou um frontend que irá consumir a API iremos ver apenas
uma página com o json abaixo:

```json
{
    "Laravel": "10.19.0"
}
```

A partir deste ponto o projeto está pronto para ser usado e portanto continue pelo frontend: https://github.com/felipe-balloni/vue-short-ly

## Agradecimentos

Agradeço a oportunidade de participar do processo seletivo, foi um desafio muito interessante e aprendi muito com ele.

Atenciosamente,

#### Felipe Balloni Ferreira



