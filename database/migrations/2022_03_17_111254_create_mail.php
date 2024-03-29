<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mails', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('fileName',100)->index();
            $table->string("attachement")->nullable();
            $table->string("type")->nullable();
            $table->string('number')->index();
            $table->string('references')->index();
            $table->string('achevementdate')->index();
            $table->string('concerned')->index();
            $table->string('notes')->index();
            $table->string('message')->index()->default("somthing");
            $table->string('title',100)->index()->default("no object");
            $table->string('sender',120)->nullable();
            $table->foreign("sender")->references('doti')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     * 
     */
    public function down()
    {
        Schema::dropIfExists('mails',function(Blueprint $table){
            $table->dropForeign('mails_sender_foreign');
            $table->dropIndex('mails_sender_index');
            $table->dropColumn('sender');
        });
    }
}
