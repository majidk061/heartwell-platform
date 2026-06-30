<?php

namespace App\Http\Controllers\Web;

use App\Domains\Content\Actions\ShowHomePageAction;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(ShowHomePageAction $action): View
    {
        return view('pages.show', $action->execute());
    }
}
