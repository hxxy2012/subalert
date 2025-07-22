<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('subscription_id')->constrained()->onDelete('cascade');
            $table->integer('remind_days')->default(7);
            $table->set('remind_type', ['email', 'feishu', 'wechat', 'system'])->default('email');
            $table->timestamp('remind_at');
            $table->enum('status', ['pending', 'sent', 'read', 'failed'])->default('pending');
            $table->timestamp('sent_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reminders');
    }
};