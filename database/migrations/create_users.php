<?php

use Forge\core\Migration;
use Forge\core\Schemas\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user', function ($table) {
            $table->id();
            $table->string('name', 255);
            $table->string('email', 255);
            $table->string('password', 255);
        });
    }

    public function down()
    {
        $this->dropTable('user');
    }
};
