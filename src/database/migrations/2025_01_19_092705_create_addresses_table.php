<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('post_code', 10)->nullable();;
            $table->string('address', 255)->nullable();;
            $table->string('building', 255)->nullable();
            $table->enum('payment_method', ['コンビニ払い', 'カード払い']);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('addresses')) {
            Schema::table('addresses', function (Blueprint $table) {
                if (Schema::hasColumn('addresses', 'product_id')) {
                    $table->dropForeign(['product_id']);
                }
                if (Schema::hasColumn('addresses', 'user_id')) {
                    $table->dropForeign(['user_id']);
                }
        });

        Schema::dropIfExists('addresses'); // テーブルを削除
    }
}

}
