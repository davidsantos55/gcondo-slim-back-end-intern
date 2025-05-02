<?php

declare(strict_types=1);

use App\Helpers\PhinxHelper;
use Phinx\Migration\AbstractMigration;

final class CreateLocais extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('locais');

        $table->addColumn('nome', 'string')
              ->addColumn('quantidade_maxima_pessoas', 'integer')
              ->addColumn('metros_quadrados', 'integer', ['null' => true]);

        PhinxHelper::setDatetimeColumns($table);

        $table->create();
    }
}
