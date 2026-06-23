<?php

namespace App\Http\Controllers\Web;

use App\Domains\Content\Actions\ShowPageAction;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class PageController extends Controller
{
    public function show(string $slug, ShowPageAction $action): View
    {
        return view('pages.show', $action->execute($slug));
    }
}
