# Ticket #456

## ⚙️ Ajuste de ambiente: imagem PHP

Durante a configuração inicial do ambiente Docker, foi identificado que a imagem `php:8.4-fpm` não está disponível no Docker Hub, causando o erro:

```
exec /usr/local/bin/docker-php-entrypoint: exec format error
```

###  Solução
- Substituída imagem `php:8.4-fpm` por `php:8.3-fpm`
- Containers passaram a subir corretamente com:

```bash
docker compose up --build
```

---

## 🛠️ Tarefa 1 – Corrigir obrigatoriedade da URL

### Problema
O campo `url` estava sendo tratado como obrigatório durante a criação de um condomínio, impedindo cadastros sem URL.

### Soluções aplicadas
- No método `validateCondominiumData()` do `CondominiumService`, foi removida a validação:
```php
if (empty($data['url'])) {
    throw new HttpUnprocessableEntityException('URL is required');
}
```

- No método `create()`, o campo `url` foi ajustado:
```php
'url' => $data['url'] ?? null
```

- Criada a migration `20250423000000_make_condominium_url_nullable.php`:
```php
$this->table('condominiums')
     ->changeColumn('url', 'string', ['null' => true])
     ->update();
```

---

##  Tarefa 2 – Tratar erro de URL duplicada

### Problema
A repetição de URL causava erro de banco, expondo detalhes técnicos.

### Solução
No método `create()` de `CondominiumService`, foi adicionada verificação explícita:
```php
if (!empty($data['url']) && Condominium::where('url', $data['url'])->exists()) {
    throw new HttpUnprocessableEntityException('URL já está em uso.');
}
```

---

##  Tarefa 3 – Criar sistema de Reservas

### Objetivo
Permitir que os usuários criem e gerenciem reservas para salões de festa, associadas a uma unidade.

### Etapas

#### 3.1 Migration `reservas`
Criada migration `CreateReservations`:
```php
$table = $this->table('reservas');
$table->addColumn('nome', 'string')
      ->addColumn('unidade_id', 'integer')
      ->addColumn('quantidade_pessoas', 'integer')
      ->addColumn('data', 'date');
\App\Helpers\PhinxHelper::setDatetimeColumns($table);
$table->create();
```
Rodada com:
```bash
composer run phinx:migrate
```

#### 3.2 Model
Criado `src/Models/Reservation.php` com fillable:

```php
protected $fillable = ['nome', 'unidade_id', 'quantidade_pessoas', 'data'];
```

#### 3.3 Controller
Criado `ReservationController.php` com métodos:
- `store`
- `index`
- `find`
- `update`
- `delete`

#### 3.4 Rotas
Adicionadas rotas em `routes.php`:

```php
$app->group('/reservas', function (RouteCollectorProxy $group) {
    $group->get('', [ReservationController::class, 'index']);
    $group->get('/{id}', [ReservationController::class, 'find']);
    $group->post('', [ReservationController::class, 'store']);
    $group->put('/{id}', [ReservationController::class, 'update']);
    $group->delete('/{id}', [ReservationController::class, 'delete']);
});
```

#### 3.5 Testes no Insomnia
- `POST /reservas`
- `GET /reservas`
- `GET /reservas/{id}`
- `PUT /reservas/{id}`
- `DELETE /reservas/{id}`

---

##  Funcionalidade extra: Locais (salões)

### Objetivo
Permitir criaçôes de salões de festas que podem ser relacionadas ao locais.

### 4.1 Migration `locais`
Criada migration com os campos:
- `nome` (obrigatório)
- `quantidade_maxima_pessoas` (obrigatório)
- `metros_quadrados` (opcional)
- timestamps

### 4.2 Model
Criado `Local.php` com:
```php
protected $fillable = ['nome', 'quantidade_maxima_pessoas', 'metros_quadrados'];
```

### 4.3 Controller
Criado `LocalController.php` com métodos:
- `index`
- `store`
- `show`
- `update`
- `delete`

### 4.4 Rotas
Registradas rotas em `/locais` no `routes.php`.

---

##  Integração entre Reservas e Locais

### Alterações no banco
Criada migration `AddLocalIdToReservas`:
```php
$table = $this->table('reservas');
$table->addColumn('local_id', 'integer', ['null' => true]);
$table->update();
```

### Atualizações no model `Reservation`
```php
public function local()
{
    return $this->belongsTo(Local::class, 'local_id');
}
```

### Atualização no `index()` do `ReservationController`
```php
$reservas = Reservation::with('local')->get();
```

### Testes no Insomnia
- `POST /reservas` com `local_id`
- `GET /reservas` exibe o local junto
- `PUT` e `DELETE` testados com sucesso

---

 **Todas as tarefas foram concluídas com sucesso. O sistema agora permite reservas com locais associados, validação de URL e ambiente estável com Docker.**