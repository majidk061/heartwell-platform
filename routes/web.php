<?php

use App\Http\Controllers\Admin\PreviewPageController;
use App\Http\Controllers\Web\ClinicalIntakeController;
use App\Http\Controllers\Web\ContactController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\MyVisitController;
use App\Http\Controllers\Web\PageController;
use App\Http\Controllers\Web\WebhookController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/preview/page/{slug}', PreviewPageController::class)
        ->name('admin.preview.page');
    Route::get('/admin/preview/section/{template}', \App\Http\Controllers\Admin\PreviewSectionController::class)
        ->name('admin.preview.section');
});

$pageSlugs = [
    'support-pathways',
    'your-experience',
    'why-heartwell',
    'wellness-journey',
    'meet-the-founder',
    'contact',
    'privacy',
];

foreach ($pageSlugs as $slug) {
    Route::get('/'.$slug, [PageController::class, 'show'])
        ->defaults('slug', $slug)
        ->name($slug);
}

Route::post('/contact/waitlist', [ContactController::class, 'storeWaitlist'])
    ->middleware('throttle:5,1')
    ->name('contact.waitlist');

Route::post('/contact/consultation', [ContactController::class, 'storeConsultation'])
    ->middleware('throttle:5,1')
    ->name('contact.consultation');

Route::post('/contact/group-inquiry', [ContactController::class, 'storeGroupInquiry'])
    ->middleware('throttle:5,1')
    ->name('contact.group-inquiry');

Route::get('/clinical-intake', ClinicalIntakeController::class)
    ->name('clinical-intake');

Route::get('/my-visit', MyVisitController::class)
    ->name('my-visit');

Route::post('/webhooks/acuity', [WebhookController::class, 'acuity'])
    ->name('webhooks.acuity');

Route::post('/webhooks/hydreight', [WebhookController::class, 'hydreight'])
    ->name('webhooks.hydreight');
