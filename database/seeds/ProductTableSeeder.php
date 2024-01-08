<?php

use Illuminate\Database\Seeder;
use iteos\Models\Product;
use bfinlay\SpreadsheetSeeder\SpreadsheetSeeder;

class ProductTableSeeder extends SpreadsheetSeeder
{
    /*public function __construct()
    {
        $this->file = '/dump_db/products.xls'; // specify relative to Laravel project base path
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    /* public function settings(SpreadsheetSeederSettings $set)
    {
        $set->file = '\database\seeds\products.xls';
    } */
    
    /*public function run()
    {
        DB::disableQueryLog();
	    parent::run();
    }*/

    public function run()
    {
        $this->call([
            SpreadsheetSeeder::class,
        ]);
    }
}
