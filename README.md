![Imagem](https://github.com/lfboaventura/spa-com-vue-js-laravel8/blob/master/imagem.PNG)


## SPA com VueJS e API em Laravel.

### Sobre

Aplicação para estudos, construção de uma mini-rede social, baseada no curso [SPA com Vue JS e API com Laravel](https://www.udemy.com/course/spa-com-vue-js/) ministrado por [Guilherme Ferreira](https://www.udemy.com/user/guilherme-ferreira-4/).


Projeto utiliza duas aplicações:

1. Frontend em VueJS localizada na pasta **social**, sendo necessário ter instalados [Node.js](https://nodejs.org/en/) e o [Vue-cli](https://cli.vuejs.org/);
2. Backend em [PHP](https://www.php.net/manual/pt_BR/index.php) localizada na pasta **webservice**, sendo necessário ter instalados [Composer](https://getcomposer.org/) e framework [Laravel](https://laravel.com/docs/8.x/readme).



**Tecnologias, Dependências e Versões:**
* [Laravel 8.0](https://laravel.com/docs/8.x/readme).
* [VueJS 2.5.2](https://br.vuejs.org/v2/guide/index.html)
* [Vue-router 3.0.1](https://router.vuejs.org/)
* [Vuex 3.6.2](https://vuex.vuejs.org/ptbr/)


Projeto segue [licença MIT](https://opensource.org/licenses/MIT) em disponibilização pública.



### Instalação e utilização

**1. Instalação - Backend**


_Na pasta "webservice":_

* Instalar dependências:
> composer install

* Arquivo .env e parametrizar para conexão com o [banco de dados](https://www.oracle.com/br/database/what-is-database/#:~:text=Um%20banco%20de%20dados%20%C3%A9,banco%20de%20dados%20(DBMS).)
> php -r \"file_exists('.env') || copy('.env.example', '.env');\"

* Rodar comando [artisan](https://laravel.com/docs/8.x/artisan#introduction)para geração de uma nova key.
> php artisan key:generate --ansi

* Rodar [migrations](https://laravel.com/docs/8.x/migrations#introduction) e criação de tabelas no banco de dados.
> php artisan migrate

* Rodar o server.
> php artisan serve



**2. Instalação - Frontend**

_Na pasta "social":_

* Instalar dependências:
> npm install

* Instalar dependências:
> npm start


**Status:** Finalizado.
