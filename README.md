

### In Finanças Console Tool

```shell
git clone git@github.com:hallboav/infinancas.git /tmp
composer install
php -dphar.readonly=0 bin/compile && mv bin/infinancas.phar /usr/local/bin/infinancas
```



Criação do PHAR:

```shell
php -dphar.readonly=0 bin/compile
```

##1 asdfas


git@github.com:hallboav/infinancas.git



Você pode definir usuário e senha no seu ambiente:

```shell
export IN_FINANCAS_USERNAME=111.111.111-11
export IN_FINANCAS_PASSWORD=foo

```

Você também pode passá-los por argumentos

```shell
infinancas.phar 222.222.222-22 bar
```

O usuário e senha passados por argumentos têm prioridade sobre as variáveis de ambiente.



php -r "readfile('http://github.com:hallboav/infinancas.git/bininfinancas.phar');" > symfony
