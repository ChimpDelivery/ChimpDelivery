@props(['value', 'icon'])
<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-gray-200']) }}>
    @isset($icon)
    <i class="fa {{ $icon }}" aria-hidden="true"></i>
    @endisset
    {{ $value ?? $slot }}
</label>
