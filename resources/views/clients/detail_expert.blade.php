@extends('layout')

@section('top')
    <section class="pt-5 bg-light-gray">
        <div class="container-fluid">
            <div class="text-center">
                <div class="d-flex align-items-center justify-content-center gap-6">
                    <a href="{{ request('back') }}" class="text-muted fw-bolder link-primary fs-3 text-uppercase">
                        List Profesional
                    </a>
                    <iconify-icon icon="solar:alt-arrow-right-outline" class="fs-5 text-muted"></iconify-icon>
                    <a href="#" class="text-primary link-primary fw-bolder fs-3 text-uppercase">
                        {{ $expert->user->name }}
                    </a>
                </div>
            </div>
            <div class="mt-5 d-lg-block d-none">
                <img src="{{ urlpathSTORAGE($expert->background) ?? asset('image/banner-detail-expert.jpg') }}" alt="{{ $expert->user->name }}" class="rounded-3 img-fluid h-25">
            </div>
        </div>
    </section>
@endsection

@section('content')
    <section class="pt-3 pt-md-13 pb-md-11 bg-light-gray">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-4 mb-7 mb-lg-0">
                    <div class="d-flex flex-column gap-2 bg-white p-7 rounded-3">
                        <div class="d-flex gap-3 pb-3 border-bottom ">
                            <div>
                                <img src="{{ urlpathSTORAGE($expert->user->picture) ?? asset('assets/images/profile/user-3.jpg') }}" alt="user"
                                    class="rounded-circle" width="44px" height="44px">
                            </div>
                            <div class="">
                                <p class="mb-0 text-dark fs-4 fw-semibold">{{ $expert->user->name }}</p>
                                <p class="mb-0 fs-3 fw-bold">{{ $expert->expertise }}</p>
                            </div>
                        </div>
                        <div class="py-9 d-flex flex-column gap-2 border-bottom">
                            <a href="#biography" class="text-dark fs-3 fw-semibold link-primary">Biography</a>
                            <a href="#experience" class="text-dark fs-3 fw-semibold link-primary">Experience</a>
                            <a href="#licenced" class="text-dark fs-3 fw-semibold link-primary">Licence Expert</a>
                        </div>
                        <a href="{{ route('appointment', $expert->id) }}" class="btn bg-primary-subtle text-primary mb-3 w-100">Make
                            Appointment</a>
                        <div class="pt-9">
                            <h4 class="text-uppercase fs-3 fw-bold">Social Media</h4>
                            <div class="d-flex gap-6">
                                <a href="#" class="border rounded-circle round-40 hstack justify-content-center"
                                    data-bs-toggle="tooltip" data-bs-title="Facebook">
                                    <img src="../assets/images/frontend-pages/icon-facebook-dark.svg" alt="facebook">
                                </a>
                                <a href="#" class="border rounded-circle round-40 hstack justify-content-center"
                                    data-bs-toggle="tooltip" data-bs-title="Instagram">
                                    <img src="../assets/images/frontend-pages/icon-instagram-dark.svg" alt="instagram">
                                </a>
                                <a href="#" class="border rounded-circle round-40 hstack justify-content-center"
                                    data-bs-toggle="tooltip" data-bs-title="YouTube">
                                    <img src="../assets/images/frontend-pages/icon-youtube-dark.svg" alt="youtube">
                                </a>
                                <a href="#" class="border rounded-circle round-40 hstack justify-content-center"
                                    data-bs-toggle="tooltip" data-bs-title="Linckedin">
                                    <img src="../assets/images/frontend-pages/icon-linckedin-dark.svg" alt="linckedin">
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card p-7 mt-3">
                        <h4 class="fw-semibold mb-3">Galery Photos</h4>
                        <div class="row">
                            @foreach ($expert->gallerys as $gallery)
                                <div class="col-3">
                                    <img src="{{ urlpathSTORAGE($gallery['photos']) }}" alt="matdash-img"
                                        class="rounded-1 img-fluid mb-9">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="d-flex flex-column gap-sm-4 gap-3 bg-white rounded-3 p-7">
                        <div class="" id="biography">
                            <h3 class="fs-7 fw-bolder mb-sm-4 mb-3">Biography</h3>
                            <p class="card-subtitle">
                                {!! $expert->biography !!}
                            </p>
                        </div>
                        <div class="" id="experience">
                            <h3 class="fs-7 fw-bolder mb-sm-4 mb-3">
                                Experience
                            </h3>
                            @foreach ($expert->experiences as $experience)
                                <div class="mb-3">
                                    <h5>{{ $experience['position'] ?? '-' }}</h5>
                                    <p class="mb-2"><strong>Organization:</strong> {{ $experience['company'] ?? '-' }} <strong class="ms-2">Years:</strong> {{ $experience['years'] ?? '-' }}</p>
                                    <p>
                                        {{ $experience['description'] ?? '-' }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                        <div class="" id="expertise">
                            <h3 class="fs-7 fw-bolder mb-sm-4 mb-3">
                                License
                            </h3>
                            <div class="row g-3">
                                @foreach ($expert->licenses as $license)
                                <div class="col-12 col-md-6">
                                    <div class="d-flex align-items-center justify-content-between pb-6 border-bottom">
                                        <div>
                                            <span class="text-muted fw-medium">{{ $license['certification'] }}</span>
                                        </div>
                                        <div class="text-end">
                                            <a class="text-dark px-2 fs-5 bg-hover-primary nav-icon-hover" href="">
                                                <i class="ti ti-file"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('footer')
    @include('partials.footer')
@endsection
