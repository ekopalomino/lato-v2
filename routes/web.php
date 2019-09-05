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

Auth::routes();
Route::group(['prefix' => 'apps', 'middleware' => ['auth']], function() {
    Route::resource('dashboard','Apps\DashboardController');
    /*-----------------------User Management-----------------------------*/
    Route::get('users','Apps\UserManagementController@userIndex')->name('user.index');
    Route::post('users/create','Apps\UserManagementController@userStore')->name('user.store');
    Route::get('users/edit/{id}','Apps\UserManagementController@userEdit')->name('user.edit');
    Route::get('users/show/{id}','Apps\UserManagementController@userShow')->name('user.show');
    Route::post('users/update/{id}','Apps\UserManagementController@userUpdate')->name('user.update');
    Route::post('users/delete/{id}','Apps\UserManagementController@userDestroy')->name('user.destroy');
    Route::get('users/profile', 'Apps\UserManagementController@userProfile')->name('user.profile');
    Route::post('users/profile/avatar', 'Apps\UserManagementController@updateAvatar')->name('user.avatar');
    Route::post('users/profile/password', 'Apps\UserManagementController@updatePassword')->name('user.password');
    Route::get('users/roles','Apps\UserManagementController@roleIndex')->name('role.index');
    Route::post('users/roles/create','Apps\UserManagementController@roleStore')->name('role.store');
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
    Route::get('settings/warehouse','Apps\ConfigurationController@warehouseIndex')->name('warehouse.index');
    Route::post('settings/warehouse/create','Apps\ConfigurationController@warehouseStore')->name('warehouse.store');
    Route::get('settings/warehouse/edit/{id}','Apps\ConfigurationController@warehouseEdit')->name('warehouse.edit');
    Route::post('settings/warehouse/update/{id}','Apps\ConfigurationController@warehouseUpdate')->name('warehouse.update');
    Route::post('settings/warehouse/delete/{id}','Apps\ConfigurationController@warehouseDestroy')->name('warehouse.destroy');
    Route::get('settings/payment-methods','Apps\ConfigurationController@methodIndex')->name('pay-method.index');
    Route::post('settings/payment-methods/create','Apps\ConfigurationController@methodStore')->name('pay-method.store');
    Route::get('settings/payment-methods/edit/{id}','Apps\ConfigurationController@methodEdit')->name('pay-method.edit');
    Route::post('settings/payment-methods/update/{id}','Apps\ConfigurationController@methodUpdate')->name('pay-method.update');
    Route::post('settings/payment-methods/delete/{id}','Apps\ConfigurationController@methodDestroy')->name('pay-method.destroy');
    Route::get('settings/payment-terms','Apps\ConfigurationController@termIndex')->name('pay-term.index');
    Route::post('settings/payment-terms/create','Apps\ConfigurationController@termStore')->name('pay-term.store');
    Route::get('settings/payment-terms/edit/{id}','Apps\ConfigurationController@termEdit')->name('pay-term.edit');
    Route::post('settings/payment-terms/update/{id}','Apps\ConfigurationController@termUpdate')->name('pay-term.update');
    Route::post('settings/payment-terms/delete/{id}','Apps\ConfigurationController@termDestroy')->name('pay-term.destroy');
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
    /*-----------------------End Config Management-----------------------------*/

    /*-----------------------Product Management--------------------------------*/
    Route::get('products/categories','Apps\ProductManagementController@categoryIndex')->name('product-cat.index');
    Route::post('products/categories/create','Apps\ProductManagementController@categoryStore')->name('product-cat.store');
    Route::get('products/categories/edit/{id}','Apps\ProductManagementController@categoryEdit')->name('product-cat.edit');
    Route::post('products/categories/update/{id}','Apps\ProductManagementController@categoryUpdate')->name('product-cat.update');
    Route::post('products/categories/delete/{id}','Apps\ProductManagementController@categoryDestroy')->name('product-cat.destroy');
    Route::get('products','Apps\ProductManagementController@productIndex')->name('product.index');
    Route::get('products/create','Apps\ProductManagementController@productCreate')->name('product.create');
    Route::post('products/store','Apps\ProductManagementController@productStore')->name('product.store');
    Route::get('products/show/{id}','Apps\ProductManagementController@productShow')->name('product.show');
    Route::get('products/show/print/{id}','Apps\ProductManagementController@productPdf')->name('product.pdf');
    Route::get('products/barcode/{id}','Apps\ProductManagementController@productBarcode')->name('product.barcode');
    Route::get('products/edit/{id}','Apps\ProductManagementController@productEdit')->name('product.edit');
    Route::post('products/update/{id}','Apps\ProductManagementController@productUpdate')->name('product.update');
    Route::post('products/delete/{id}','Apps\ProductManagementController@productDestroy')->name('product.destroy');
    Route::get('products/bom','Apps\ProductManagementController@indexBom')->name('product-bom.index');
    Route::get('products/bom/create/{id}','Apps\ProductManagementController@createBom')->name('product-bom.create');
    Route::post('products/bom/store','Apps\ProductManagementController@storeBom')->name('product-bom.store');
    Route::get('products/bom/edit/{id}','Apps\ProductManagementController@editBom')->name('product-bom.edit');
    Route::post('products/bom/update/{id}','Apps\ProductManagementController@updateBom')->name('product-bom.update');
    Route::post('products/bom/delete/{id}','Apps\ProductManagementController@destroyBom')->name('product-bom.destroy');
    /*-----------------------End Product Management--------------------------------*/

    /*-----------------------Sales Management------------------------------------*/
    Route::get('sales/customers','Apps\SalesManagementController@customerIndex')->name('customer.index');
    Route::get('sales/customers/create','Apps\SalesManagementController@customerCreate')->name('customer.create');
    Route::post('sales/customers/store','Apps\SalesManagementController@customerStore')->name('customer.store');
    Route::get('sales/customers/edit/{id}','Apps\SalesManagementController@customerEdit')->name('customer.edit');
    Route::post('sales/customers/update/{id}','Apps\SalesManagementController@customerUpdate')->name('customer.update');
    Route::post('sales/customers/delete/{id}','Apps\SalesManagementController@customerDestroy')->name('customer.destroy');
    Route::get('sales','Apps\SalesManagementController@index')->name('sales.index');
    Route::get('sales/orders/create','Apps\SalesManagementController@create')->name('sales.create');
    Route::post('sales/orders/store','Apps\SalesManagementController@storeSales')->name('sales.store');
    Route::get('sales/orders/items/create/{id}','Apps\SalesManagementController@createItems')->name('sales.items');
	Route::post('sales/orders/items/store','Apps\SalesManagementController@storeItems')->name('sales-item.store');
	Route::get('sales/orders/sum/{id}','Apps\SalesManagementController@updateSo')->name('sales.sum');
	Route::get('sales/orders/show/{id}','Apps\SalesManagementController@salesShow')->name('sales.show');
    /*-----------------------End Sales Management------------------------------------*/

    /*-----------------------Purchase Management------------------------------------*/
    Route::get('purchase/suppliers','Apps\PurchaseManagementController@supplierIndex')->name('supplier.index');
    Route::get('purchase/suppliers/create','Apps\PurchaseManagementController@supplierCreate')->name('supplier.create');
    Route::post('purchase/suppliers/store','Apps\PurchaseManagementController@supplierStore')->name('supplier.store');
    Route::get('purchase/suppliers/edit/{id}','Apps\PurchaseManagementController@supplierEdit')->name('supplier.edit');
    Route::post('purchase/suppliers/update/{id}','Apps\PurchaseManagementController@supplierUpdate')->name('supplier.update');
    Route::post('purchase/suppliers/delete/{id}','Apps\PurchaseManagementController@supplierDestroy')->name('supplier.destroy');
    /*-----------------------End Purchase Management------------------------------------*/

    /*-----------------------Inventory Management------------------------------------*/
    Route::get('inventories','Apps\InventoryManagementController@inventoryIndex')->name('inventory.index');
    Route::get('inventories/stockcard/{id}','Apps\InventoryManagementController@stockCard')->name('inventory.card');
    Route::get('inventory/adjustment','Apps\InventoryManagementController@inventoryAdjustIndex')->name('inventory.adjust');
    Route::get('inventory/adjustment/{id}','Apps\InventoryManagementController@makeAdjust')->name('make.adjust');
    Route::post('inventory/adjustment/store/{id}','Apps\InventoryManagementController@storeAdjust')->name('store.adjust');
    /*-----------------------End Inventory Management------------------------------------*/
});
