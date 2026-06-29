<?php

namespace App\Domains\Integrations\Exceptions;

use RuntimeException;

class MailNotConfiguredException extends RuntimeException
{
    public static function ambiguous(): self
    {
        return new self('Configure either Admin → Email / SMTP or Admin → Integrations → SendGrid — not both.');
    }

    public static function none(): self
    {
        return new self('Configure either Admin → Email / SMTP or Admin → Integrations → SendGrid before sending email.');
    }
}
