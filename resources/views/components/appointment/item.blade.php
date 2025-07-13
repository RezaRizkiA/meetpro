@php
    $person = $isExpert ? $appointment->user : optional($appointment->expert)->user;
    $dt = \Carbon\Carbon::parse($appointment->date_time);
    $badgeClasses = [
        'need_confirmation' => 'text-bg-warning',
        'progress' => 'text-bg-primary',
        'payment' => 'text-bg-danger',
        'completed' => 'text-bg-success',
    ];
    $badgeClass = $badgeClasses[$appointment->status] ?? 'text-bg-secondary';
@endphp

<li>
    <a href="javascript:void(0)"
        class="px-4 py-3 bg-hover-light-black d-flex align-items-start justify-content-between chat-user {{ $active ? 'bg-light-subtle' : '' }}"
        id="chat_user_{{ $index }}" data-user-id="{{ $index }}">
        <div class="position-relative w-100 ms-2">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <h6 class="mb-0">{{ $person->name ?? '-' }}</h6>
            </div>
            <h6 class="fw-normal text-muted">{{ \Illuminate\Support\Str::limit($appointment->appointment, 50) }}</h6>
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <p class="mb-0 fs-2 text-muted">{{ $dt->format('d M Y') }}</p>
                </div>
                <p class="mb-0 fs-2 text-muted">{{ $dt->format('H:i') }}</p>
            </div>
        </div>
    </a>
</li>
