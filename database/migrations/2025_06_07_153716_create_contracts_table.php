
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContractsTable extends Migration
{
    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('content');
            $table->string('party_a_name');
            $table->string('party_a_email');
            $table->string('party_b_name');
            $table->string('party_b_email');
            $table->enum('status', ['draft', 'pending', 'signed', 'cancelled'])->default('draft');
            $table->timestamp('signed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('contracts');
    }
}
