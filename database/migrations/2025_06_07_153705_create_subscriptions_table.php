
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionsTable extends Migration
{
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('plan_id')->constrained()->onDelete('cascade');
            $table->enum('billing_cycle', ['monthly', 'six_months', 'yearly']);
            $table->decimal('amount', 8, 2);
            $table->enum('status', ['active', 'cancelled', 'expired'])->default('active');
            $table->timestamp('starts_at');
            $table->timestamp('ends_at');
            $table->string('mercadopago_subscription_id')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('subscriptions');
    }
}
