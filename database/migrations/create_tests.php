<?php

use Forge\core\Migration;
use Forge\core\Schemas\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('test', function ($table) {
            $table->id();
            $table->string('name', 255);
            $table->integer('age');
            $table->text('bio');
        });
    }

    public function down()
    {
        $this->dropTable('test');
    }
};
