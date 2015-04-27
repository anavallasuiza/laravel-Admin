<?php

namespace Admin\Library\Database;

use Schema as LSchema;
use Illuminate\Database\Migrations\Migration as LMigration;

class Migration extends LMigration
{
    private static $tables = [];

    public function toSql()
    {
        $connection = LSchema::getConnection();
        $grammar = $connection->getSchemaGrammar();

        $sql = '';

        foreach (Schema::getTables() as $table) {
            $sql .= "\n".implode("\n", array_map(function ($row) {
                return $row.';';
            }, $table->toSql($connection, $grammar)));
        }

        return trim($sql);
    }
}
