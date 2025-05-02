<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddTimestampsToLocais extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('locais');
        $table->addTimestamps()->update();
    }
}
