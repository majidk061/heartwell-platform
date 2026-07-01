@props(['name' => 'avatar_type', 'multiName' => 'avatar_interests', 'cards' => null])

@php
    $avatarCards = $cards ?? array_values(config('heartwell.avatar_cards', []));
@endphp

<fieldset class="md:col-span-2 hw-avatar-selector">
    <legend class="hw-form-label">Which feels most like you? <span class="text-hw-muted font-normal">(optional)</span></legend>
    <p class="hw-avatar-selector__hint">Choose one primary option, or select all that resonate.</p>

    <div class="hw-avatar-selector__grid">
        @foreach($avatarCards as $card)
            @php
                $type = $card['type'] ?? '';
                $headline = $card['headline'] ?? '';
                $checkedSingle = old($name) === $type;
                $checkedMulti = in_array($type, old($multiName, []), true);
            @endphp
            <label @class(['hw-avatar-option', 'hw-avatar-option--selected' => $checkedSingle || $checkedMulti])>
                <input
                    type="radio"
                    name="{{ $name }}"
                    value="{{ $type }}"
                    class="sr-only"
                    @checked($checkedSingle)
                >
                <span class="hw-avatar-option__checkbox-wrap">
                    <input
                        type="checkbox"
                        name="{{ $multiName }}[]"
                        value="{{ $type }}"
                        class="hw-form-checkbox"
                        @checked($checkedMulti)
                    >
                </span>
                <span class="hw-avatar-option__label">{{ $headline }}</span>
            </label>
        @endforeach
    </div>
</fieldset>
