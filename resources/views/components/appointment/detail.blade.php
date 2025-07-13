@php
    // Ambil data person (konselor/user) berdasarkan peran
    $person = $isExpert ? $appointment->user : optional($appointment->expert)->user;
    $dt = \Carbon\Carbon::parse($appointment->date_time); // Parse tanggal dan waktu

    // Kelas badge untuk status
    $badgeClasses = [
        'need_confirmation' => 'bg-warning-subtle text-warning', // Lebih lembut
        'progress' => 'bg-primary-subtle text-primary', // Lebih lembut
        'payment' => 'bg-danger-subtle text-danger', // Lebih lembut
        'completed' => 'bg-success-subtle text-success', // Lebih lembut
        'canceled' => 'bg-dark-subtle text-dark', // Tambahan untuk canceled
    ];
    $badgeClass = $badgeClasses[$appointment->status] ?? 'bg-secondary-subtle text-secondary';

    // Mendapatkan URL avatar (contoh, sesuaikan dengan logic aplikasi Anda)
    $avatarUrl =
        $person->avatar_url ??
        'https://ui-avatars.com/api/?name=' .
            urlencode($person->name ?? 'N A') .
            '&background=random&color=fff&size=128';
@endphp

<div class="chat-list chat {{ $active ? 'active-chat' : '' }}" data-user-id="{{ $index }}">
    {{-- Header Detail Appointment --}}
    <div class="hstack align-items-start mb-7 pb-1 align-items-center justify-content-between flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
            <img src="{{ $avatarUrl }}" alt="{{ $person->name ?? 'User' }}" width="64" height="64"
                class="rounded-circle shadow-sm">
            <div>
                <h4 class="fw-semibold mb-0">{{ $person->name ?? '-' }}</h4>
                <p class="mb-0 text-muted fs-3">{{ $person->email ?? '-' }}</p>
            </div>
        </div>
        {{-- Status Badge di pojok kanan atas --}}
        <span class="badge {{ $badgeClass }} fs-3 py-2 px-3 rounded-pill">
            {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
        </span>
    </div>

    {{-- Detail Penting Appointment --}}
    <div class="appointment-details-section mb-7 border-bottom pb-7">
        <h3 class="fw-bold mb-4 text-dark">{{ $appointment->expert->expertise ?? 'Tanpa Topik' }}</h3>

        <div class="row g-3 mb-5">
            <div class="col-md-6 col-lg-4">
                <p class="mb-1 fs-2 text-muted">Tanggal</p>
                <h6 class="fw-semibold mb-0 fs-4 d-flex align-items-center gap-2">
                    <i class="ti ti-calendar fs-5 text-primary"></i>
                    <span>{{ $dt->translatedFormat('l, d F Y') }}</span>
                </h6>
            </div>
            <div class="col-md-6 col-lg-4">
                <p class="mb-1 fs-2 text-muted">Waktu</p>
                <h6 class="fw-semibold mb-0 fs-4 d-flex align-items-center gap-2">
                    <i class="ti ti-clock fs-5 text-primary"></i>
                    <span>{{ $dt->format('H:i') }} WIB</span>
                </h6>
            </div>
            @if (isset($appointment->hours))
                <div class="col-md-6 col-lg-4">
                    <p class="mb-1 fs-2 text-muted">Durasi</p>
                    <h6 class="fw-semibold mb-0 fs-4 d-flex align-items-center gap-2">
                        <i class="ti ti-hourglass fs-5 text-primary"></i>
                        <span>{{ $appointment->hours }} jam</span>
                    </h6>
                </div>
            @endif
            @if (isset($appointment->price))
                <div class="col-md-6 col-lg-4">
                    <p class="mb-1 fs-2 text-muted">Harga</p>
                    <h6 class="fw-semibold mb-0 fs-4 d-flex align-items-center gap-2">
                        <i class="ti ti-currency-dollar fs-5 text-primary"></i>
                        <span>Rp{{ number_format($appointment->price, 0, ',', '.') }}</span>
                    </h6>
                </div>
            @endif
        </div>

        {{-- Deskripsi Appointment / Catatan --}}
        <p class="mb-2 fs-3 text-muted">Detail Appointment</p>
        <p class="mb-3 text-dark fs-4">
            {{ $appointment->appointment ?? 'Tidak ada detail deskripsi yang diberikan.' }}
        </p>
    </div>

    {{-- Tombol Aksi --}}
    <div class="d-flex align-items-center gap-3 flex-wrap">
        @if ($isExpert)
            @if ($appointment->status == 'need_confirmation')
                <form action="{{ route('appointment.update_status', $appointment->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="progress">
                    <button type="submit"
                        class="btn btn-success py-2 px-4 rounded-pill d-flex align-items-center gap-1">
                        <i class="ti ti-check fs-5"></i> Confirm
                    </button>
                </form>
                <form action="{{ route('appointment.update_status', $appointment->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="declined">
                    <button type="submit"
                        class="btn btn-danger py-2 px-4 rounded-pill d-flex align-items-center gap-1">
                        <i class="ti ti-x fs-5"></i> Decline
                    </button>
                </form>
                <button type="button" class="btn btn-warning py-2 px-3 rounded-pill d-flex align-items-center gap-1"
                    data-bs-toggle="modal" data-bs-target="#editAppointmentModal{{ $appointment->id }}">
                    <i class="ti ti-pencil fs-5"></i> Edit
                </button>
                <div class="modal fade" id="editAppointmentModal{{ $appointment->id }}" tabindex="-1"
                    aria-labelledby="editAppointmentModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editAppointmentModalLabel">Edit Appointment Schedule</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            {{-- Form untuk mengirim data baru --}}
                            <form action="{{ route('appointment.edit_schedule', $appointment->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="mdate" class="form-label">New Date and Time</label>
                                        <div class="d-flex align-items-center gap-2 flex-nowrap">
                                            <input name="date" type="date" class="form-control"
                                                value="{{ $dt->format('Y-m-d') }}" required />
                                            <input name="time" type="time" class="form-control"
                                                value="{{ $dt->format('H:i') }}" required />
                                        </div>
                                        <p class="fs-2 mb-0 mt-1">Request new date and time</p>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @elseif ($appointment->status == 'progress')
                <form action="{{ route('appointment.update_status', $appointment->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="completed">
                    <button type="submit" class="btn btn-info py-2 px-4 rounded-pill d-flex align-items-center gap-1">
                        <i class="ti ti-medal fs-5"></i> Mark as Completed
                    </button>
                </form>
            @endif
        @endif
    </div>
</div>
