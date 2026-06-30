@php
    use App\Domains\Content\Support\CmsImage;
    use Illuminate\Support\Str;

    $user = filament()->auth()->user();
    $avatarUrl = CmsImage::url($user->avatar_path);
    $initials = collect(explode(' ', (string) $user->name))
        ->filter()
        ->map(fn (string $part): string => Str::substr($part, 0, 1))
        ->take(2)
        ->implode('');
@endphp

<x-filament-panels::page>
    <div class="hw-profile-shell mx-auto w-full max-w-6xl">
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-[17rem_minmax(0,1fr)] xl:grid-cols-[19rem_minmax(0,1fr)]">
            {{-- Left sidebar — profile identity --}}
            <aside class="hw-profile-sidebar">
                <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
                    <div class="hw-profile-sidebar-cover h-24 w-full bg-gradient-to-br from-[#e8b4b8] via-[#7ba7bc] to-[#1b2b4b]"></div>

                    <div class="relative px-5 pb-6 pt-0 text-center">
                        <div class="hw-profile-sidebar-avatar -mt-12 mx-auto">
                            @if ($avatarUrl)
                                <img
                                    src="{{ $avatarUrl }}"
                                    alt="{{ $user->name }}"
                                    class="hw-profile-avatar-image"
                                />
                            @else
                                <div class="hw-profile-avatar-fallback" aria-hidden="true">
                                    {{ $initials ?: 'HW' }}
                                </div>
                            @endif
                        </div>

                        <h2 class="mt-4 truncate text-lg font-semibold text-gray-900">
                            {{ $user->name }}
                        </h2>
                        <p class="mt-1 truncate text-sm text-gray-500">
                            {{ $user->email }}
                        </p>
                        <p class="mt-3 inline-flex rounded-full bg-[#e8f0f4] px-3 py-1 text-xs font-semibold uppercase tracking-wide text-[#1b2b4b]">
                            HeartWell Admin
                        </p>
                    </div>
                </div>

                <nav class="mt-4 space-y-1 rounded-2xl border border-gray-200 bg-white p-2 shadow-sm" aria-label="Profile sections">
                    <button
                        type="button"
                        wire:click="setActiveTab('personal')"
                        @class([
                            'hw-profile-tab-button w-full',
                            'hw-profile-tab-button-active' => $activeTab === 'personal',
                        ])
                    >
                        <x-filament::icon icon="heroicon-o-user-circle" class="h-5 w-5 shrink-0" />
                        <span>Personal details</span>
                    </button>

                    <button
                        type="button"
                        wire:click="setActiveTab('security')"
                        @class([
                            'hw-profile-tab-button w-full',
                            'hw-profile-tab-button-active' => $activeTab === 'security',
                        ])
                    >
                        <x-filament::icon icon="heroicon-o-lock-closed" class="h-5 w-5 shrink-0" />
                        <span>Security</span>
                    </button>
                </nav>
            </aside>

            {{-- Right panel — tab content --}}
            <div class="min-w-0">
                @if ($activeTab === 'personal')
                    <div class="hw-profile-panel">
                        <div class="hw-profile-panel-header">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Personal details</h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    Update your photo, name, and email address.
                                </p>
                            </div>
                        </div>

                        <form wire:submit="savePersonal" class="hw-profile-panel-body">
                            {{ $this->personalForm }}

                            <div class="hw-profile-panel-actions">
                                <x-filament::button
                                    type="button"
                                    color="gray"
                                    wire:click="cancelPersonal"
                                >
                                    Cancel
                                </x-filament::button>

                                <x-filament::button type="submit" icon="heroicon-o-check">
                                    Save personal details
                                </x-filament::button>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="hw-profile-panel">
                        <div class="hw-profile-panel-header">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Security</h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    Change your password. All three fields are required when updating.
                                </p>
                            </div>
                        </div>

                        <form wire:submit="saveSecurity" class="hw-profile-panel-body">
                            {{ $this->securityForm }}

                            <div class="hw-profile-panel-actions">
                                <x-filament::button
                                    type="button"
                                    color="gray"
                                    wire:click="cancelSecurity"
                                >
                                    Cancel
                                </x-filament::button>

                                <x-filament::button type="submit" icon="heroicon-o-check">
                                    Save password
                                </x-filament::button>
                            </div>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-filament-panels::page>
