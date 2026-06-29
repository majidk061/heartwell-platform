<?php

namespace App\Domains\Integrations\Actions;

use App\Domains\Content\Actions\GetSiteSettingsAction;
use App\Domains\Integrations\Models\EmailTemplate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;

class SendTemplatedEmailAction
{
    public function __construct(
        private readonly GetSiteSettingsAction $getSiteSettings,
    ) {}

    /**
     * @param  array<string, mixed>  $mergeData
     */
    public function execute(string $templateKey, string $toEmail, array $mergeData = []): bool
    {
        $template = EmailTemplate::query()->where('key', $templateKey)->first();

        if (! $template || ! $template->is_enabled) {
            return false;
        }

        $settings = $this->getSiteSettings->execute();
        $logoPath = $template->logo_path ?: ($settings['branding']['logo_image_path'] ?? null);

        $subject = $this->replaceMergeTags($template->subject, $mergeData);
        $heading = $this->replaceMergeTags($template->heading ?? '', $mergeData);
        $body = $this->replaceMergeTags($template->body ?? '', $mergeData);
        $buttonLabel = $template->button_label ? $this->replaceMergeTags($template->button_label, $mergeData) : null;
        $buttonUrl = $template->button_url ? $this->replaceMergeTags($template->button_url, $mergeData) : null;
        $footerText = $template->footer_text ? $this->replaceMergeTags($template->footer_text, $mergeData) : null;

        $html = View::make('emails.heartwell', [
            'logoUrl' => $logoPath ? asset('storage/'.$logoPath) : null,
            'heading' => $heading,
            'body' => $body,
            'buttonLabel' => $buttonLabel,
            'buttonUrl' => $buttonUrl,
            'footerText' => $footerText,
        ])->render();

        Mail::html($html, function ($message) use ($toEmail, $subject) {
            $message->to($toEmail)->subject($subject);
        });

        return true;
    }

    /**
     * @param  array<string, mixed>  $mergeData
     */
    public function replaceMergeTags(string $content, array $mergeData): string
    {
        foreach ($mergeData as $key => $value) {
            if (is_scalar($value) || $value === null) {
                $content = str_replace('{{'.$key.'}}', (string) ($value ?? ''), $content);
            }
        }

        return $content;
    }
}
