<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('coupon_course', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupon_id')->constrained('coupons')->onDelete('cascade');
            if (Schema::hasTable('courses')) {
                $table->foreignId('course_id')->nullable()
                      ->constrained('courses')
                      ->cascadeOnDelete();
            } else {
                $table->unsignedBigInteger('course_id')->nullable();
            }

            $table->timestamps();
            $table->softDeletes();
        });
    }
    public function down()
    {
        Schema::dropIfExists('coupon_course');
    }
};
