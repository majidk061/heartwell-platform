<?php

use App\Http\Controllers\Web\ClinicalIntakeController;
use App\Http\Controllers\Web\ContactController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\PageController;
use App\Http\Controllers\Web\WebhookController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

$pageSlugs = [
    'support-pathways',
    'your-experience',
    'why-heartwell',
    'wellness-journey',
    'meet-the-founder',
    'contact',
];

foreach ($pageSlugs as $slug) {
    Route::get('/'.$slug, [PageController::class, 'show'])
        ->defaults('slug', $slug)
        ->name($slug);
}

Route::post('/contact/waitlist', [ContactController::class, 'storeWaitlist'])
    ->name('contact.waitlist');

Route::post('/contact/consultation', [ContactController::class, 'storeConsultation'])
    ->name('contact.consultation');

Route::post('/contact/group-inquiry', [ContactController::class, 'storeGroupInquiry'])
    ->name('contact.group-inquiry');

Route::get('/clinical-intake', ClinicalIntakeController::class)
    ->name('clinical-intake');

Route::post('/webhooks/acuity', [WebhookController::class, 'acuity'])
    ->name('webhooks.acuity');
