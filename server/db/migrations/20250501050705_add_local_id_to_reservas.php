<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddLocalIdToReservas extends AbstractMigration
{
   
    public function change(): void
    {
        $table = $this->table('reservas');
        $table->addColumn('local_id', 'integer', ['null' => true]);
        $table->update();
    }
}
