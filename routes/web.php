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
    Route::get('products/edit/{id}','Apps\ProductManagementController@productEdit')->name('product.edit');
    Route::post('products/update/{id}','Apps\ProductManagementController@productUpdate')->name('product.update');
    Route::post('products/delete/{id}','Apps\ProductManagementController@productDestroy')->name('product.destroy');
    /*-----------------------End Product Management--------------------------------*/

    /*-----------------------Sales Management------------------------------------*/
    Route::get('sales','Apps\SalesManagementController@index')->name('sales.index');
    Route::get('sales/point-of-sale','Apps\SalesManagementController@posIndex')->name('pos.index');
    Route::get('sales/orders/create','Apps\SalesManagementController@create')->name('sales.create');
    Route::post('sales/orders/store','Apps\SalesManagementController@storeSales')->name('sales.store');
    Route::get('sales/orders/edit/{id}','Apps\SalesManagementController@editSales')->name('sales.edit');
    Route::post('sales/orders/update/{id}','Apps\SalesManagementController@updateSales')->name('sales.update');
    Route::post('sales/orders/approve/{id}','Apps\SalesManagementController@processSales')->name('sales.approve');
    Route::post('sales/orders/rejected/{id}','Apps\SalesManagementController@rejectedSale')->name('sales.rejected');
    Route::post('sales/orders/close/{id}','Apps\SalesManagementController@closeSale')->name('sales.close');
    Route::get('sales/orders/pdf/{id}','Apps\SalesManagementController@salesPrint')->name('sales.pdf');
    Route::get('sales/orders/show/{id}','Apps\SalesManagementController@salesShow')->name('sales.show');
    Route::get('sales/barcode','Apps\SalesManagementController@salesBarcode')->name('sales.barcode');
    Route::get('sales/barcode/pdf','Apps\SalesManagementController@barcodePdf')->name('salesbarcode.pdf');
    Route::get('sales/return-product','Apps\SalesManagementController@returSale')->name('salesRetur.index');
    /*-----------------------End Sales Management------------------------------------*/

    /*-----------------------Purchase Management------------------------------------*/
    Route::get('purchase','Apps\PurchaseManagementController@index')->name('purchase.index');
    Route::get('purchase/request/create','Apps\PurchaseManagementController@requestCreate')->name('request.create');
    Route::post('purchase/request/store','Apps\PurchaseManagementController@requestStore')->name('request.store');
    Route::get('purchase/request/show/{id}','Apps\PurchaseManagementController@requestShow')->name('request.show');
    Route::get('purchase/request/print/{id}','Apps\PurchaseManagementController@requestPrint')->name('request.print');
    Route::get('purchase/request/edit/{id}','Apps\PurchaseManagementController@requestForm')->name('request.form');
    Route::post('purchase/request/approve/{id}','Apps\PurchaseManagementController@requestApprove')->name('request.approve');
    Route::get('purchase/order/show/{id}','Apps\PurchaseManagementController@purchaseShow')->name('purchase.show');
    Route::post('purchase/orders/rejected/{id}','Apps\PurchaseManagementController@requestRejected')->name('request.rejected');
    Route::get('purchase/orders/print/{id}','Apps\PurchaseManagementController@purchasePrint')->name('purchase.print');
    Route::post('purchase/orders/close/{id}','Apps\PurchaseManagementController@purchaseClose')->name('purchase.close');
    /*-----------------------End Purchase Management------------------------------------*/

    /*-----------------------Inventory Management------------------------------------*/
    Route::get('inventories','Apps\InventoryManagementController@inventoryIndex')->name('inventory.index');
    Route::post('inventories/initial-stock','Apps\InventoryManagementController@initialStock')->name('initial.stock');
    Route::get('inventories/stockcard/{id}','Apps\InventoryManagementController@stockCard')->name('inventory.card');
    Route::get('inventories/stockcard/print/{id}','Apps\InventoryManagementController@stockPrint')->name('stock.pdf');
    Route::get('inventory/adjustment','Apps\InventoryManagementController@inventoryAdjustIndex')->name('inventory.adjust');
    Route::get('inventory/adjustment/{id}','Apps\InventoryManagementController@makeAdjust')->name('make.adjust');
    Route::post('inventory/adjustment/store/{id}','Apps\InventoryManagementController@storeAdjust')->name('store.adjust');
    Route::get('inventories/internal-transfer','Apps\InventoryManagementController@internTransfer')->name('transfer.index');
    Route::get('inventories/internal-transfer/find','Apps\InventoryManagementController@searchProduct')->name('transfer.product');
    Route::get('inventories/internal-transfer/create','Apps\InventoryManagementController@addTransfer')->name('add.transfer');
    Route::post('inventories/internal-transfer/store','Apps\InventoryManagementController@internStore')->name('store.transfer');
    Route::post('inventories/internal-transfer/accept/{id}','Apps\InventoryManagementController@transferAccept')->name('transfer.accept');
    Route::get('inventories/internal-transfer/view/{id}','Apps\InventoryManagementController@transferView')->name('transfer.view');
    Route::get('inventories/purchase-receipt','Apps\InventoryManagementController@receiptIndex')->name('receipt.index');
    Route::get('inventories/purchase-receipt/search/purchase-order','Apps\InventoryManagementController@receiptSearch')->name('receipt.search');
    Route::post('inventories/purchase-receipt/get/purchase-order','Apps\InventoryManagementController@receiptGet')->name('receipt.get');
    Route::post('inventories/purchase-receipt/store','Apps\InventoryManagementController@receiptStore')->name('receipt.store');
    Route::get('inventories/purchase-receipt/edit/{id}','Apps\InventoryManagementController@receiptEdit')->name('receipt.edit');
    Route::post('inventories/purchase-receipt/update/{id}','Apps\InventoryManagementController@receiptUpdate')->name('receipt.update');
    Route::post('inventories/purchase-receipt/close/{id}','Apps\InventoryManagementController@receiptClose')->name('receipt.close');
    Route::get('inventories/delivery-order','Apps\InventoryManagementController@doIndex')->name('delivery.index');
    Route::get('inventories/delivery-order/search/sales-order','Apps\InventoryManagementController@doSearch')->name('delivery.search');
    Route::post('inventories/delivery-order/get/sales-order','Apps\InventoryManagementController@doGet')->name('delivery.get');
    Route::get('inventories/delivery-order/create','Apps\InventoryManagementController@doMake')->name('delivery.create');
    Route::post('inventories/delivery-order/store','Apps\InventoryManagementController@doStore')->name('delivery.store');
    Route::get('inventories/delivery-order/edit/{id}','Apps\InventoryManagementController@doEdit')->name('delivery.edit');
    Route::post('inventories/delivery-order/cancel/{id}','Apps\InventoryManagementController@doCancel')->name('delivery.cancel');
    Route::get('inventories/delivery-order/show-items/{id}','Apps\InventoryManagementController@doShow')->name('delivery.show');
    Route::get('inventories/delivery-order/receipt/{id}','Apps\InventoryManagementController@doReceipt')->name('delivery.receipt');
    Route::post('inventories/delivery-order/delivered/{id}','Apps\InventoryManagementController@doDone')->name('delivery.done');
    Route::get('inventories/delivery-order/print/{id}','Apps\InventoryManagementController@doPrint')->name('delivery.print');
    Route::get('inventories/delivery-order/retur','Apps\InventoryManagementController@returItem')->name('deliveryRetur.index');
    Route::get('inventories/delivery-order/retur/search/order','Apps\InventoryManagementController@returSearch')->name('deliveryRetur.search');
    Route::post('inventories/delivery-order/retur/get/order','Apps\InventoryManagementController@returGet')->name('deliveryRetur.get');
    Route::post('inventories/delivery-order/retur/store','Apps\InventoryManagementController@returStore')->name('deliveryRetur.store');
    /*-----------------------End Inventory Management------------------------------------*/
 
    /*-----------------------Manufacture Management------------------------------------*/
    Route::get('manufactures/request','Apps\ManufactureManagementController@requestIndex')->name('manufacture-request.index');
    Route::get('manufactures/request/create','Apps\ManufactureManagementController@createRequest')->name('manufacture-request.create');
    Route::get('manufactures/request/product/find','Apps\ManufactureManagementController@manufactureProduct')->name('manufacture-request.product');
    Route::post('manufactures/request/store','Apps\ManufactureManagementController@storeRequest')->name('manufacture-request.store');
    Route::get('manufactures/request/store/{id}','Apps\ManufactureManagementController@checkStock')->name('manufacture-request.check');
    Route::post('manufactures/request/approve/{id}','Apps\ManufactureManagementController@approveRequest')->name('manufacture-request.approve');
    Route::get('manufactures','Apps\ManufactureManagementController@index')->name('manufacture.index');
    Route::get('manufactures/order/stock-check/{id}','Apps\ManufactureManagementController@reCheckStock')->name('manufactureStock.check');
    Route::post('manufactures/order/stock-validate/{id}','Apps\ManufactureManagementController@approveStock')->name('manufactureStock.validate');
    Route::post('manufactures/order/process/{id}','Apps\ManufactureManagementController@makeManufacture')->name('manufacture.process');
    Route::get('manufactures/order/done/{id}','Apps\ManufactureManagementController@manufactureDone')->name('manufacture.done');
    Route::post('manufactures/order/complete','Apps\ManufactureManagementController@process')->name('manufacture.complete');
    Route::get('manufactures/order/show/{id}','Apps\ManufactureManagementController@manufactureShow')->name('manufacture.show');
    Route::get('manufactures/order/transfer/{id}','Apps\ManufactureManagementController@manufactureTransfer')->name('manufacture.transfer');
    Route::post('manufactures/order/transfer/process/{id}','Apps\ManufactureManagementController@transferProcess')->name('transfer.process');
    /*-----------------------End Manufacture Management------------------------------------*/
 
    /*-----------------------Reports Management------------------------------------*/
    Route::get('reports/table/sales','Apps\ReportManagementController@saleTable')->name('sale.table');
    Route::post('reports/table/sales/view','Apps\ReportManagementController@reportSales')->name('sale-table.view');
    Route::get('reports/table/inventory','Apps\ReportManagementController@inventoryTable')->name('inventory.table');
    Route::post('reports/table/inventory/view','Apps\ReportManagementController@reportInventory')->name('inventory-table.view');
    Route::get('reports/table/purchase','Apps\ReportManagementController@purchaseTable')->name('purchase.table');
    Route::post('reports/table/purchase/view','Apps\ReportManagementController@reportPurchase')->name('purchase-table.view');
    Route::get('reports/table/manufacture','Apps\ReportManagementController@manufactureTable')->name('manufacture.table');
    Route::post('reports/table/manufacture/view','Apps\ReportManagementController@reportManufacture')->name('manufacture-table.view');
    /*-----------------------End Reports Management------------------------------------*/

    /*-----------------------Finance Management------------------------------------*/
    Route::get('finance/invoices/index','Apps\PaymentManagementController@invoiceIndex')->name('invoice.index');
    Route::get('finance/invoices/create','Apps\PaymentManagementController@invoiceMake')->name('invoice.create');
    Route::post('finance/invoices/search','Apps\PaymentManagementController@referenceGet')->name('invoice.search');
    Route::post('finance/invoices/store','Apps\PaymentManagementController@invoiceStore')->name('invoice.store');
    Route::post('finance/invoices/payment-receive/{id}','Apps\PaymentManagementController@invoicePayment')->name('invoice.payment');
    Route::get('finance/invoices/cicilan/{id}','Apps\PaymentManagementController@cicilanCreate')->name('invoiceCicilan.create');
    Route::post('finance/invoices/cicilan/{id}','Apps\PaymentManagementController@cicilanStore')->name('invoiceCicilan.store');
    Route::get('finance/invoices/show/{id}','Apps\PaymentManagementController@invoiceShow')->name('invoice.show');
    Route::get('finance/invoices/print/{id}','Apps\PaymentManagementController@invoicePrint')->name('invoice.print');
    Route::get('finance/purchase-receipt/index','Apps\PaymentManagementController@receiptIndex')->name('purchaseReceipt.index');
    Route::get('finance/purchase-receipt/search','Apps\PaymentManagementController@receiptSearch')->name('purchaseReceipt.search');
    Route::post('finance/purchase-receipt/result','Apps\PaymentManagementController@receiptGet')->name('purchaseReceipt.get');
    Route::get('finance/purchase-receipt/make','Apps\PaymentManagementController@receiptMake')->name('receiptPayment.make');
    Route::post('finance/purchase-receipt/create','Apps\PaymentManagementController@receiptStore')->name('receiptPayment.store');
    Route::post('finance/purchase-receipt/payment-made/{id}','Apps\PaymentManagementController@receiptPayment')->name('purchaseReceipt.payment');
    Route::get('finance/purchase-receipt/show/{id}','Apps\PaymentManagementController@receiptShow')->name('purchaseReceipt.show');
    Route::get('finance/purchase-receipt/print/{id}','Apps\PaymentManagementController@receiptPrint')->name('purchaseReceipt.print');
});
