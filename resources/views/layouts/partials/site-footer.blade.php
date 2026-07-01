@php
    use App\Domains\Content\Models\SupportPathway;

    $footer = $siteSettings['footer'] ?? [];
    $socialLinks = $siteSettings['social'] ?? [];
    $footerColumns = $siteSettings['footer_columns'] ?? config('heartwell.footer_columns', []);
    $footerTagline = $siteSettings['brand']['footer_tagline'] ?? config('heartwell.brand.footer_tagline');
    $brandName = $siteSettings['brand']['name'] ?? config('heartwell.brand.name');
    $complianceNote = $siteSettings['compliance']['footer_note'] ?? config('heartwell.compliance.footer_note');
    $pathways = SupportPathway::query()->published()->orderBy('sort_order')->get();

    $resolveFooterHref = function (array $link): string {
        if (! empty($link['url'])) {
            return str_starts_with($link['url'], 'http') ? $link['url'] : url($link['url']);
        }

        if (! empty($link['route']) && \Illuminate\Support\Facades\Route::has($link['route'])) {
            $href = route($link['route']);

            if (! empty($link['anchor'])) {
                $href .= str_starts_with($link['anchor'], '#') ? $link['anchor'] : '#'.$link['anchor'];
            }

            return $href;
        }

        return '#';
    };
@endphp

<footer class="hw-site-footer mt-auto">
    <div class="hw-container-wide hw-site-footer__inner">
        <div class="hw-site-footer__grid">
            <div class="hw-site-footer__brand">
                <x-site-logo variant="dark" context="footer" :show-tagline="false" class="hw-site-footer__logo" />
                @if($footerTagline)
                    <p class="hw-site-footer__tagline">{{ $footerTagline }}</p>
                @endif

                <div class="hw-site-footer__connect">
                    <p class="hw-site-footer__heading">Stay Connected</p>
                    @if(! empty($socialLinks))
                        <div class="hw-site-footer__social">
                            @foreach($socialLinks as $link)
                                @if(! empty($link['url']))
                                    <a
                                        href="{{ $link['url'] }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="hw-site-footer__social-link"
                                        aria-label="{{ ucfirst($link['platform'] ?? 'Social') }}"
                                    >
                                        @switch($link['platform'] ?? '')
                                            @case('instagram')
                                                <svg class="w-[1.125rem] h-[1.125rem]" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                                                @break
                                            @case('facebook')
                                                <svg class="w-[1.125rem] h-[1.125rem]" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                                @break
                                            @default
                                                <span class="text-xs uppercase">{{ $link['platform'] ?? 'Link' }}</span>
                                        @endswitch
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    @endif
                    <div class="hw-site-footer__contact">
                        @if(! empty($footer['phone']))
                            <a href="tel:{{ preg_replace('/[^\d+]/', '', $footer['phone']) }}">{{ $footer['phone'] }}</a>
                        @endif
                        @if(! empty($footer['email']))
                            <a href="mailto:{{ $footer['email'] }}">{{ $footer['email'] }}</a>
                        @endif
                    </div>
                </div>
            </div>

            @if($pathways->isNotEmpty())
                <div class="hw-site-footer__column">
                    <p class="hw-site-footer__heading">Support Options</p>
                    <ul class="hw-site-footer__links">
                        @foreach($pathways as $pathway)
                            <li>
                                <a href="{{ route('support-pathways') }}#{{ $pathway->slug }}">
                                    {{ preg_replace('/\s+Support$/', '', $pathway->title) }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @foreach($footerColumns as $column)
                <div class="hw-site-footer__column">
                    <p class="hw-site-footer__heading">{{ $column['title'] ?? '' }}</p>
                    <ul class="hw-site-footer__links">
                        @foreach($column['links'] ?? [] as $link)
                            <li>
                                <a href="{{ $resolveFooterHref($link) }}">{{ $link['label'] ?? '' }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>

        <div class="hw-site-footer__bottom">
            <p class="hw-site-footer__copyright">&copy; {{ date('Y') }} {{ $brandName }}. All rights reserved.</p>
            @if($complianceNote)
                <p class="hw-site-footer__compliance">{{ $complianceNote }}</p>
            @endif
        </div>
    </div>
</footer>
