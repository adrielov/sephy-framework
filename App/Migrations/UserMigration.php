<?php
namespace App\Migrations;

use App\Helpers\Utils;
use Core\Lib\DB;
use Core\Migration;

class UserMigration extends Migration {

    protected $tableName;

    public function __construct(){
        parent::__construct();

        $this->tableName = 'users';
    }

    /**
     * @return \Illuminate\Database\Schema\Blueprint
     */
    public function up(){

		DB::schema()->dropIfExists($this->tableName);

        return DB::schema()->create($this->tableName, function ($table) {

            $table->increments('id');
			$table->enum('priority', ['user', 'admin'])->default('user');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
			$table->enum('gender', ['male', 'female']);
            $table->string('password');
            $table->string('activation_code')->default(Utils::generateToken());
            $table->tinyInteger('is_activated');
			$table->timestamps();

			$table->index(['id', 'email']);
        });
    }

    /**
     * @return \Illuminate\Database\Schema\Blueprint
     */
    public function down() {
        return $this->schema->drop($this->tableName);
    }
}