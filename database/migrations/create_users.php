<?php

use Forge\core\Migration;
use Forge\core\Schemas\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function ($table) {
            $table->id()->auto_increment()->primary();
            $table->string('name', 255)->nullable();
            $table->string('email', 255);
            $table->integer('age')->nullable();
            $table->string('role')->default('user');
            $table->string('password', 255);
            $table->timestamps();
        });
    }

    public function down()
    {
        $this->dropTable('user');
    }
};
