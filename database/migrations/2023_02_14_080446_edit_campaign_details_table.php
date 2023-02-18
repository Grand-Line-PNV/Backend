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
        Schema::table('campaign_details', function (Blueprint $table) {
            $table->string('industry');
            $table->string('hashtag');
            $table->string('socialChannel');
            $table->integer('amount');
            $table->text('require');
            $table->text('interest');
            $table->text('description')->change();
            $table->dropColumn('notes');
            $table->dropForeign(['file_id']); 
            $table->dropColumn(['file_id']); 
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('campaign_details', function (Blueprint $table) {            
        });
    }
};