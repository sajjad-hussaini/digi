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

use App\Http\Controllers\AttendanceNoteController;
use App\Http\Controllers\AuthorityLetterController;
use App\Http\Controllers\BalanceStatementController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CustomFieldController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\FileTypeController;
use App\Http\Controllers\FollowUpLetterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LedgerStatementController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class,'welcome'])->name('home');

Route::get('config', function () {
    Artisan::call('route:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('cache:forget spatie.permission.cache');
});
 
Auth::routes();

Route::group(['prefix' => 'admin', 'middleware' => ['auth','check_block']], function () {
    Route::get('/home', [HomeController::class,'index'])->name('admin.dashboard');
    Route::match(['get','post'],'/profile', [HomeController::class,'profile'])->name('profile.manage');
    Route::group(['prefix' => 'advanced'], function () {
        Route::resource('settings', SettingController::class);
        Route::resource('custom-fields', CustomFieldController::class, ['names' => 'customFields']);
        Route::resource('file-types', FileTypeController::class, ['names' => 'fileTypes']);
    });
    Route::resource('users', UserController::class);
    Route::get('/users-block/{user}',[UserController::class,'blockUnblock'])->name('users.blockUnblock');
    Route::resource('tags', TagController::class);
    Route::resource('companies', CompanyController::class);
    Route::resource('clients', ClientController::class);
    Route::resource('invoices', InvoiceController::class);
    Route::resource('templates', TemplateController::class);
    Route::resource('receipts', ReceiptController::class);
    Route::resource('ledger-statements', LedgerStatementController::class);
    Route::resource('balance-statements', BalanceStatementController::class);
    Route::resource('attendance-notes', AttendanceNoteController::class);
    Route::resource('follow-up-letters', FollowUpLetterController::class);
    Route::resource('reminders', ReminderController::class);
    Route::get('clients/{client}/authority-letter/create', [AuthorityLetterController::class, 'create'])->name('authorityLetters.create');
    Route::post('authority-letters/store', [AuthorityLetterController::class, 'store'])->name('authority_letters.store');
    Route::get('clients/{client}/authority-letters', [AuthorityLetterController::class, 'index'])->name('authorityLetter.index');

    // Templates list
    Route::get('/templates/list', [ClientController::class, 'getTemplates'])
        ->name('templates.list');

    // Template content (BLOB)
    Route::get('/templates/{id}/content', [ClientController::class, 'getTemplateContent'])
        ->name('templates.content');

    // Generate document
    Route::post('/client/{client}/initial-instruction/generate', [ClientController::class, 'generateDocument'])
        ->name('client.initial.instruction.generate');

    // Base template
    Route::get('/client/{client}/initial-instruction/base', [ClientController::class, 'initialInstructionBase'])
        ->name('client.initial.instruction.base');


    Route::get('/clients/{client}/authority-letter', [ClientController::class, 'generateAuthorityLetter'])->name('clients.authority-letter');
    Route::post('/clients/{client}/initial-instruction', [ClientController::class, 'initialInstructionLetter'])->name('clients.initial-instruction');
    Route::post('/clients/{client}/advice-letter', [ClientController::class, 'eeCareLetter'])->name('clients.advice-letter');
    Route::get('/clients/{client}/covering-letter', [ClientController::class, 'coveringLetter'])->name('clients.covering-letter');
    Route::get('/clients/{client}/client-care-letter', [ClientController::class, 'clientCareLetter'])->name('clients.client-care-letter');

    Route::resource('documents', DocumentController::class);
    Route::post('document-verify/{id}',[DocumentController::class,'verify'])->name('documents.verify');
    Route::post('document-store-permission/{id}',[DocumentController::class,'storePermission'])->name('documents.store-permission');
    Route::post('document-delete-permission/{document_id}/{user_id}',[DocumentController::class,'deletePermission'])->name('documents.delete-permission');
    Route::group(['prefix' => '/files-upload', 'as' => 'documents.files.'], function () {
        Route::get('/{id}', [DocumentController::class,'showUploadFilesUi'])->name('create');
        Route::post('/{id}', [DocumentController::class,'storeFiles'])->name('store');
        Route::delete('/{id}', [DocumentController::class,'deleteFile'])->name('destroy');
    });

    Route::get('/_files/{dir?}/{file?}',[HomeController::class,'showFile'])->name('files.showfile');
    Route::get('/_zip/{id}/{dir?}',[HomeController::class,'downloadZip'])->name('files.downloadZip');
    Route::post('/_pdf',[HomeController::class,'downloadPdf'])->name('files.downloadPdf');
});