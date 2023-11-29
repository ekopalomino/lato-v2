<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('request_name');
            $table->bigInteger('from_wh');
            $table->bigInteger('to_wh');
            $table->bigInteger('status_id');
            $table->uuid('created_by');
            $table->uuid('updated_by')->nullable();
            $table->uuid('approve_by')->nullable();
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
        Schema::dropIfExists('inventory_requests');
    }
}
