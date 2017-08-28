## In Finanças Console Tool

Você pode usar para rápida verificação do saldo.

### Instalação

```shell
php -r "copy('https://github.com/hallboav/infinancas/releases/download/v1.0/infinancas.phar', 'infinancas.phar');"
php -r "if (hash_file('SHA384', 'infinancas.phar') !== '9eb24dc1ee69c98e3837e0eec2acb3e3970456df98fce6f843e40a724eb8b7088027b6d445444bd27a355d30282669ee') { unlink('infinancas.phar'); echo 'Invalid PHAR', PHP_EOL; } else { echo 'Verified', PHP_EOL; }"
chmod u+x infinancas.phar
sudo mv infinancas.phar /usr/local/bin
infinancas.phar --version
```

### Como usar

Os argumentos são username e password (nesta ordem).

```shell
infinancas.phar 111.111.111-11 foo
```

Você também pode definir usuário e senha no seu ambiente:

```shell
export IN_FINANCAS_USERNAME=222.222.222-22
export IN_FINANCAS_PASSWORD=bar

```

Então você pode chamar o PHAR sem passar as credenciais:

```shell
infinancas.phar
```

Credenciais recebidas por argumentos têm prioridade sobre as que foram recebidas por variáveis de ambiente.

### Criando PHAR

É necessário modificar seu `php.ini` para a seguinte configuração:

```shell
phar.readonly = On
```

Então basta chamar:
```shell
bin/compile
```

Ou chamar o PHP da seguinte forma:

```shell
php -dphar.readonly=0 bin/compile
```

