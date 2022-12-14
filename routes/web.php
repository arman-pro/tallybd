<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/login', 'MBCorporation\AdminController@login')->name('login');
Route::post('/user-check', 'MBCorporation\AdminController@LoginAdmin')->name('usercheck');

Route::group(['middleware' => 'guest'], function () {
    Route::get('/', 'MBCorporation\HomeController@index')->name('mb_cor_index');
    Route::get('/dashboard-ajax-request', 'MBCorporation\HomeController@dashboard_request_ajax')->name('dashboard_ajax');

    Route::get('activeLedger', 'SearchLedgerController@activeLedger');
    Route::get('activeGroup', 'SearchLedgerController@activeGroup');
    Route::get('activeItem', 'SearchLedgerController@activeItem');
    Route::get('paymentLedger', 'SearchLedgerController@paymentLedger');
    Route::get('expenseLedger', 'SearchLedgerController@expenseLedger');
    Route::get('employee', 'SearchLedgerController@employee');


    Route::get('bankinterest/index', 'MBCorporation\ReportController@bankInterest');
    Route::post('bankinterest/get', 'MBCorporation\ReportController@bankCalculation');


    //employee -journal
    Route::group(['prefix' =>'employee-journal' ,'as'=> 'employee.journal.'], function(){
        Route::get('index', 'MBCorporation\EmployeeJournalController@index')->name('index');
        Route::get('create', 'MBCorporation\EmployeeJournalController@create')->name('create');
        Route::post('store', 'MBCorporation\EmployeeJournalController@store');
        Route::get('edit/{id}', 'MBCorporation\EmployeeJournalController@edit');
        Route::post('update/{id}', 'MBCorporation\EmployeeJournalController@update');
        Route::get('delete/{id}', 'MBCorporation\EmployeeJournalController@delete');
        Route::get('print/{id}', 'MBCorporation\EmployeeJournalController@print');

        Route::POST('/employee_journal_list/store/', 'MBCorporation\EmployeeJournalController@contra_journal_addlist_store')->name('store');
        Route::get('/journal_add_new_field/-{vo_no}', 'MBCorporation\EmployeeJournalController@journal_add_new_field')->name('journal_add_new_field');
        Route::get('/employee_democontrajournal_delete_fild/-{id_row}', 'MBCorporation\EmployeeJournalController@democontrajournal_delete_fild');
    });

     //department
    Route::group(['prefix' =>'department' ,'as'=> 'department.'], function(){
        Route::get('index', 'MBCorporation\DepartmentController@index');
        Route::get('create', 'MBCorporation\DepartmentController@create');
        Route::post('store', 'MBCorporation\DepartmentController@store');
        Route::get('edit/{id}', 'MBCorporation\DepartmentController@edit');
        Route::post('update/{id}', 'MBCorporation\DepartmentController@update');
        Route::get('delete/{id}', 'MBCorporation\DepartmentController@delete');
    });

    //designation
    Route::group(['prefix' =>'designation' ,'as'=> 'designation.'], function(){
        Route::get('index', 'MBCorporation\DesignationController@index');
        Route::get('create', 'MBCorporation\DesignationController@create');
        Route::post('store', 'MBCorporation\DesignationController@store');
        Route::get('edit/{id}', 'MBCorporation\DesignationController@edit');
        Route::Post('update/{id}', 'MBCorporation\DesignationController@update');
        Route::get('delete/{id}', 'MBCorporation\DesignationController@delete');
    });

    //shift
    Route::group(['prefix' =>'shift' ,'as'=> 'shift.'], function(){
        Route::get('index', 'MBCorporation\ShiftController@index');
        Route::get('create', 'MBCorporation\ShiftController@create');
        Route::post('store', 'MBCorporation\ShiftController@store');
        Route::get('edit/{id}', 'MBCorporation\ShiftController@edit');
        Route::post('update/{id}', 'MBCorporation\ShiftController@update');
        Route::get('delete/{id}', 'MBCorporation\ShiftController@delete');
    });


    //employee
    Route::group(['prefix' =>'employee' ,'as'=> 'employee.'], function(){
        Route::get('index', 'MBCorporation\EmployeeController@index');
        Route::get('create', 'MBCorporation\EmployeeController@create')->name('create');
        Route::post('store', 'MBCorporation\EmployeeController@store')->name('store');
        Route::get('edit/{id}', 'MBCorporation\EmployeeController@edit');
        Route::post('update/{id}', 'MBCorporation\EmployeeController@update');
        Route::get('delete/{id}', 'MBCorporation\EmployeeController@delete');
        Route::get('status/{id}', 'MBCorporation\EmployeeController@status');
    });

    // salary
    Route::group(['prefix' =>'salary' ,'as'=> 'salary.'], function(){
        Route::get('index', 'MBCorporation\SalaryController@index')->name('index');
        Route::get('create', 'MBCorporation\SalaryController@create')->name('create');
        Route::post('store', 'MBCorporation\SalaryController@store')->name('store');
        Route::get('edit/{id}', 'MBCorporation\SalaryController@edit')->name('edit');
        Route::get('print/{id}', 'MBCorporation\SalaryController@print')->name('print');
        Route::post('update/{id}', 'MBCorporation\SalaryController@update');
        Route::get('delete/{id}', 'MBCorporation\SalaryController@destroy');
        Route::get('search', 'MBCorporation\SalaryController@searchSalary');
        Route::get('search-employee', 'MBCorporation\SalaryController@getEmployee');
        Route::get('report-salary', 'MBCorporation\SalaryController@reportSalary');
        Route::get('/employee/salary-by/date', 'MBCorporation\SalaryController@reportEmployee');
        
    });

    // salary-payment
    Route::group(['prefix' =>'salary-payment' ,'as'=> 'salary_payment.'], function(){
        Route::get('index', 'MBCorporation\SalaryPaymentController@index')->name('index');
        Route::get('create', 'MBCorporation\SalaryPaymentController@create')->name('create');
        Route::post('store', 'MBCorporation\SalaryPaymentController@store')->name('store');
        Route::get('edit/{id}', 'MBCorporation\SalaryPaymentController@edit')->name('edit');
        Route::post('update/{id}', 'MBCorporation\SalaryPaymentController@update')->name('update');
        Route::get('delete/{id}', 'MBCorporation\SalaryPaymentController@destroy');
        Route::get('search', 'MBCorporation\SalaryPaymentController@searchSalary');
        Route::get('search-ledger', 'MBCorporation\SalaryPaymentController@searchAccountSummary')->name('searchAccountSummary');
        Route::get('report-salary', 'MBCorporation\SalaryPaymentController@reportSalary');
        Route::get('/employee/salary-by/date', 'MBCorporation\SalaryPaymentController@reportEmployee');
        Route::get('print_salary_payment_recepet/{vo_no}', 'MBCorporation\SalaryPaymentController@print_salary_payment_recepet')->name('print_salary_payment_recepet');
        // salary-received
        Route::get('received', 'MBCorporation\SalaryPaymentController@received')->name("receive");
        Route::get('create_receive', 'MBCorporation\SalaryPaymentController@create_receive')->name('create_receive');
        Route::post('store_receive', 'MBCorporation\SalaryPaymentController@store_receive')->name('store_receive');
        Route::get('edit_receive/{id}', 'MBCorporation\SalaryPaymentController@edit_receive')->name('edit_receive');
        Route::post('update_receive/{id}', 'MBCorporation\SalaryPaymentController@update_receive')->name('update_receive');
        Route::get('delete_receive/{id}', 'MBCorporation\SalaryPaymentController@destroy_receive')->name("delete_receive");
        Route::get('print_salary_receive_recepet/{vo_no}', 'MBCorporation\SalaryPaymentController@print_salary_receive_recepet');
    });

    // workingorder
    Route::group(['prefix' =>'workingorder' ,'as'=> 'workingOrder.'], function(){
        Route::get('/index', 'MBCorporation\WorkingOrderController@index')->name('index');
        Route::get('create', 'MBCorporation\WorkingOrderController@create')->name('create');
        Route::post('store', 'MBCorporation\WorkingOrderController@store')->name('store');
        Route::get('edit/{id}', 'MBCorporation\WorkingOrderController@edit')->name("edit");
        Route::post('update/{id}', 'MBCorporation\WorkingOrderController@update');
        Route::get('print/{id}', 'MBCorporation\WorkingOrderController@print')->name('print');
        Route::get('delete/{id}', 'MBCorporation\WorkingOrderController@destroy');
        Route::get('search', 'MBCorporation\WorkingOrderController@searchSalary');
        Route::get('adjustment', 'MBCorporation\WorkingOrderController@production_adjustment');
        Route::get('/findProductRow', 'MBCorporation\WorkingOrderController@findProductRow');
        Route::get('delete_field_from_add/-{id_row}', 'MBCorporation\WorkingOrderController@delete_field_from_add');
    });

    // production
    Route::group(['prefix' => 'production' ,'as'=> 'production.'], function(){
        Route::get('index', 'MBCorporation\ProductionController@index')->name('index');
        Route::get('create', 'MBCorporation\ProductionController@create')->name('create');
        Route::post('store', 'MBCorporation\ProductionController@store')->name('store');
        Route::get('edit/{id}', 'MBCorporation\ProductionController@edit')->name('edit');
        Route::post('update/{id}', 'MBCorporation\ProductionController@update');
        Route::get('delete/{id}', 'MBCorporation\ProductionController@destroy');
        Route::get('search', 'MBCorporation\ProductionController@searchSalary');
        Route::get('adjustment', 'MBCorporation\ProductionController@production_adjustment');
        Route::get('/findProductRow', 'MBCorporation\ProductionController@findProductRow');
        Route::get('delete_field_from_add/-{id_row}', 'MBCorporation\ProductionController@delete_field_from_add');
        Route::get('orderList', 'MBCorporation\ProductionController@orderList');
        Route::get('print/{id}', 'MBCorporation\ProductionController@print')->name('print');

    });


    // ===Admin======
    Route::get('/create-admin', 'MBCorporation\AdminController@index');
    Route::post('/insert-admin', 'MBCorporation\AdminController@store');
    Route::get('/view-admin', 'MBCorporation\AdminController@show');
    Route::get('/editadminModal/{id}', 'MBCorporation\AdminController@editadminModal');
    Route::post('/update-admin/{id}', 'MBCorporation\AdminController@update');
    Route::post('/inactive-status-admin', 'MBCorporation\AdminController@inactivestatusadmin');
    Route::post('/active-status-admin', 'MBCorporation\AdminController@activestatusadmin');
    Route::post('/delete-account-admin', 'MBCorporation\AdminController@destroy');
    
    Route::get('/userpermission', 'MBCorporation\AdminController@user_permission');
    Route::get('/user_permission/{id}', 'MBCorporation\AdminController@user_permission');
    Route::post('/userpermission_update', 'MBCorporation\AdminController@user_permissionUpdate');

    //admin main menu
    Route::get(
        'MainMenu',
        [
            'as' => 'MainMenu',
            'uses' => 'MBCorporation\AdmainMenuCon@index',
        ]
    )->where(['MainMenu' => '[A-Z]+', 'MainMenu' => '[a-z]+']);

    Route::get('AdminMainMenuModel/{id}', 'MBCorporation\AdmainMenuCon@showDate');
    Route::post('AdmainSaveMainlink', 'MBCorporation\AdmainMenuCon@store');
    Route::post('AdminEditMainlink', 'MBCorporation\AdmainMenuCon@update');
    Route::post('adminDeleteData/{id}', 'MBCorporation\AdmainMenuCon@Dalete');

    //admin sub menu
    Route::get(
        'SubMenu',
        [
            'as' => 'SubMenu',
            'uses' => 'MBCorporation\AdminSubMenuCon@index'
        ]
    )->where(['SubMenu' => '[A-Z]+', 'SubMenu' => '[a-z]+']);

    Route::post('AdminSubLinkSave', 'MBCorporation\AdminSubMenuCon@store');
    Route::get('adminSubModelEdit/{id}', 'MBCorporation\AdminSubMenuCon@showDate');
    Route::post('AdminMainMenuEditcon', 'MBCorporation\AdminSubMenuCon@update');
    Route::post('AdminSubmenuDelete/{id}', 'MBCorporation\AdminSubMenuCon@Dalete');

    // company details.................
    Route::get('/company_details', 'MBCorporation\CompanyDetailcompany_detailsController@index')->name('company_details');
    Route::get('/year-setting', 'MBCorporation\CompanyDetailcompany_detailsController@yearSetting')->name('yearSetting');
    Route::get('/active-change-year/{id}', 'MBCorporation\CompanyDetailcompany_detailsController@yearSettingActive')->name('yearSettingActive');
    Route::post('/year-setting/store', 'MBCorporation\CompanyDetailcompany_detailsController@yearSettingstore')->name('yearSetting.store');
    Route::post('/Update_company_details/{id}', 'MBCorporation\CompanyDetailcompany_detailsController@Update_company_details');


    // ................... Start InvwentorT / meterial................................................
    // godown List details.................
    Route::get('/godown-list', 'MBCorporation\GodownController@index')->name('godown_list');
    Route::get('/godown_create_from', 'MBCorporation\GodownController@godown_create_from')->name('godown_create_from');
    Route::post('/store_godown_create_from', 'MBCorporation\GodownController@store_godown_create_from');
    Route::get('/edit_godown/{id}', 'MBCorporation\GodownController@edit_godown');
    Route::post('/update_godown/{id}', 'MBCorporation\GodownController@update_godown');
    Route::get('/delete_godown/{id}', 'MBCorporation\GodownController@delete_godown');

    // Unit List details.................
    Route::get('/unit_list', 'MBCorporation\UnitController@index')->name('unit_list');
    Route::post('/store_unit', 'MBCorporation\UnitController@store_unit');
    Route::get('/edit_unit/{id}', 'MBCorporation\UnitController@edit_unit');
    Route::post('/update_unit/{id}', 'MBCorporation\UnitController@update_unit')->name('unit_update');
    Route::get('/delete_unit/{id}', 'MBCorporation\UnitController@delete_unit');

    // category List details.................
    Route::get('/category', 'MBCorporation\CategoryController@index')->name('category');
    Route::post('/store_category', 'MBCorporation\CategoryController@store_category');
    Route::get('/edit_category/{id}', 'MBCorporation\CategoryController@edit');
    Route::post('/update_category/{id}', 'MBCorporation\CategoryController@update_category')->name('update_category');
    Route::get('/delete_category/{id}', 'MBCorporation\CategoryController@delete_category');

    // Item List details.................
    Route::get('/item_list', 'MBCorporation\ItemController@index')->name('item_list');
    Route::get('/item_create_from', 'MBCorporation\ItemController@item_create_from')->name('item_create_from');
    Route::post('/store_item', 'MBCorporation\ItemController@store_item');
    Route::get('/edit_item/{item_code}', 'MBCorporation\ItemController@edit_item')->name('edit_item');
    Route::post('/update_item/{item_code}', 'MBCorporation\ItemController@update_item');
    Route::get('/delete_item/{item_code}', 'MBCorporation\ItemController@delete_item');
    Route::get('/print_all_item', 'MBCorporation\ItemController@print_all_item');
    // ................... End InvwentorT / meterial................................................



    // ................... Start Account................................................

    // account group List details.................
    Route::get('/account_group_list', 'MBCorporation\AccountController@account_group_list')->name('account_group_list');
    Route::get('/account_group_create', 'MBCorporation\AccountController@account_group_create')->name('account_group_create');
    Route::post('/store_account_group', 'MBCorporation\AccountController@store_account_group');
    Route::get('/edit_account_group/{account_group_id}', 'MBCorporation\AccountController@edit_account_group');
    Route::post('/update_account_group/{account_group_id}', 'MBCorporation\AccountController@update_account_group');
    Route::get('/delete_account_group/{account_group_id}', 'MBCorporation\AccountController@delete_account_group');

    // account Ladger List details.................
    Route::get('/account_ledger_list', 'MBCorporation\AccountController@account_ledger_list')->name('account_ledger_list');
    Route::get('/account_ledger_create', 'MBCorporation\AccountController@account_ledger_create')->name('account_ledger_create');
    Route::post('/store_account_ledger', 'MBCorporation\AccountController@store_account_ledger');
    Route::get('/edit_account_ledger/{account_ledger_id}', 'MBCorporation\AccountController@edit_account_ledger');
    Route::post('/update_account_ledger/{account_ledger_id}', 'MBCorporation\AccountController@update_account_ledger');
    Route::get('/delete_account_ledger/{account_ledger_id}', 'MBCorporation\AccountController@delete_account_ledger');

    // selasman list details.................
    Route::get('/selasman_list', 'MBCorporation\AccountController@selasman_list')->name('selasman_list');
    Route::get('/selasman_create', 'MBCorporation\AccountController@selasman_create')->name('selasman_create');
    Route::post('/store_selasman', 'MBCorporation\AccountController@store_selasman');
    Route::get('/edit_SaleMan/{SaleMan_id}', 'MBCorporation\AccountController@edit_SaleMan');
    Route::post('/update_SaleMan/{SaleMan_id}', 'MBCorporation\AccountController@update_SaleMan');
    Route::get('/delete_SaleMan/{SaleMan_id}', 'MBCorporation\AccountController@delete_SaleMan');

    // chat_of_account.................
    Route::get('/chat_of_account', 'MBCorporation\AccountController@chat_of_account')->name('chat_of_account');

    // ................... End Account................................................

    // ................... Start Transaction................................................
    // Purchases Add &  List.................
    Route::get('/purchases_addlist_list', 'MBCorporation\PurchasesController@purchases_addlist_list')->name('purchases_addlist_list');
    Route::get('/purchases_addlist_from', 'MBCorporation\PurchasesController@purchases_addlist_from')->name('purchases_addlist_from');
    Route::post('/SaveAllData/store/', 'MBCorporation\PurchasesController@SaveAllData_store');
    Route::get('/edit_purchases/{product_id_list}', 'MBCorporation\PurchasesController@edit_purchases')->name('edit_purchases');
    Route::post('/Update/PurchasesAddList/{product_id_list}', 'MBCorporation\PurchasesController@UpdatePurchasesAddList');
    Route::get('/delete_purchases/{product_id_list}', 'MBCorporation\PurchasesController@delete_purchases');
    Route::get('/view_purchases/{product_id_list}', 'MBCorporation\PurchasesController@view_purchases')->name('view_purchases');
    Route::get('/print_pruchases_invoice/{product_id_list}', 'MBCorporation\PurchasesController@print_pruchases_invoice')->name('print_pruchases_invoice');
    Route::get('/send_purchases_sms/{product_id_list}', 'MBCorporation\PurchasesController@send_purchases_sms');



    // Purchasesand sales demo product and demo route Start.................
    // Purchasesand sales demo product and demo route Start.................
    Route::get('/product_as_price/-{product_name}', 'MBCorporation\PurchasesController@product_as_price');
    Route::get('/account_details_for_invoice/-{account_ledger_id}', 'MBCorporation\PurchasesController@account_details_for_invoice');
    Route::post('/addondemoproduct/store/', 'MBCorporation\PurchasesController@addondemoproduct_store');
    Route::get('/product_new_fild/-{product_id_list}', 'MBCorporation\PurchasesController@product_new_fild');
    Route::get('/product_delete_fild/-{id_row}/{stock?}', 'MBCorporation\PurchasesController@product_delete_fild');
    Route::get('/account_pre_amount/-{account_id_for_preamound}', 'MBCorporation\PurchasesController@account_pre_amount');
    Route::get('/delete/Demoproductaddonvouture/{id_row}', 'MBCorporation\PurchasesController@deleteDemoproductaddonvouture');
    // Purchasesand sales demo product and demo route End.................
    // Purchasesand sales demo product and demo route End.................


    // purchases_order_addlist Add &  List.................
    Route::get('/purchases_order_addlist', 'MBCorporation\PurchasesController@purchases_order_addlist')->name('purchases_order_addlist');
    Route::get('/purchases_Order_addlist_from', 'MBCorporation\PurchasesController@purchases_order_addlist_form')->name('purchases_Order_addlist_from');
    Route::post('/SaveAllData/order/store/', 'MBCorporation\PurchasesController@SaveAllData_order_store');

    Route::get('/edit_purchases_order/{product_id_list}', 'MBCorporation\PurchasesController@edit_order_purchases');
    Route::post('/Update/PurchasesOrderAddList/{product_id_list}', 'MBCorporation\PurchasesController@UpdatePurchasesaOrderddlist');
    Route::get('/delete_purchases_order/{product_id_list}', 'MBCorporation\PurchasesController@delete_order_purchases');

    // purchases_return_addlist Add &  List.................
    Route::get('/purchases_return_addlist', 'MBCorporation\PurchasesController@purchases_return_addlist')->name('purchases_return_addlist');
    Route::get('/purchases_return_addlist_form', 'MBCorporation\PurchasesController@purchases_return_addlist_form')->name('purchases_return_addlist_form');
    Route::post('/SaveAllData/return/store/', 'MBCorporation\PurchasesController@SaveAllData_return_store');
    Route::get('/edit_purchases_return/{product_id_list}', 'MBCorporation\PurchasesController@edit_return_purchases')->name("edit_purchases_return");
    Route::post('/Update/PurchasesReturnAddList/{product_id_list}', 'MBCorporation\PurchasesController@UpdatePurchasesaReturnddlist');
    Route::get('/delete_purchases_return/{product_id_list}', 'MBCorporation\PurchasesController@delete_return_purchases');
    Route::get('/send_purchases_return_sms/{product_id_list}', 'MBCorporation\PurchasesController@send_purchases_return_sms')->name('send_purchases_return_sms');
    Route::get('/print_pruchases_return_invoice/{product_id_list}', 'MBCorporation\PurchasesController@print_pruchases_return_invoice')->name("print_pruchases_return_invoice");


    // sales_addlist Add &  List.................
    Route::get('/sales_addlist', 'MBCorporation\SalesController@sales_addlist')->name('sales_addlist');
    Route::get('/sales_addlist_form', 'MBCorporation\SalesController@sales_addlist_form')->name('sales_addlist_form');
    Route::post('/SaveAllData_sales/store/', 'MBCorporation\SalesController@SaveAllData_sales');
    Route::get('/edit_sales/{product_id_list}', 'MBCorporation\SalesController@edit_sales')->name("edit_sales");
    Route::post('/Update/Sales/{product_id_list}', 'MBCorporation\SalesController@UpdateSalesAddList');
    Route::get('/delete_sales/{product_id_list}', 'MBCorporation\SalesController@delete_sales');
    Route::get('/send_sales_sms/{product_id_list}', 'MBCorporation\SalesController@send_sales_sms')->name("send_sales_sms");

    Route::get('/view_sales/{product_id_list}', 'MBCorporation\SalesController@view_sales')->name("view_sales");
    Route::get('/print_sales_invoice/{product_id_list}', 'MBCorporation\SalesController@print_sales_invoice')->name("print_sales_invoice");
    Route::get('/print_sales_gate_pass/{product_id_list}', 'MBCorporation\SalesController@print_sales_gate_pass')->name("print_sales_gate_pass");
    Route::get('/print_sales_invoice2/{product_id_list}', 'MBCorporation\SalesController@print_sales_invoice2');

    // sales order addlist Add &  List.................
    
    Route::get('/sales_order_addlist', 'MBCorporation\SalesController@sales_order_addlist')->name('sales_order_addlist');
    Route::get('/sales_order_addlist_form', 'MBCorporation\SalesController@sales_order_addlist_form')->name('sales_order_addlist_form');
    Route::post('/SaveAllData/sales_order/store/', 'MBCorporation\SalesController@SaveAllData_sales_order_store');
    Route::get('/view_sales_order/{product_id_list}', 'MBCorporation\SalesController@view_sales_order')->name("view_sales_order");
    Route::get('/edit_sales_order/{product_id_list}', 'MBCorporation\SalesController@edit_sales_order')->name('edit_sales_order');
    Route::post('/Update/SalesOrderAddList/{product_id_list}', 'MBCorporation\SalesController@UpdateSalesOrderAddList');
    Route::get('/delete_sales_order/{product_id_list}', 'MBCorporation\SalesController@delete_sales_order');
    Route::get('/print_sales_order_invoice/{product_id_list}', 'MBCorporation\SalesController@print_sales_order_invoice');
    Route::get('/sales_order_approved/{product_id_list}/{md_signature}', 'MBCorporation\SalesController@sales_order_approved')->name('sales_order_approved');

    // salesreturn_addlist Add &  List.................
    Route::get('/salesreturn_addlist', 'MBCorporation\SalesController@salesreturn_addlist')->name('salesreturn_addlist');
    Route::get('/salesreturn_addlist_form', 'MBCorporation\SalesController@salesreturn_addlist_form')->name('salesreturn_addlist_form');
    Route::Post('/SaveAllData/sales_return/store/', 'MBCorporation\SalesController@SaveAllData_sales_return_store');
    Route::get('/edit_sales_return/{product_id_list}', 'MBCorporation\SalesController@edit_sales_return')->name("edit_sales_return");
    Route::post('/Update/SalesReturnaddlist/{product_id_list}', 'MBCorporation\SalesController@UpdateSalesReturnaddlist');
    Route::get('/delete_sales_return/{product_id_list}', 'MBCorporation\SalesController@delete_sales_return');
    Route::get('/send_sales_return_sms/{product_id_list}', 'MBCorporation\SalesController@send_sales_return_sms')->name("send_sales_return_sms");
    Route::get('/print_sales_return_invoice/{product_id_list}', 'MBCorporation\SalesController@print_sales_return_invoice')->name('print_sales_return_invoice');



    // Received Add &  List.................
    Route::get('/recevied_addlist', 'MBCorporation\ReceviePaynebtController@recevied_addlist')->name('recevied_addlist');
    Route::get('/recevied_addlist_form', 'MBCorporation\ReceviePaynebtController@recevied_addlist_form')->name('recevied_addlist_form');
    Route::post('/store_recived_addlist', 'MBCorporation\ReceviePaynebtController@store_recived_addlist');
    Route::get('/edit_recevie_addlist/{vo_no}', 'MBCorporation\ReceviePaynebtController@edit_recevie_addlist')->name("edit_recevie_addlist");
    Route::get('/delete_recevie_addlist/{vo_no}', 'MBCorporation\ReceviePaynebtController@delete_recevie_addlist');
    Route::post('/update_recived_addlist/{vo_no}', 'MBCorporation\ReceviePaynebtController@update_recived_addlist');
    Route::get('/print_receive_recepet/{vo_no}', 'MBCorporation\ReceviePaynebtController@print_receive_recepet')->name('print_receive_recepet');
  
    Route::get('/view_recevie_recepet/{vo_no}', 'MBCorporation\ReceviePaynebtController@view_recevie_recepet')->name('view_recevie_recepet');
    Route::get('/send_recevie_sms/{id}', 'MBCorporation\ReceviePaynebtController@send_receive_sms')->name("send_recevie_sms");

    // Payment Add &  List.................
    Route::get('/payment_addlist', 'MBCorporation\ReceviePaynebtController@payment_addlist')->name('payment_addlist');
    Route::get('/payment_addlist_form', 'MBCorporation\ReceviePaynebtController@payment_addlist_form')->name('payment_addlist_form');
    Route::post('/store_payment_addlist', 'MBCorporation\ReceviePaynebtController@store_payment_addlist');
    Route::get('/edit_payment_addlist/{vo_no}', 'MBCorporation\ReceviePaynebtController@edit_payment_addlist')->name('edit_payment_addlist');
    Route::get('/delete_payment_addlist/{vo_no}', 'MBCorporation\ReceviePaynebtController@delete_payment_addlist');
    Route::post('/update_payment_addlist/{vo_no}', 'MBCorporation\ReceviePaynebtController@update_payment_addlist');
    
    // import payment by xcel
    Route::get('/importpayment', 'MBCorporation\ReceviePaynebtController@import_payment')->name('importpayment');
    Route::post('/importpayment', 'MBCorporation\ReceviePaynebtController@store_import_payment')->name('importpayment');

    Route::get('/print_payment_recepet/{vo_no}', 'MBCorporation\ReceviePaynebtController@print_payment_recepet')->name('print_payment_recepet');
    Route::get('/view_payment_recepet/{vo_no}', 'MBCorporation\ReceviePaynebtController@view_payment_recepet')->name('view_payment_recepet');
    Route::get('/send_payment_sms/{id}', 'MBCorporation\ReceviePaynebtController@send_payment_sms')->name('send_payment_sms');
    Route::get('/ledgerValue/{id}', 'MBCorporation\ReceviePaynebtController@ledgerValue');

    // Contra Add &  List.................
    Route::get('/contra_addlist', 'MBCorporation\ContraJournalController@contra_addlist')->name('contra_addlist');
    Route::get('/contra_addlist_form', 'MBCorporation\ContraJournalController@contra_addlist_form')->name('contra_addlist_form');
    Route::get('/delete_contra_addlist/{id}', 'MBCorporation\ContraJournalController@delete_contra_addlist');
    Route::get('/edit_contra_addlist/{id}', 'MBCorporation\ContraJournalController@edit_contra_addlist')->name('edit_contra_addlist');
    Route::post('/Update/Contra/{vo_no}/', 'MBCorporation\ContraJournalController@Update_Contra_addlist');

    Route::get('/view_contra_recepet/{vo_no}', 'MBCorporation\ContraJournalController@view_contra_recepet')->name('view_contra_recepet');
    Route::get('/print_contra_recepet/{vo_no}', 'MBCorporation\ContraJournalController@print_contra_recepet')->name('print_contra_recepet');


    //Store contra  journal Common  .....................
    Route::post('/contra_journal_addlist/store/', 'MBCorporation\ContraJournalController@contra_journal_addlist_store');


    // addondemocontrajournal Add &  List start.................
    // addondemocontrajournal Add &  List start.................
    Route::post('/addondemocontrajournal_list/store/', 'MBCorporation\ContraJournalController@addondemocontrajournal_list_store');
    Route::get('/contra_journaladd_new_fild/-{vo_no}', 'MBCorporation\ContraJournalController@contra_journaladd_new_fild');
    Route::get('/democontrajournal_delete_fild/-{id_row}', 'MBCorporation\ContraJournalController@democontrajournal_delete_fild');

    // addondemocontrajournal Add &  List End.................
    // addondemocontrajournal Add &  List End.................


    // journal_addlist Add &  List.................
    Route::get('/journal_addlist', 'MBCorporation\ContraJournalController@journal_addlist')->name('journal_addlist');
    Route::get('/journa_addlist_form', 'MBCorporation\ContraJournalController@journa_addlist_form')->name('journa_addlist_form');
    Route::get('/delete_journal_addlist/{id}', 'MBCorporation\ContraJournalController@delete_journal_addlist');
    Route::get('/edit_journal_addlist/{id}', 'MBCorporation\ContraJournalController@edit_journal_addlist')->name("edit_journal_addlist");
    Route::post('/Update/journal/{vo_no}', 'MBCorporation\ContraJournalController@Update_Journal_addlist');

    Route::get('/view_journal_recepet/{vo_no}', 'MBCorporation\ContraJournalController@view_journal_recepet')->name("view_journal_recepet");
    Route::get('/print_journal_recepet/{vo_no}', 'MBCorporation\ContraJournalController@print_journal_recepet')->name("print_journal_recepet");



    // stock_transfer_addlist Add &  List.................
    Route::get('/stock_transfer_addlist', 'MBCorporation\StockController@stock_transfer_addlist')->name('stock_transfer_addlist');
    Route::get('/stock_transfer_addlist_form', 'MBCorporation\StockController@stock_transfer_addlist_form')->name('stock_transfer_addlist_form');
    Route::Post('/SaveAllData/stock_transfer/store/', 'MBCorporation\StockController@SaveAllData_StockTransfer_store');
    Route::get('/edit_stocktransfer/{product_id_list}', 'MBCorporation\StockController@edit_stocktransfer');
    Route::post('/Update/stock_transfer/{product_id_list}', 'MBCorporation\StockController@Update_stocktransfer');
    Route::get('/delete_stocktransfer/{product_id_list}', 'MBCorporation\StockController@delete_stocktransfer');
    Route::get('/stocktransfer_product_delete_fild_from_add/-{id_row}', 'MBCorporation\StockController@stocktransfer_product_delete_fild_from_add');

    // stock_adjustment_addlist Add &  List.................
    Route::get('/stock_adjustment_addlist', 'MBCorporation\StockController@stock_adjustment_addlist')->name('stock_adjustment_addlist');
    Route::get('/stock_adjustment_addlist_form', 'MBCorporation\StockController@stock_adjustment_addlist_form')->name('stock_adjustment_addlist_form');
    Route::get('/SaveAllData_adjusment/store/', 'MBCorporation\StockController@SaveAllData_adjusment_store');
    Route::get('/edit_stock_adjustment/{adjustmen_vo_id}', 'MBCorporation\StockController@edit_stock_adjustment')->name("edit_stock_adjustment");
    Route::post('/Update/stock_adjustment/{adjustmen_vo_id}', 'MBCorporation\StockController@Updatestock_adjustment');
    Route::get('/delete_stock_adjustment/{adjustmen_vo_id}', 'MBCorporation\StockController@delete_stock_adjustment');


    Route::get('/add_ondemoproduct_for_adjustment/store/', 'MBCorporation\StockController@add_ondemoproduct_for_adjustment_store');
    Route::get('/product_new_fild_for_add_inStock/-{adjustmen_vo_id}', 'MBCorporation\StockController@product_new_fild_for_add_inStock');
    Route::get('/adjustment_product_delete_fild_from_add/-{id_row}', 'MBCorporation\StockController@adjustment_product_delete_fild_from_add');



    // ................... End Transaction................................................

    // ................... Start Production................................................
    Route::get('/work_order_Production_list', 'MBCorporation\ProductionController@work_order_Production_list')->name('work_order_Production_list');
    Route::get('/work_order_Production_addform', 'MBCorporation\ProductionController@work_order_Production_addform')->name('work_order_Production_addform');

    // ................... End Production................................................


    // ................... Start Report................................................

    // ................... Start Balance Sheet Report................................................
    Route::get('/balance_sheet_report', 'MBCorporation\ReportController@balance_sheet_report')->name('balance_sheet_report');
    // ................... End Balance Sheet Report................................................

    // ................... Start Day Book Report................................................
    Route::get('/day_book_report', 'MBCorporation\ReportController@day_book_report')->name('day_book_report');
    // Route::post('/day_book_report/by/date', 'MBCorporation\ReportController@day_book_reportbydate');
    // ................... End Day Book Report................................................

    // ................... Start Account Ledger Report................................................
    Route::get('/account_ledger_search_from', 'MBCorporation\ReportController@account_ledger_search_from')->name('account_ledger_search_from');
    Route::get('/account_ledger_report/by/date', 'MBCorporation\ReportController@account_ledger_report_by_date')->name('account_ledger_report');
    // ................... End Account Ledger Report................................................
    // ................... Start Account Ledger Report................................................
    // Route::get('/account_group_ledger_search_from', 'MBCorporation\ReportController@account_ledger_group_search_from');
    Route::get('/account_ledger_group_report/by/date', 'MBCorporation\ReportController@account_ledger_group_reportbydate')->name('account_ledger_group_search_from');
    // ................... End Account Ledger Report................................................

    // ................... Start all_purchases_reportt................................................
    Route::get('/all_purchases_report', 'MBCorporation\ReportController@all_purchases_report')->name('all_purchases_report');
    Route::get('/all_purchases_report/by/date', 'MBCorporation\ReportController@all_purchases_reportbydate');

    Route::get('/item_wise_purchases_report_search_form', 'MBCorporation\ReportController@item_wise_purchases_report_search_form')->name('item_wise_purchases_report_search_form');
    Route::get('/item_wise_purchases_report/by/item', 'MBCorporation\ReportController@item_wise_purchases_report');

    Route::get('/party_wise_purchases_report_search', 'MBCorporation\ReportController@party_wise_purchases_report_search')->name('party_wise_purchases_report_search');
    Route::get('/party_wise_purchases_report', 'MBCorporation\ReportController@party_wise_purchases_report');
    // ................... End all_purchases_report................................................

    // ................... Start all_sales_report................................................
    Route::get('/all_sales_report', 'MBCorporation\ReportController@all_sales_report')->name('all_sales_report');
    Route::get('/all_sales_report/by/date', 'MBCorporation\ReportController@all_sales_reportbydate');

    Route::get('/item_wise_sales_report_search_form', 'MBCorporation\ReportController@item_wise_sales_report_search_form')->name('item_wise_sales_report_search_form');
    Route::get('/item_wise_sales_report/by/item', 'MBCorporation\ReportController@item_wise_sales_report');

    Route::get('/party_wise_sales_report_search', 'MBCorporation\ReportController@party_wise_sales_report_search')->name('party_wise_sales_report_search');
    Route::get('/party_wise_sales_report', 'MBCorporation\ReportController@party_wise_sales_report');

    Route::get('/sale_man_wise_sales_report_search', 'MBCorporation\ReportController@sale_man_wise_sales_report_search')->name('sale_man_wise_sales_report_search');
    Route::get('/sale_man_wise_sales_report', 'MBCorporation\ReportController@sale_man_wise_sales_report');

    // ................... End all_purchases_report................................................

    // ................... Start all_stock_summery_report................................................
    Route::get('/all_stock_summery_report', 'MBCorporation\ReportController@all_stock_summery_report')->name('all_stock_summery_report');
    Route::get('/all_stock_summery_report/by-date', 'MBCorporation\ReportController@all_stock_summery_report_by_date')->name('all_stock_summery_report.date');

    Route::get('/stock_summery_report_category_search_from', 'MBCorporation\ReportController@stock_summery_report_category_search_from')->name('stock_summery_report_catagory_search_from');
    Route::post('/stock_summery_report/by/category', 'MBCorporation\ReportController@stock_summery_reportbycategory');

    Route::get('/stock_summery_report_godown_search_from', 'MBCorporation\ReportController@stock_summery_report_godown_search_from')->name('stock_summery_report_godown_search_from');
    Route::post('/stock_summery_report/by/godown', 'MBCorporation\ReportController@stock_summery_reportbygodown');


    Route::get('/stock_summery_report_item_search_from', 'MBCorporation\ReportController@stock_summery_report_item_search_from')->name('stock_summery_report_item_search_from');

    // ................... End all_stock_summery_report................................................

    // ................... Start all_recevie_payment................................................
    Route::get('/all_recevie_payment', 'MBCorporation\ReportController@all_recevie_payment')->name('all_recevie_payment');
    Route::post('/all_recevie_payment/by/date', 'MBCorporation\ReportController@all_recevie_paymentbydate');
    // ................... End all_recevie_payment................................................

    // ................... Start all_recevie_payment................................................
    Route::get('/profit_loss_search', 'MBCorporation\ReportController@profit_loss_search')->name('profit_loss_search');
    Route::post('/profit_loss/by/date', 'MBCorporation\ReportController@profit_loss_bydate');
    // ................... End all_recevie_payment................................................
    // ................... End Report................................................

    // sms
    Route::get('/sms', 'MBCorporation\SmsController@index')->name('sms');
    Route::get('/sms/create', 'MBCorporation\SmsController@create')->name('sms.create');
    Route::post('/sms/create', 'MBCorporation\SmsController@store')->name('sms.create');
    Route::get('/sms/{id}/active', 'MBCorporation\SmsController@sms_active')->name('sms.active');
    Route::get('/sms/{id}', 'MBCorporation\SmsController@edit')->name('sms.edit');
    Route::put('/sms/{id}', 'MBCorporation\SmsController@update')->name('sms.edit');
    Route::get('/sms/{id}/destroy', 'MBCorporation\SmsController@destroy')->name('sms.destroy');
    Route::post('/sms/setting', 'MBCorporation\SmsController@sendSms')->name('sms.send');
    Route::post('/sms/send', 'MBCorporation\SmsController@settingSms')->name('sms.setting');
    // end sms
    Route::get('all-receivable-payable', 'MBCorporation\ReportController@receivable_payable')->name('receivable.payable');
    Route::get('all-receivable-payablesms', 'MBCorporation\ReportController@receivable_payablesms')->name('receivable.payablesms');
    Route::post('sms-send', 'MBCorporation\ReportController@sms_send')->name('sms.send');
    Route::get('cash-flow', 'MBCorporation\ReportController@cashFlow')->name('cash-flow');

});

Route::get('/drive-backup', 'HomeController@drive_upload')->name('gdrive');
Route::get('/batabase/backup', 'HomeController@backup')->name('backup.backup');
Route::get('database/backup/download', 'HomeController@backupDownload')->name('backup.backup.download');
Route::get('backup/download/single/{file}', 'HomeController@singlebackupDownload')->name('backup.download.single');
Route::get('backup/delete/single/{file}', 'HomeController@singlebackupDelete')->name('backup.download.delete');
Route::get('/Admin-logout', 'MBCorporation\AdminController@Adminlogout');


Route::get('add-to-log', 'HomeController@myTestAddToLog');
Route::get('logActivity', 'HomeController@logActivity');
Route::get('logActivity/delete/{id}', 'HomeController@logActivityDelete');
Route::get('logActivity/delete', 'HomeController@logActivityDeleteCron');
