@php
    $type = $type ?? 'text';
    $id = $id ?? $name;
@endphp
<div class="mb-3">
    <label for="{{ $id }}" class="form-label">{{ $label }}</label>
    @if($type === 'password')
        <div class="input-group">
            <input type="{{ $type }}" id="{{ $id }}" name="{{ $name }}"
                   value=""
                   class="form-control @error($name) is-invalid @enderror"
                   @isset($autocomplete) autocomplete="{{ $autocomplete }}" @endisset
                   @isset($placeholder) placeholder="{{ $placeholder }}" @endisset
                   @if(!empty($disabled)) disabled @endif>
            <button type="button" 
                    class="btn btn-outline-secondary btn-toggle-password" 
                    data-target="{{ $id }}" 
                    tabindex="-1"
                    title="Hiện/Ẩn mật khẩu">
                <span class="eye-icon">👁️</span>
            </button>
            @error($name)
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    @else
        <input type="{{ $type }}" id="{{ $id }}" name="{{ $name }}"
               value="{{ old($name, $value ?? '') }}"
               class="form-control @error($name) is-invalid @enderror"
               @isset($autocomplete) autocomplete="{{ $autocomplete }}" @endisset
               @isset($placeholder) placeholder="{{ $placeholder }}" @endisset
               @if(!empty($disabled)) disabled @endif>
        @error($name)
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    @endif
    @isset($hint)
        <div class="form-text">{{ $hint }}</div>
    @endisset
</div>
