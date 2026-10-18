@php($size = $size ?? 40)
@if($user->avatar_url)
    <img src="{{ $user->avatar_url }}" alt="Ảnh đại diện của {{ $user->FullName }}" class="avatar"
         style="width: {{ $size }}px; height: {{ $size }}px;">
@else
    <span class="avatar" style="width: {{ $size }}px; height: {{ $size }}px; font-size: {{ round($size * 0.42) }}px;"
          aria-hidden="true">{{ $user->initial }}</span>
@endif
