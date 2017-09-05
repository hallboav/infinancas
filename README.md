## In Finanças Console Tool

Você pode usar para rápida verificação do saldo.

### Instalação

```shell
php -r "copy('https://github.com/hallboav/infinancas/releases/download/v1.0/infinancas.phar', 'infinancas.phar');"
php -r "if (hash_file('SHA384', 'infinancas.phar') !== 'ac3a7ef534552e55577771496cf69dca24935f810bdc2a17d2eb44d04fcf7ebd27c9c4a08b8f8621a20f84a9e722a6e3') { unlink('infinancas.phar'); echo 'Invalid PHAR', PHP_EOL; } else { echo 'Verified', PHP_EOL; }"
chmod u+x infinancas.phar
sudo mv infinancas.phar /usr/local/bin
infinancas.phar --version
```

### Como usar

#### Comando balance (padrão)

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

#### Comando transactions

Você também pode usá-lo para ver suas transações. Basta informar o ID da sua sessão no servidor e o número do cartão desejado.

```shell
infinancas.phar transactions qj2k3mjjynrmz4x1dp1axnxn "4712 2282 0001 2464"
```

Para obter seu *session-id* você deve executar o comando *balance* em modo verboso.

### Criando PHAR

É necessário modificar seu `php.ini` para a seguinte configuração:

```shell
phar.readonly = Off
```

Então basta chamar:
```shell
bin/compile
```

Ou chamar o PHP da seguinte forma:

```shell
php -dphar.readonly=0 bin/compile
```
