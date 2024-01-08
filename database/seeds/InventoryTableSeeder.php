<?php

use Illuminate\Database\Seeder;
use iteos\Models\Inventory;
use bfinlay\SpreadsheetSeeder\SpreadsheetSeeder;

class InventoryTableSeeder extends SpreadsheetSeeder
{
    /* public function __construct()
    {
        $this->file = '/database/seeds/inventories.xls'; // specify relative to Laravel project base path
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function settings(SpreadsheetSeederSettings $set)
    {
        $set->file = '/database/seeds/inventories.xlsx';
    }

    public function run()
    {
        DB::disableQueryLog();
	    parent::run();
    }
}
