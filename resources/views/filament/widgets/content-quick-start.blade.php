<x-filament-widgets::widget>
    <x-filament::section heading="Quick start — edit your website" description="Follow these steps to update content without developer help.">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @foreach ($this->getSteps() as $step)
                <a href="{{ $step['url'] }}"
                   @if(str_contains($step['url'], url('/'))) target="_blank" rel="noopener" @endif
                   class="flex gap-3 p-4 rounded-lg border border-gray-200 hover:border-primary-300 hover:bg-primary-50/50 transition">
                    <x-filament::icon :icon="$step['icon']" class="h-6 w-6 text-primary-600 shrink-0" />
                    <div>
                        <p class="font-semibold text-gray-900">{{ $step['label'] }}</p>
                        <p class="text-sm text-gray-600 mt-0.5">{{ $step['description'] }}</p>
                    </div>
                </a>
            @endforeach
        </div>
        <p class="mt-4 text-sm text-gray-600">
            <a href="{{ \App\Filament\Pages\AdminGuide::getUrl() }}" class="text-primary-600 hover:underline font-medium">Need help?</a>
            — step-by-step guide for editing pages, sections, and team settings.
        </p>
    </x-filament::section>
</x-filament-widgets::widget>
