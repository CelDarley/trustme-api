
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlansTable extends Migration
{
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->integer('seals_limit')->nullable(); // null = unlimited
            $table->integer('contracts_limit')->nullable(); // null = unlimited
            $table->decimal('monthly_price', 8, 2);
            $table->decimal('six_months_price', 8, 2);
            $table->decimal('yearly_price', 8, 2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('plans');
    }
}
