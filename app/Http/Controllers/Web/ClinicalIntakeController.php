<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ClinicalIntakeController extends Controller
{
    public function __invoke(): View
    {
        return view('pages.clinical-intake', [
            'portal_url' => config('integrations.hydreight.portal_url'),
            'enabled' => config('integrations.hydreight.enabled'),
            'note' => config('heartwell.compliance.hydreight_note'),
            'brand' => config('heartwell.brand'),
        ]);
    }
}
