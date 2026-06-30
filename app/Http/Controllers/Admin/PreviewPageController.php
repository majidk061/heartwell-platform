<?php

namespace App\Http\Controllers\Admin;

use App\Domains\Content\Actions\PreviewPageAction;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class PreviewPageController extends Controller
{
    public function __invoke(string $slug): View
    {
        abort_unless(auth()->check() && auth()->user()?->can('content.pages.view'), 403);

        $data = app(PreviewPageAction::class)->execute($slug);

        if ($slug === 'home') {
            return view('pages.home', $data);
        }

        return view('pages.show', $data);
    }
}
