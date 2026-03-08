<?php

use App\Http\Controllers\AdminPortalController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ClientPortalController;
use App\Http\Controllers\LandingPageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingPageController::class, 'index'])->name('home');
Route::post('/lead', [LandingPageController::class, 'storeLead'])->name('lead.store');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store'])->name('register.store');
});

Route::middleware('auth')->group(function (): void {
    Route::get('/dashboard', [LandingPageController::class, 'dashboardRedirect'])->name('dashboard.redirect');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function (): void {
        Route::get('/', [AdminPortalController::class, 'dashboard'])->name('dashboard');
        Route::post('/customers', [AdminPortalController::class, 'storeCustomer'])->name('customers.store');
        Route::post('/projects', [AdminPortalController::class, 'storeProject'])->name('projects.store');
        Route::post('/services', [AdminPortalController::class, 'storeService'])->name('services.store');
        Route::post('/invoices', [AdminPortalController::class, 'storeInvoice'])->name('invoices.store');
        Route::patch('/invoices/{invoice}', [AdminPortalController::class, 'updateInvoiceStatus'])->name('invoices.update');
        Route::post('/tickets', [AdminPortalController::class, 'storeTicket'])->name('tickets.store');
        Route::patch('/tickets/{ticket}', [AdminPortalController::class, 'updateTicketStatus'])->name('tickets.update');
        Route::post('/servers', [AdminPortalController::class, 'storeServer'])->name('servers.store');
        Route::patch('/servers/{server}', [AdminPortalController::class, 'updateServerStatus'])->name('servers.update');
    });

    Route::middleware('role:client')->prefix('portal')->name('client.')->group(function (): void {
        Route::get('/', [ClientPortalController::class, 'dashboard'])->name('dashboard');
        Route::post('/payment-notifications', [ClientPortalController::class, 'storePaymentNotification'])->name('payments.store');
        Route::post('/tickets', [ClientPortalController::class, 'storeTicket'])->name('tickets.store');
    });
});

Route::get('/control-center', [LandingPageController::class, 'controlCenter'])->name('control-center');
Route::get('/client-hub', [LandingPageController::class, 'clientHub'])->name('client-hub');
