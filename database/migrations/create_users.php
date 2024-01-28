<?php 

namespace Forge\database\migrations;

use Forge\core\Migration;

class User extends Migration
{
 public function up()
 {
 $this->createTable('user', [
'name' => 'id',
'type' => 'int',
'length' => '11',
'auto_increment' => '1',
'primary_key' => '1',
]);
 }

 public function down()
 {
 $this->dropTable('user');
 }
}