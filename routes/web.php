<?php

use App\Http\Controllers\BurgerController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\ProfileController;
use App\Mail\ConfirmationCommande;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [BurgerController::class, 'index'])->name('catalogue');
Route::middleware(['auth'])->get('/catalogue_Client', [BurgerController::class, 'index'])->name('catalogue_Client');

//Route::get('/dashboard', function () {
//    return view('dashboard');
//})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::middleware(['auth', 'client'])->group(function () {
    Route::get('/catalogue', [BurgerController::class, 'index']);
    Route::post('/commande', [CommandeController::class, 'store']);
});
Route::get('/burger/{id}', [BurgerController::class, 'show'])->name('burger.show');
Route::get('/commande', [CommandeController::class, 'index'])->name('commande.index');
Route::get('/commande/{commande}', [CommandeController::class, 'show'])->name('commande.show');
Route::Post('/commande', [CommandeController::class, 'store'])->name('commande.store');
Route::post('/checkout', [CommandeController::class, 'checkout'])->name('commande.checkout')->middleware('auth');
Route::get('/Commandes', function () {
    return view('commandes_client');
})->middleware(['auth'])->name('commandes');

Route::get('/test-email/{commande}', function ($commandeId) {
    $commande = Commande::findOrFail($commandeId);
    Mail::to(auth()->user()->email)->send(new ConfirmationCommande($commande));
    return "Email envoyé avec succès !";
});
require __DIR__.'/auth.php';
