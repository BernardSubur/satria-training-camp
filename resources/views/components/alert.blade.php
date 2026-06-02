@props(['type' => 'success'])

@php
    $classes = match($type) {
        'success' => 'alert alert-success border-0 bg-success bg-opacity-10 text-success d-flex align-items-center',
        'error' => 'alert alert-danger border-0 bg-danger bg-opacity-10 text-danger d-flex align-items-center',
        'warning' => 'alert alert-warning border-0 bg-warning bg-opacity-10 text-warning d-flex align-items-center',
        'info' => 'alert alert-info border-0 bg-info bg-opacity-10 text-info d-flex align-items-center',
        default => 'alert alert-secondary border-0 bg-secondary bg-opacity-10 text-secondary d-flex align-items-center',
    };

    $icon = match($type) {
        'success' => 'bi-check-circle-fill',
        'error' => 'bi-exclamation-triangle-fill',
        'warning' => 'bi-exclamation-circle-fill',
        'info' => 'bi-info-circle-fill',
        default => 'bi-bell-fill',
    };
@endphp

<div {{ $attributes->merge(['class' => $classes]) }} role="alert">
    <i class="bi {{ $icon }} fs-5 me-3"></i>
    <div>
        {{ $slot }}
    </div>
    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
