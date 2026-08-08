@php
    $avatarUser = $user ?? auth()->user();
    $avatarSize = $size ?? 32;
    $avatarPath = $avatarUser?->profile?->avatar;
    $isOnline   = $online ?? false;
    $initials   = $avatarUser
        ? mb_strtoupper(mb_substr($avatarUser->first_name, 0, 1) . mb_substr($avatarUser->last_name, 0, 1))
        : '?';
    $dotSize    = max(8, intval($avatarSize * 0.28));
@endphp

<span style="position:relative; display:inline-block; width:{{ $avatarSize }}px; height:{{ $avatarSize }}px; flex-shrink:0;">

    @if($avatarPath)
        <img
            src="{{ asset('storage/' . $avatarPath) }}"
            alt="{{ $avatarUser->first_name }} {{ $avatarUser->last_name }}"
            class="rounded-circle object-fit-cover"
            style="width:{{ $avatarSize }}px; height:{{ $avatarSize }}px;
                   border: 2px solid {{ $isOnline ? '#22c55e' : 'rgba(255,255,255,0.25)' }};
                   box-shadow: {{ $isOnline ? '0 0 0 2px rgba(34,197,94,0.35)' : 'none' }};
                   display:block;"
        >
    @else
        <span
            class="rounded-circle d-inline-flex align-items-center justify-content-center bg-secondary text-white"
            style="width:{{ $avatarSize }}px; height:{{ $avatarSize }}px;
                   font-size:{{ $avatarSize * 0.4 }}px; line-height:1; display:block;
                   border: 2px solid {{ $isOnline ? '#22c55e' : 'transparent' }};
                   box-shadow: {{ $isOnline ? '0 0 0 2px rgba(34,197,94,0.35)' : 'none' }};"
        >{{ $initials }}</span>
    @endif

    @if($isOnline)
        {{-- Point vert "en ligne" en bas à droite --}}
        <span style="
            position: absolute;
            bottom: 0; right: 0;
            width: {{ $dotSize }}px; height: {{ $dotSize }}px;
            background: #22c55e;
            border-radius: 50%;
            border: 2px solid #1a1a2e;
            box-shadow: 0 0 4px rgba(34,197,94,0.7);
        "></span>
    @endif

</span>
