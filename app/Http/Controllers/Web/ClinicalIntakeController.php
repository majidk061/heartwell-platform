<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ClinicalIntakeController extends Controller
{
    public function __invoke(): View
    {
        $portalUrl = config('integrations.hydreight.portal_url');
        $portalEnabled = (bool) config('integrations.hydreight.enabled') && filled($portalUrl);

        return view('pages.clinical-intake', [
            'portalUrl' => $portalUrl,
            'portalEnabled' => $portalEnabled,
            'note' => config('heartwell.compliance.clinical_portal_note'),
            'brand' => config('heartwell.brand'),
        ]);
    }
}
