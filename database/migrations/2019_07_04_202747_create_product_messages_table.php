<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_messages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('slug');
            $table->longText('subject');
            $table->longText('message');
            $table->longText('action_text');
            $table->longText('action_url');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_messages');
    }
}
