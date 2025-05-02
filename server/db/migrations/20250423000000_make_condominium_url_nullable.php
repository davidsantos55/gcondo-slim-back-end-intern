<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class MakeCondominiumUrlNullable extends AbstractMigration
{
    
    public function change()
    {
        $this->table('condominiums')
            ->changeColumn('url','string',['null'=>true])
            ->update();
    }
}
