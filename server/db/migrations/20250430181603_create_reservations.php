<?php

declare(strict_types=1);

use App\Helpers\PhinxHelper;
use Phinx\Migration\AbstractMigration;

final class CreateReservations extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('reservas');

        $table->addColumn('nome', 'string')
              ->addColumn('unidade_id', 'integer')
              ->addColumn('quantidade_pessoas', 'integer')
              ->addColumn('data', 'date');

        PhinxHelper::setDatetimeColumns($table);

        $table->create();
    }
}
