<?php

use Forge\core\Migration;
use Forge\core\Schemas\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('remember_tokens', function ($table) {
            $table->id()->auto_increment()->primary();
            $table->integer('user_id');
            $table->string('token', 255);
            $table->datetime('expires_at');
            $table->timestamps();
        });
    }

    public function down()
    {
        $this->dropTable('user');
    }
};
