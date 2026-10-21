@props(['status'])

<span {{ $attributes->merge(['class' => 'inline-flex rounded-full px-2 py-0.5 text-xs font-semibold '.$status->badgeClasses()]) }}>{{ $status->label() }}</span>
