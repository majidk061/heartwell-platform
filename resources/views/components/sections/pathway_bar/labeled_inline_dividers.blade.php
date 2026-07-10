@props(['pathways', 'barHeading' => 'Support Pathways Include:'])

<section class="hw-pathway-bar hw-pathway-bar--client" aria-label="{{ strip_tags($barHeading) }}">
    <div class="hw-container-wide hw-pathway-bar__inner">
        @if($barHeading)
            <p class="hw-pathway-bar__heading">{{ $barHeading }}</p>
        @endif
        <div class="hw-pathway-bar__scroll">
            <div class="hw-pathway-bar__track">
                @foreach($pathways as $pathway)
                    @if(! $loop->first)
                        <span class="hw-pathway-bar__divider" aria-hidden="true"></span>
                    @endif
                    <a href="{{ route('support-pathways') }}#{{ $pathway->slug }}"
                       class="hw-pathway-bar__link">
                        {{ $pathway->displayTitle(compact: true) }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</section>
