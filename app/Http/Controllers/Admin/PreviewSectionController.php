<?php

namespace App\Http\Controllers\Admin;

use App\Domains\Content\Actions\PreviewSectionAction;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class PreviewSectionController extends Controller
{
    public function __invoke(int $template): View
    {
        abort_unless(auth()->check() && auth()->user()?->can('content.pages.view'), 403);

        $data = app(PreviewSectionAction::class)->execute($template);

        return view('admin.preview-section', $data);
    }
}
