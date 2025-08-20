<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('coupon_category', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupon_id')->constrained('coupons')->onDelete('cascade');

            if (Schema::hasTable('categories')) {
                $table->foreignId('category_id')->nullable()
                      ->constrained('categories')
                      ->cascadeOnDelete();
            } else {
                $table->unsignedBigInteger('category_id')->nullable();
            }

            $table->timestamps();
            $table->softDeletes();
        });
    }
    public function down()
    {
        Schema::dropIfExists('coupon_category');
    }
};
