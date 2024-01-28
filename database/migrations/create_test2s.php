<?php

use Forge\core\Migration;
use Forge\core\Schemas\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('test2s', function ($table) {
            $table->id();
        });
    }

    public function down()
    {
        $this->dropTable('test2s');
    }
};
