<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('apps.pages.login');
});

Auth::routes(['register' => false]);
Route::group(['prefix' => 'apps', 'middleware' => ['auth']], function() {
    Route::get('login/locked','Auth\LoginController@locked')->name('login.locked');
    Route::post('login/locked','Auth\LoginController@unlock')->name('login.unlock'); 
});
Route::group(['prefix' => 'apps', 'middleware' => ['auth.lock']], function() {
    
    Route::resource('dashboard','Apps\DashboardController');
    /*-----------------------User Management-----------------------------*/
    Route::get('users','Apps\UserManagementController@userIndex')->name('user.index');
    Route::post('users/create','Apps\UserManagementController@userStore')->name('user.store');
    Route::get('users/edit/{id}','Apps\UserManagementController@userEdit')->name('user.edit');
    Route::get('users/show/{id}','Apps\UserManagementController@userShow')->name('user.show');
    Route::post('users/update/{id}','Apps\UserManagementController@userUpdate')->name('user.update');
    Route::post('users/suspend/{id}','Apps\UserManagementController@userSuspend')->name('user.suspend');
    Route::post('users/delete/{id}','Apps\UserManagementController@userDestroy')->name('user.destroy');
    Route::get('users/profile', 'Apps\UserManagementController@userProfile')->name('user.profile');
    Route::post('users/profile/avatar', 'Apps\UserManagementController@updateAvatar')->name('user.avatar');
    Route::post('users/profile/password', 'Apps\UserManagementController@updatePassword')->name('user.password');
    Route::get('users/roles','Apps\UserManagementController@roleIndex')->name('role.index');
    Route::get('users/roles/create','Apps\UserManagementController@roleCreate')->name('role.create');
    Route::post('users/roles/store','Apps\UserManagementController@roleStore')->name('role.store');
    Route::get('users/roles/edit/{id}','Apps\UserManagementController@roleEdit')->name('role.edit');
    Route::get('users/roles/show/{id}','Apps\UserManagementController@roleShow')->name('role.show');
    Route::post('users/roles/update/{id}','Apps\UserManagementController@roleUpdate')->name('role.update');
    Route::post('users/roles/delete/{id}','Apps\UserManagementController@roleDestroy')->name('role.destroy');
    Route::get('users/unit-kerja','Apps\UserManagementController@ukerIndex')->name('uker.index');
    Route::post('users/unit-kerja/create','Apps\UserManagementController@ukerStore')->name('uker.store');
    Route::get('users/unit-kerja/edit/{id}','Apps\UserManagementController@ukerEdit')->name('uker.edit');
    Route::get('users/unit-kerja/show/{id}','Apps\UserManagementController@ukerShow')->name('uker.show');
    Route::post('users/unit-kerja/update/{id}','Apps\UserManagementController@ukerUpdate')->name('uker.update');
    Route::post('users/unit-kerja/delete/{id}','Apps\UserManagementController@ukerDestroy')->name('uker.destroy');
    Route::get('users/log-activities','Apps\LogActivityController@index')->name('user.log');
    /*-----------------------End User Management-----------------------------*/

    /*-----------------------Config Management-----------------------------*/
    Route::get('settings/chart-of-account','Apps\ConfigurationController@coaIndex')->name('coas.index');
    Route::post('settings/chart-of-account/create','Apps\ConfigurationController@coaStore')->name('coas.store');
    Route::get('settings/chart-of-account/edit/{id}','Apps\ConfigurationController@coaEdit')->name('coas.edit');
    Route::post('settings/chart-of-account/update/{id}','Apps\ConfigurationController@coaUpdate')->name('coas.update');
    Route::post('settings/chart-of-account/delete/{id}','Apps\ConfigurationController@coaDestroy')->name('coas.destroy');
    Route::get('settings/branch','Apps\ConfigurationController@branchIndex')->name('branch.index');
    Route::post('settings/branch/create','Apps\ConfigurationController@branchStore')->name('branch.store');
    Route::get('settings/branch/edit/{id}','Apps\ConfigurationController@branchEdit')->name('branch.edit');
    Route::post('settings/branch/update/{id}','Apps\ConfigurationController@branchUpdate')->name('branch.update');
    Route::post('settings/branch/delete/{id}','Apps\ConfigurationController@branchDestroy')->name('branch.destroy');
    Route::get('settings/warehouse','Apps\ConfigurationController@warehouseIndex')->name('warehouse.index');
    Route::post('settings/warehouse/create','Apps\ConfigurationController@warehouseStore')->name('warehouse.store');
    Route::get('settings/warehouse/edit/{id}','Apps\ConfigurationController@warehouseEdit')->name('warehouse.edit');
    Route::post('settings/warehouse/update/{id}','Apps\ConfigurationController@warehouseUpdate')->name('warehouse.update');
    Route::post('settings/warehouse/delete/{id}','Apps\ConfigurationController@warehouseDestroy')->name('warehouse.destroy');
    Route::get('settings/uom-category','Apps\ConfigurationController@uomcatIndex')->name('uom-cat.index');
    Route::post('settings/uom-category/create','Apps\ConfigurationController@uomcatStore')->name('uom-cat.store');
    Route::get('settings/uom-category/edit/{id}','Apps\ConfigurationController@uomcatEdit')->name('uom-cat.edit');
    Route::post('settings/uom-category/update/{id}','Apps\ConfigurationController@uomcatUpdate')->name('uom-cat.update');
    Route::post('settings/uom-category/delete/{id}','Apps\ConfigurationController@uomcatDestroy')->name('uom-cat.destroy');
    Route::get('settings/uom-value','Apps\ConfigurationController@uomvalIndex')->name('uom-val.index');
    Route::post('settings/uom-value/create','Apps\ConfigurationController@uomvalStore')->name('uom-val.store');
    Route::get('settings/uom-value/edit/{id}','Apps\ConfigurationController@uomvalEdit')->name('uom-val.edit');
    Route::post('settings/uom-value/update/{id}','Apps\ConfigurationController@uomvalUpdate')->name('uom-val.update');
    Route::post('settings/uom-value/delete/{id}','Apps\ConfigurationController@uomvalDestroy')->name('uom-val.destroy');
    Route::get('settings/uom-value/export','Apps\ConfigurationController@uomExport')->name('uom.export');

    /*-----------------------End Config Management-----------------------------*/

    /*-----------------------Product Management--------------------------------*/
    Route::get('products/material-group','Apps\ProductManagementController@materialIndex')->name('material.index');
    Route::post('products/material-group/create','Apps\ProductManagementController@materialStore')->name('material.store');
    Route::get('products/material-group/edit/{id}','Apps\ProductManagementController@materialEdit')->name('material.edit');
    Route::post('products/material-group/update/{id}','Apps\ProductManagementController@materialUpdate')->name('material.update');
    Route::post('products/material-group/delete/{id}','Apps\ProductManagementController@materialDestroy')->name('material.destroy');
    Route::get('products/material-group/export','Apps\ProductManagementController@materialExport')->name('material.export');
    Route::get('products/categories','Apps\ProductManagementController@categoryIndex')->name('product-cat.index');
    Route::post('products/categories/create','Apps\ProductManagementController@categoryStore')->name('product-cat.store');
    Route::get('products/categories/edit/{id}','Apps\ProductManagementController@categoryEdit')->name('product-cat.edit');
    Route::post('products/categories/update/{id}','Apps\ProductManagementController@categoryUpdate')->name('product-cat.update');
    Route::post('products/categories/delete/{id}','Apps\ProductManagementController@categoryDestroy')->name('product-cat.destroy');
    Route::get('products/categories/export','Apps\ProductManagementController@categoryExport')->name('product-cat.export');
    Route::get('products','Apps\ProductManagementController@productIndex')->name('product.index');
    Route::get('products/create','Apps\ProductManagementController@productCreate')->name('product.create');
    Route::post('products/store','Apps\ProductManagementController@productStore')->name('product.store');
    Route::get('products/import','Apps\ProductManagementController@productImport')->name('product.page');
    Route::get('products/import/template','Apps\ProductManagementController@importTemplate')->name('product.template');
    Route::post('products/import/store','Apps\ProductManagementController@productImportStore')->name('product.import');
    Route::get('products/edit/{id}','Apps\ProductManagementController@productEdit')->name('product.edit');
    Route::post('products/update/{id}','Apps\ProductManagementController@productUpdate')->name('product.update');
    Route::post('products/delete','Apps\ProductManagementController@productDestroy')->name('product.destroy');
    Route::get('products/download','Apps\ProductManagementController@downloadProduct')->name('product.download');
    /*-----------------------End Product Management--------------------------------*/

    /*-----------------------Purchase Management------------------------------------*/
    Route::get('purchase/request','Apps\PurchaseManagementController@index')->name('request.index');
    Route::get('purchase/request/process/{id}','Apps\PurchaseManagementController@requestShow')->name('request.show');
    Route::post('purchase/request/process/{id}','Apps\PurchaseManagementController@requestProcess')->name('request.process');
    Route::get('purchase/request/create','Apps\PurchaseManagementController@requestCreate')->name('request.create');
    Route::get('purchase/request/import','Apps\PurchaseManagementController@requestImport')->name('request.import');
    Route::get('purchase/request/data/download','Apps\PurchaseManagementController@requestDownload')->name('request.download');
    Route::post('purchase/request/store','Apps\PurchaseManagementController@requestStore')->name('request.store');
    Route::get('purchase/request/print/{id}','Apps\PurchaseManagementController@requestPrint')->name('request.print');
    Route::get('purchase/request/edit/{id}','Apps\PurchaseManagementController@requestForm')->name('request.form');
    Route::post('purchase/request/approve/{id}','Apps\PurchaseManagementController@requestApprove')->name('request.approve');
    Route::get('purchase/order/show/{id}','Apps\PurchaseManagementController@purchaseShow')->name('purchase.show');
    Route::post('purchase/orders/rejected/{id}','Apps\PurchaseManagementController@requestRejected')->name('request.rejected');
    Route::get('purchase/orders/print/{id}','Apps\PurchaseManagementController@purchasePrint')->name('purchase.print');
    Route::post('purchase/orders/close/{id}','Apps\PurchaseManagementController@purchaseClose')->name('purchase.close');
    Route::get('purchase/request/excel/{id}','Apps\PurchaseManagementController@requestExcel')->name('purchase.excel');
    /*-----------------------End Purchase Management------------------------------------*/

    /*-----------------------Inventory Management------------------------------------*/
    Route::get('inventories','Apps\InventoryManagementController@inventoryIndex')->name('inventory.index');
    Route::get('inventories/stockcard/{id}','Apps\InventoryManagementController@stockCard')->name('inventory.card');
    Route::get('inventories/stockcard/print/{id}','Apps\InventoryManagementController@stockPrint')->name('stock.pdf');
    Route::get('inventory/opname','Apps\InventoryManagementController@stockOpnameIndex')->name('opname.index');
    Route::get('inventory/opname/create','Apps\InventoryManagementController@opnameImportPage')->name('opname.create');
    Route::get('inventory/opname/stocks','Apps\InventoryManagementController@stockExport')->name('opname.export');
    Route::post('inventory/opname/import','Apps\InventoryManagementController@opnameProcess')->name('opname.import');
    Route::get('inventory/adjustment/item/{id}','Apps\InventoryManagementController@adjustmentForm')->name('adjustment.page');
    Route::post('inventory/adjustment/store/{id}','Apps\InventoryManagementController@storeAdjust')->name('store.adjust');
    Route::get('product-request/atk-request','Apps\InventoryManagementController@internTransfer')->name('transfer.index');
    Route::get('product-request/atk-request/find','Apps\InventoryManagementController@searchProduct')->name('transfer.product');
    Route::get('product-request/atk-request/create','Apps\InventoryManagementController@addTransfer')->name('add.transfer');
    Route::post('product-request/atk-request/store','Apps\InventoryManagementController@internStore')->name('store.transfer');
    Route::post('product-request/atk-request/accept/{id}','Apps\InventoryManagementController@transferClose')->name('transfer.close');
    Route::get('product-request/atk-request/view/{id}','Apps\InventoryManagementController@transferView')->name('transfer.view');
    Route::get('inventories/purchase-receipt','Apps\InventoryManagementController@receiptIndex')->name('receipt.index');
    Route::get('inventories/purchase-receipt/search/purchase-order','Apps\InventoryManagementController@receiptSearch')->name('receipt.search');
    Route::post('inventories/purchase-receipt/get/purchase-order','Apps\InventoryManagementController@receiptGet')->name('receipt.get');
    Route::post('inventories/purchase-receipt/store','Apps\InventoryManagementController@receiptStore')->name('receipt.store');
    Route::get('inventories/purchase-receipt/edit/{id}','Apps\InventoryManagementController@receiptEdit')->name('receipt.edit');
    Route::post('inventories/purchase-receipt/update/{id}','Apps\InventoryManagementController@receiptUpdate')->name('receipt.update');
    Route::post('inventories/purchase-receipt/close/{id}','Apps\InventoryManagementController@receiptClose')->name('receipt.close');
    /*-----------------------End Inventory Management------------------------------------*/

    /*-----------------------Reports Management------------------------------------*/
    Route::get('reports/table/inventory','Apps\ReportManagementController@inventoryTable')->name('inventory.table');
    Route::post('reports/table/inventory/view','Apps\ReportManagementController@inventoryReport')->name('inventory.view');
    /*-----------------------End Reports Management------------------------------------*/
});
