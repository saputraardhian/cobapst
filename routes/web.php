<?php

use App\Livewire\KioskLobby;
use App\Livewire\PublicLandingPage;
use App\Livewire\QueueTvDisplay;
use Illuminate\Support\Facades\Route;

// ID: Rute Portal Layanan Publik Terpadu BPS Jateng (Node 1)
// EN: Route for BPS Central Java Integrated Public Service Portal (Node 1)
Route::get('/', PublicLandingPage::class)->name('home');

// ID: Rute Anjungan Kios Lobi Mandiri Sentuh (Node 2)
// EN: Route for Self-Service Touchscreen Kiosk Lobby (Node 2)
Route::get('/kiosk', KioskLobby::class)->name('kiosk');

// ID: Rute Layar TV Display Antrean Ruang Tunggu PST (Node 3)
// EN: Route for PST Lobby Queue TV Display Monitor (Node 3)
Route::get('/antrian', QueueTvDisplay::class)->name('queue.tv');
Route::redirect('/antrean', '/antrian');

// ID: Rute Pintasan Konsol Meja Operator (Node 4)
// EN: Shortcut route to Desk Console Operator (Node 4)
Route::redirect('/konsol-meja', '/admin/konsol-meja');
Route::redirect('/operator', '/admin/konsol-meja');

// ID: Rute Pintasan Modul STATISFY Analytics & AI Summary (Node 5)
// EN: Shortcut route to STATISFY Analytics & AI Summary module (Node 5)
Route::redirect('/statisfy', '/admin/statisfy');

// ID: Rute Pintasan Workspace Pegawai Bento UI (Node 6)
// EN: Shortcut route to Employee Workspace Bento UI (Node 6)
Route::redirect('/workspace', '/admin');
Route::redirect('/admin/workspace', '/admin');

