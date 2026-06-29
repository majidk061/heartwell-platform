<x-filament-panels::page>
    <form wire:submit="save">
        {{ $this->form }}
        <div class="mt-6 flex flex-wrap gap-3">
            <x-filament::button type="submit">Save settings</x-filament::button>
            <x-filament::button type="button" color="gray" wire:click="testEmail">Send test email</x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
