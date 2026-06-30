<?php

namespace App\Http\Controllers\Web;

use App\Domains\Content\Actions\ShowMyVisitPageAction;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class MyVisitController extends Controller
{
    public function __invoke(ShowMyVisitPageAction $action): View
    {
        return view('pages.my-visit', $action->execute());
    }
}
