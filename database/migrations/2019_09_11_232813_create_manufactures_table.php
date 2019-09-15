<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateManufacturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manufactures', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('order_ref');
            $table->uuid('product_id');
            $table->uuid('uom_id');
            $table->decimal('quantity',50,2);
            $table->date('deadline');
            $table->uuid('status_id')->default('8083f49e-f0aa-4094-894f-f64cd2e9e4e9');
            $table->uuid('warehouse_id')->nullable();
            $table->uuid('created_by');
            $table->uuid('updated_by')->nullable();
            $table->primary('id');
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
        Schema::dropIfExists('manufactures');
    }
}
