<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->enum('type', ['video', 'music', 'software', 'communication', 'other'])->default('other');
            $table->decimal('price', 10, 2);
            $table->enum('cycle', ['monthly', 'quarterly', 'yearly', 'custom'])->default('monthly');
            $table->date('expire_at');
            $table->boolean('auto_renew')->default(false);
            $table->enum('status', ['active', 'paused', 'cancelled', 'expired'])->default('active');
            $table->string('icon')->nullable();
            $table->text('note')->nullable();
            $table->string('account_info')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('subscriptions');
    }
};