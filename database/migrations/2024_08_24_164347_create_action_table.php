<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('actions', function (Blueprint $table) {
            $table->id();
            $table->string('device'); // Thiết bị
            $table->enum('action', ['on', 'off']); // Hành động (bật/tắt)
            $table->timestamp('time');
            $table->unsignedInteger('user_id'); // ID người dùng
            $table->timestamps();

            // Thêm khóa ngoại (nếu cần)
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('action');
    }
};
