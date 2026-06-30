<x-filament-panels::page>
    <form wire:submit="save" class="space-y-6">
        {{ $this->form }}

        <div class="fi-form-actions flex flex-wrap justify-end gap-3">
            <x-filament::button type="submit" icon="heroicon-o-check">
                Save settings
            </x-filament::button>
            <x-filament::button type="button" color="gray" wire:click="testEmail">
                Send test email
            </x-filament::button>
            <x-filament::button type="button" color="gray" wire:click="testAllTemplates">
                Send all template tests
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
