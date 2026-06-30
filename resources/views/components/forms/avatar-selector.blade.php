@props(['name' => 'avatar_type', 'multiName' => 'avatar_interests', 'cards' => null])

@php
    $avatarCards = $cards ?? array_values(config('heartwell.avatar_cards', []));
@endphp

<fieldset class="md:col-span-2 space-y-3">
    <legend class="hw-form-label">Which feels most like you? <span class="text-hw-muted font-normal">(optional)</span></legend>
    <p class="text-sm text-hw-muted">You may choose one primary option below, or select all that resonate.</p>

    <div class="grid gap-3 sm:grid-cols-3">
        @foreach($avatarCards as $card)
            @php
                $type = $card['type'] ?? '';
                $headline = $card['headline'] ?? '';
                $checkedSingle = old($name) === $type;
                $checkedMulti = in_array($type, old($multiName, []), true);
            @endphp
            <label class="relative flex flex-col rounded-lg border border-hw-border bg-hw-white p-4 cursor-pointer hover:border-hw-dusty-blue transition-colors has-[:checked]:border-hw-dusty-blue has-[:checked]:ring-2 has-[:checked]:ring-hw-dusty-blue/30">
                <input
                    type="radio"
                    name="{{ $name }}"
                    value="{{ $type }}"
                    class="sr-only"
                    @checked($checkedSingle)
                >
                <input
                    type="checkbox"
                    name="{{ $multiName }}[]"
                    value="{{ $type }}"
                    class="absolute top-3 right-3 h-4 w-4 rounded border-hw-border text-hw-dusty-blue focus:ring-hw-dusty-blue"
                    @checked($checkedMulti)
                >
                <span class="font-heading text-sm text-hw-heading pr-6">{{ $headline }}</span>
            </label>
        @endforeach
    </div>
</fieldset>
