
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSealsTable extends Migration
{
    public function up()
    {
        Schema::create('seals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('document_type'); // CPF, RG, CNH, etc.
            $table->string('document_number');
            $table->enum('verification_status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->text('verification_notes')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('seals');
    }
}
