<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $heading ?? 'HeartWell' }}</title>
</head>
<body style="margin:0;padding:0;background:#ffffff;font-family:'Source Sans 3',Arial,sans-serif;color:#334155;">
<table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="background:#ffffff;padding:32px 16px;">
    <tr>
        <td align="center">
            <table width="600" cellpadding="0" cellspacing="0" role="presentation" style="max-width:600px;width:100%;">
                @if(!empty($logoUrl))
                <tr>
                    <td align="center" style="padding-bottom:24px;">
                        <img src="{{ $logoUrl }}" alt="HeartWell" style="max-height:48px;width:auto;">
                    </td>
                </tr>
                @endif
                @if(!empty($heading))
                <tr>
                    <td style="padding-bottom:16px;">
                        <h1 style="margin:0;font-size:24px;line-height:1.3;color:#1e293b;font-weight:600;">{{ $heading }}</h1>
                    </td>
                </tr>
                @endif
                @if(!empty($body))
                <tr>
                    <td style="padding-bottom:24px;font-size:16px;line-height:1.6;color:#475569;">
                        {!! $body !!}
                    </td>
                </tr>
                @endif
                @if(!empty($buttonLabel) && !empty($buttonUrl))
                <tr>
                    <td style="padding-bottom:24px;">
                        <a href="{{ $buttonUrl }}" style="display:inline-block;background:#7ba7bc;color:#ffffff;text-decoration:none;padding:12px 24px;border-radius:9999px;font-weight:600;">{{ $buttonLabel }}</a>
                    </td>
                </tr>
                @endif
                @if(!empty($footerText))
                <tr>
                    <td style="padding-top:24px;border-top:1px solid #e2e8f0;font-size:12px;line-height:1.5;color:#94a3b8;">
                        {!! $footerText !!}
                    </td>
                </tr>
                @endif
            </table>
        </td>
    </tr>
</table>
</body>
</html>
