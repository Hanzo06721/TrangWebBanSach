@php
    $type = $type ?? 'text';
    $id = $id ?? $name;
@endphp
<div class="mb-3">
    <label for="{{ $id }}" class="form-label">{{ $label }}</label>
    <input type="{{ $type }}" id="{{ $id }}" name="{{ $name }}"
           value="{{ $type === 'password' ? '' : old($name, $value ?? '') }}"
           class="form-control @error($name) is-invalid @enderror"
           @isset($autocomplete) autocomplete="{{ $autocomplete }}" @endisset
           @isset($placeholder) placeholder="{{ $placeholder }}" @endisset
           @if(!empty($disabled)) disabled @endif>
    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    @isset($hint)
        <div class="form-text">{{ $hint }}</div>
    @endisset
</div>
