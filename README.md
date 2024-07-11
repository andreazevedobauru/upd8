## Upd8
Crud Clientes, Representantes e Cidades.
Foi utilizado Laravel para o backend, Vuejs para o frontend e Mysql para banco de dados.

Gostaria de salientar que poderia ter utilizado cache em algumas consultas com Redis, porém como não estava em um ambiente utilizando docker, e também pelo tempo que tinha para desenvolvimento e a não obrigatoriedade, optei por não implementa-lo.

### Obrigatoriedade:
1. Foi gerado a rota GET: /sellers-by-client/{clientId}
2. Foi gerado a rota GET: /sellers-by-city/{cityId}
3. O arquivo DDL da base na raiz do projeto: dump.sql e para auxilio gerei uma imagem das entidades e seus relacionamentos na raiz chamado `estrutura BD UPD8.png`

#### Instalar dependencias:
```
Copiar o .env.example para .env
```

Rodar os comandos:

```shell
php artisan migrate
```

```shell
php artisan db:seed
```

e executar também em caso de o fronend não estar rodando:

```shell
npm run build
```



