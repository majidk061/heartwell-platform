<?php

namespace App\Http\Controllers\Web;

use App\Domains\Content\Actions\ShowPageAction;
use App\Domains\Content\Support\ClientCopyCatalog;
use App\Domains\CRM\Actions\CreateConsultationRequestAction;
use App\Domains\CRM\Actions\CreateGroupInquiryAction;
use App\Domains\CRM\Actions\CreateWaitlistEntryAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\CRM\ConsultationStoreRequest;
use App\Http\Requests\CRM\GroupInquiryStoreRequest;
use App\Http\Requests\CRM\WaitlistStoreRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function show(ShowPageAction $action): View
    {
        return view('pages.show', $action->execute('contact'));
    }

    public function storeWaitlist(
        WaitlistStoreRequest $request,
        CreateWaitlistEntryAction $action,
    ): RedirectResponse {
        $action->execute($request->validated());

        return back()->with('success', ClientCopyCatalog::FORM_THANK_YOU);
    }

    public function storeConsultation(
        ConsultationStoreRequest $request,
        CreateConsultationRequestAction $action,
    ): RedirectResponse {
        $action->execute($request->validated());

        return back()->with('success', ClientCopyCatalog::FORM_THANK_YOU);
    }

    public function storeGroupInquiry(
        GroupInquiryStoreRequest $request,
        CreateGroupInquiryAction $action,
    ): RedirectResponse {
        $action->execute($request->validated());

        return back()->with('success', ClientCopyCatalog::FORM_THANK_YOU);
    }
}
