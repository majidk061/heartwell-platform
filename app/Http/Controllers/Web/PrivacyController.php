<?php

namespace App\Http\Controllers\Web;

use App\Domains\Content\Actions\GetSiteSettingsAction;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class PrivacyController extends Controller
{
    public function __invoke(GetSiteSettingsAction $getSiteSettings): View
    {
        $siteSettings = $getSiteSettings->execute();

        return view('pages.privacy', [
            'siteSettings' => $siteSettings,
            'brand' => $siteSettings['brand'] ?? config('heartwell.brand'),
            'compliance' => $siteSettings['compliance'] ?? config('heartwell.compliance'),
        ]);
    }
}
