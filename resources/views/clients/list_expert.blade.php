@extends('layout')

@section('top')
    <section class="py-5 bg-light-gray">
        <div class="container-fluid">
            <div class="d-flex justify-content-between flex-md-nowrap flex-wrap">
                <h2 class="fs-15 fw-bolder ">
                    Explore Profesional Expert
                </h2>
                <div class="d-flex align-items-center gap-6">
                    <a href="{{ route('home_client', $client->slug_page) }}"
                        class="text-muted fw-bolder link-primary fs-3 text-uppercase">
                        Home
                    </a>
                    <iconify-icon icon="solar:alt-arrow-right-outline" class="fs-5 text-muted"></iconify-icon>
                    <a href="#" class="text-primary link-primary fw-bolder fs-3 text-uppercase">
                        Expert
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('content')
    <section class="pt-5 pt-md-14 pb-4 pb-md-5">
        <div class="container-fluid">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h3 class="mb-3 mb-sm-0 fw-semibold d-flex align-items-center">Expert <span
                        class="badge text-bg-secondary fs-2 rounded-4 py-1 px-2 ms-2">{{ $expertise->experts_count }}</span>
                </h3>
                <form class="position-relative">
                    <input type="text" class="form-control search-chat py-2 ps-5" id="text-srh"
                        placeholder="Search Expert">
                    <i class="ti ti-search position-absolute top-50 start-0 translate-middle-y text-dark ms-3"></i>
                </form>
            </div>

            <div class="row">
                @foreach ($expertise->experts as $expert)
                    <div class="col-md-3">
                        <a href="{{ route('expert_detail', ['expert_id' => $expert->id]) }}?back={{ urlencode(request()->fullUrl()) }}" class="card mb-0 h-100">
                            <div class="card-body border-light p-3 text-center">
                                <div class="position-relative overflow-hidden d-inline-block">
                                    <img src="{{ urlpathSTORAGE($expert->user->picture) ?? asset('assets/images/profile/user-3.jpg')}}" alt="{{ $expert->user->name }}" class="img-fluid mb-4 rounded-circle position-relative" width="140">
                                    {{-- <span class="badge round-20 text-bg-danger fs-2 position-absolute top-0 end-0 d-flex align-items-center justify-content-center me-3 mt-2">1</span> --}}
                                </div>
                                <h5 class="fw-semibold fs-5 mb-2">{{ $expert->user->name }}</h5>
                                <p class="mb-0">{{ $expert->expertise }}</p>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection

@section('footer')
    @include('partials.footer')
@endsection

@section('script')
<script>
    Initials.init({
        nameAttribute: 'data-name',
        selector: '.avatar-img'
    });
</script>
@endsection
