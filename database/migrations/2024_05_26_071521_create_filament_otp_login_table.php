<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        $table_name = config('filament-otp-login.table_name');

        Schema::create('otp_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('email');
            $table->dateTime('expires_at');
            $table->timestamps();
        });
    }

     /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('otp_codes');
    }
};
