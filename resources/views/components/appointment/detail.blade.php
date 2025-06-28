@php
    $person = $isExpert ? $appointment->user : optional($appointment->expert)->user;
    $badgeClasses = [
        'need_confirmation' => 'text-bg-warning',
        'progress' => 'text-bg-primary',
        'payment' => 'text-bg-danger',
        'completed' => 'text-bg-success',
    ];
    $badgeClass = $badgeClasses[$appointment->status] ?? 'text-bg-secondary';
@endphp

<div class="chat-list chat {{ $active ? 'active-chat' : '' }}" data-user-id="{{ $index }}">
    <div class="hstack align-items-start mb-7 pb-1 align-items-center justify-content-between flex-wrap gap-6">
        <div class="d-flex align-items-center gap-2">
            <img src=""
                alt="{{ $person->name ?? 'User' }}" width="48" height="48" class="rounded-circle">
            <div>
                <h6 class="fw-semibold mb-0">{{ $person->name ?? '-' }}</h6>
                <p class="mb-0">{{ $person->email ?? '-' }}</p>
            </div>
        </div>
        <span class="badge {{ $badgeClass }}">
            {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
        </span>
    </div>
    <div class="border-bottom pb-7 mb-7">
        <p class="mb-3 text-dark">{{ $appointment->appointment }}</p>

        <div class="d-flex justify-content-end gap-2">
            @if ($isExpert)
                @if ($appointment->status == 'need_confirmation')
                    <form action="{{ route('appointment.update_status', $appointment->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="progress">
                        <button type="submit" class="btn btn-sm btn-primary">Confirm</button>
                    </form>
                    <form action="{{ route('appointment.update_status', $appointment->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="declined">
                        <button type="submit" class="btn btn-sm btn-danger">Decline</button>
                    </form>
                @elseif ($appointment->status == 'progress')
                    <form action="{{ route('appointment.update_status', $appointment->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="completed">
                        <button type="submit" class="btn btn-sm btn-success">Mark as Completed</button>
                    </form>
                @endif
            @else
                @if (in_array($appointment->status, ['need_confirmation', 'progress']))
                    <form action="{{ route('appointment.update_status', $appointment->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="canceled">
                        <button type="submit" class="btn btn-sm btn-warning">Cancel</button>
                    </form>
                @endif
            @endif
        </div>
    </div>
</div>
