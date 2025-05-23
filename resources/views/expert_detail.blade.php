@extends('layout')

@section('top')
<section class="pt-5 bg-light-gray">
      <div class="container-fluid">
        <div class="text-center">
          <div class="d-flex align-items-center justify-content-center gap-6">
            <a href="{{route('expert')}}" class="text-muted fw-bolder link-primary fs-3 text-uppercase">
              List Profesional
            </a>
            <iconify-icon icon="solar:alt-arrow-right-outline" class="fs-5 text-muted"></iconify-icon>
            <a href="#" class="text-primary link-primary fw-bolder fs-3 text-uppercase">
              Kirdi Putra
            </a>
          </div>
        </div>
        <div class="mt-5 d-lg-block d-none">
          <img src="{{asset('image/banner-detail-expert.jpg')}}" alt="blog detail banner" class="rounded-3 img-fluid h-25">
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
                  <img src="{{asset('image/kirdi-putra.jpeg')}}" alt="user" class="rounded-circle" width="44px" height="44px">
                </div>
                <div class="">
                  <p class="mb-0 text-dark fs-4 fw-semibold">Kirdi Putra</p>
                  <p class="mb-0 fs-3 fw-bold">Microexpression expert</p>
                </div>
              </div>
              <div class="py-9 d-flex flex-column gap-2 border-bottom">
                <a href="#biography" class="text-dark fs-3 fw-semibold link-primary">Biography</a>
                <a href="#experience" class="text-dark fs-3 fw-semibold link-primary">Experience</a>
                <a href="#licenced" class="text-dark fs-3 fw-semibold link-primary">Licence Expert</a>
              </div>
              <a href="{{route('appointment')}}" class="btn bg-primary-subtle text-primary mb-3 w-100">Make Appointment</a>
              <div class="pt-9">
                <h4 class="text-uppercase fs-3 fw-bold">Social Media</h4>
                <div class="d-flex gap-6">
                  <a href="#" class="border rounded-circle round-40 hstack justify-content-center" data-bs-toggle="tooltip" data-bs-title="Facebook">
                    <img src="../assets/images/frontend-pages/icon-facebook-dark.svg" alt="facebook">
                  </a>
                  <a href="#" class="border rounded-circle round-40 hstack justify-content-center" data-bs-toggle="tooltip" data-bs-title="Instagram">
                    <img src="../assets/images/frontend-pages/icon-instagram-dark.svg" alt="instagram">
                  </a>
                  <a href="#" class="border rounded-circle round-40 hstack justify-content-center" data-bs-toggle="tooltip" data-bs-title="YouTube">
                    <img src="../assets/images/frontend-pages/icon-youtube-dark.svg" alt="youtube">
                  </a>
                  <a href="#" class="border rounded-circle round-40 hstack justify-content-center" data-bs-toggle="tooltip" data-bs-title="Linckedin">
                    <img src="../assets/images/frontend-pages/icon-linckedin-dark.svg" alt="linckedin">
                  </a>
                </div>
              </div>
            </div>

            <div class="card p-7 mt-3">
              <h4 class="fw-semibold mb-3">Galery Photos</h4>
              <div class="row">
                <div class="col-3">
                  <img src="{{asset('assets/images/profile/user-1.jpg')}}" alt="matdash-img" class="rounded-1 img-fluid mb-9">
                </div>
                <div class="col-3">
                  <img src="{{asset('assets/images/profile/user-1.jpg')}}" alt="matdash-img" class="rounded-1 img-fluid mb-9">
                </div>
                <div class="col-3">
                  <img src="{{asset('assets/images/profile/user-1.jpg')}}" alt="matdash-img" class="rounded-1 img-fluid mb-9">
                </div>
                <div class="col-3">
                  <img src="{{asset('assets/images/profile/user-1.jpg')}}" alt="matdash-img" class="rounded-1 img-fluid mb-9">
                </div>
                <div class="col-3">
                  <img src="{{asset('assets/images/profile/user-1.jpg')}}" alt="matdash-img" class="rounded-1 img-fluid mb-9">
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-8">
            <div class="d-flex flex-column gap-sm-4 gap-3 bg-white rounded-3 p-7">
              <div class="" id="biography">
                <h3 class="fs-7 fw-bolder mb-sm-4 mb-3">Biography</h3>
                <p class="card-subtitle">
                  Kirdi Putra is a highly skilled microexpression expert with over 10 years of experience in the field of behavioral analysis. Specializing in detecting hidden emotions through subtle facial expressions, John has worked with law enforcement agencies, corporate leaders, and therapists to enhance communication, detect deception, and resolve conflicts more effectively. His work has been featured in renowned psychological journals and he has been a sought-after speaker at various national and international conferences.
                </p>
                <p class="card-subtitle">
                  Kirdi's unique ability to decode human emotions has helped numerous organizations identify hidden truths and improve interpersonal communication. His expertise has proven invaluable in high-stakes situations such as interviews, negotiations, and conflict resolution. With his passion for understanding human behavior, John continues to mentor future professionals in this fascinating field.
                </p>
              </div>
              <div class="" id="experience">
                <h3 class="fs-7 fw-bolder mb-sm-4 mb-3">
                  Experience
                </h3>
                <div class="mb-3">
                  <h5>Microexpression Expert</h5>
                  <p><strong>Organization:</strong> Behavioral Insights Group</p>
                  <p><strong>Years:</strong> 2013 - Present</p>
                  <p>
                    As a Microexpression Expert, John has conducted workshops to train professionals in detecting microexpressions, interpreting body language, and understanding emotional cues. He works with various organizations to improve communication strategies and has helped organizations navigate difficult conversations with emotional intelligence.
                  </p>
                </div>
                <div class="mb-3">
                  <h5>Consultant for Law Enforcement</h5>
                  <p><strong>Organization:</strong> National Police Academy</p>
                  <p><strong>Years:</strong> 2015 - 2020</p>
                  <p>
                    John worked closely with law enforcement agencies, training officers to identify and analyze facial expressions during interrogations. His work has enhanced the accuracy of identifying deception and improved the interviewing techniques of officers.
                  </p>
                </div>
                
              </div>
              <div class="" id="expertise">
                <h3 class="fs-7 fw-bolder mb-sm-4 mb-3">
                  Expertise license
                </h3>
                <div class="mb-3">
                  <p><strong>Certified Microexpression Analyst (CMA)</strong></p>
                  <p>This certification is awarded to professionals who have demonstrated proficiency in analyzing microexpressions and emotional cues. The certification is issued by the International Body of Behavioral Experts.</p>
                </div>
                <div class="mb-3">
                  <p><strong>Certified Behavioral Analyst (CBA)</strong></p>
                  <p>This certification is awarded by the Behavioral Science Certification Institute to professionals with extensive training in behavioral analysis and non-verbal communication.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
@endsection

@section('footer')
    @include('partials/footer')
@endsection

