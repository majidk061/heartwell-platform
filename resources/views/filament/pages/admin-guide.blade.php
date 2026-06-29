<x-filament-panels::page>
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <aside class="lg:col-span-3">
            <nav class="lg:sticky lg:top-24 space-y-1 rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-3">On this page</p>
                @foreach ($this->getSections() as $section)
                    <a href="#{{ $section['id'] }}"
                       class="block rounded-lg px-3 py-2 text-sm text-gray-600 hover:bg-primary-50 hover:text-primary-700 transition">
                        {{ $section['title'] }}
                    </a>
                @endforeach
            </nav>
        </aside>

        <div class="lg:col-span-9 space-y-8">
            @foreach ($this->getSections() as $index => $section)
                <section id="{{ $section['id'] }}" class="scroll-mt-24 rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden">
                    <div class="flex items-start gap-4 p-6 border-b border-gray-100 bg-gray-50/50">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-primary-100 text-primary-700">
                            <x-filament::icon :icon="$section['icon']" class="h-5 w-5" />
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-primary-600 mb-1">Step {{ $index + 1 }}</p>
                            <h2 class="text-xl font-semibold text-gray-900">{{ $section['title'] }}</h2>
                        </div>
                        @if(! empty($section['url']))
                            <a href="{{ $section['url'] }}"
                               class="shrink-0 inline-flex items-center gap-1 text-sm font-medium text-primary-600 hover:text-primary-800">
                                Open
                                <x-filament::icon icon="heroicon-m-arrow-top-right-on-square" class="h-4 w-4" />
                            </a>
                        @endif
                    </div>
                    <ol class="p-6 space-y-3 list-decimal list-inside text-gray-700 text-sm leading-relaxed">
                        @foreach ($section['steps'] as $step)
                            <li class="pl-1">{{ $step }}</li>
                        @endforeach
                    </ol>
                </section>
            @endforeach
        </div>
    </div>
</x-filament-panels::page>
