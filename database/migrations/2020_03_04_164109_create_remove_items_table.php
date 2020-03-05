<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRemoveItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('remove_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            // 删除玩家物品操作记录
            $table->integer('player')->nullable();  // 玩家id
            $table->integer('zone')->nullable();    // 玩家所在区服
            $table->integer('itemid')->nullable();  // 物品实例id
            $table->integer('configid')->nullable();// 物品配置id
            $table->integer('count')->nullable();   // 删除物品数量 
            $table->integer('status')->nullable();  // 审核状态
            
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
        Schema::dropIfExists('remove_items');
    }
}
