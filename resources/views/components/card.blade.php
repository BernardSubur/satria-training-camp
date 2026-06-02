<div {{ $attributes->merge(['class' => 'card border-0 shadow-sm rounded-4']) }}>
    @if(isset($header))
    <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
        {{ $header }}
    </div>
    @endif
    
    <div class="card-body p-4">
        {{ $slot }}
    </div>
    
    @if(isset($footer))
    <div class="card-footer bg-light border-top-0 rounded-bottom-4 px-4 py-3">
        {{ $footer }}
    </div>
    @endif
</div>
