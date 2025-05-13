@extends('layout')

@section('top')
<section class="pt-5 bg-light-gray">
      <div class="container-fluid">
        <div class="text-center">
          <div class="d-flex align-items-center justify-content-center gap-6">
            <a href="{{route('home')}}" class="text-muted fw-bolder link-primary fs-3 text-uppercase">
              MeetPro
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
            <div class="d-flex flex-column gap-3 bg-white p-7 rounded-3">
              <div class="d-flex gap-3 pb-3 border-bottom ">
                <div>
                  <img src="{{asset('image/kirdi-putra.jpeg')}}" alt="user" class="rounded-circle" width="44px" height="44px">
                </div>
                <div class="">
                  <p class="mb-0 text-dark fs-4 fw-semibold">Kirdi Putra</p>
                  <p class="mb-0 fs-3 fw-bold">Microexpression expert</p>
                </div>
              </div>
              <div class="py-9 d-flex flex-column gap-3 border-bottom">
                <h4 class="fs-3 fw-bold text-uppercase text-muted mb-0 ">Detail Profile</h4>
                <a href="#experience" class="text-dark fs-4 fw-semibold link-primary">Experience</a>
                <a href="#expertise" class="text-dark fs-4 fw-semibold link-primary">Expertise</a>
                <a href="#galery" class="text-dark fs-4 fw-semibold link-primary">Galery</a>
              </div>
              <button class="btn bg-primary-subtle text-primary mb-3 w-100">Add Appointment</button>
              <div class="py-9">
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
          </div>
          <div class="col-lg-8">
            <div class="d-flex flex-column gap-sm-4 gap-3 bg-white rounded-3 p-7">
              <div class="">
                <p class="fs-4 mb-sm-4 mb-3 text-muted">
                  Lorem ipsum dolor sit amet consectetur, adipisicing elit. Labor facere tenetur error consequuntur iam incidunt, voluptate delectus facilis ipsum inventore voluptatem! Dolor, dolore! Praesentium, illum consequatur. Sapiente, veritatis optio! Incidunt libero eaque harum at, ab dolorem recusandae voluptatem sint? Architecto explicabo, nobis quod tenetur cumque eum. Enim aliquam, voluptas culpa deleniti odio, maiores tempore aut animi suscipit non asperiores.
                </p>
                <p class="fs-4 mb-0 text-muted">
                  Lorem ipsum dolor sit, amet consectetur adipisicing elit. Ducimus suscipit veritatis eaque, dolore cumque deserunt distinctio voluptatibus autem qui officiis.
                </p>
              </div>
              <div class="" id="experience">
                <h3 class="fs-7 fw-bolder mb-sm-4 mb-3">
                  Experience
                </h3>
                <p class="fs-4 mb-0 text-muted">
                  Lorem ipsum dolor sit amet consectetur adipisicing elit. Quis, facilis voluptatibus enim ullam temporibus doloremque culpa aliquam, quo quam quia sunt voluptate dolorum deleniti, ex ut incidunt sed magni. Velit, quibusdam. Nostrum iusto repudiandae repellat commodi quia asperiores suscipit fuga recusandae, ducimus placeat vel aliquid nam dolore perferendis ipsum magni!
                </p>
              </div>
              <div class="" id="expertise">
                <h3 class="fs-7 fw-bolder mb-sm-4 mb-3">
                  Expertise
                </h3>
                <p class="fs-4 mb-0 text-muted">
                  Lorem ipsum dolor sit amet consectetur adipisicing elit. Blanditiis quidem quod veniam ratione beatae quam accusamus? Accusantium earum ex praesentium repudiandae dolorum, deleniti, sit, culpa corrupti minus inventore dolorem incidunt.
                </p>
              </div>
              <div class="" id="galery">
                <h3 class="fs-7 fw-bolder mb-sm-4 mb-3">
                  Galery
                </h3>
                <div class="row fs-4 mb-sm-4 mb-3 text-muted">
                  <div class="col-lg-3 col-md-6">
                    <div class="card overflow-hidden">
                      <div class="el-card-item pb-1">
                        <div class=" el-card-avatar mb-1 el-overlay-1 w-100 overflow-hidden position-relative text-center">
                          <img src="../assets/images/blog/blog-img3.jpg" class="d-block position-relative w-100" alt="user">
                        </div>
                        <div class="el-card-content text-center my-0">
                          <h4 class="mb-0 card-title">title</h4>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-3 col-md-6">
                    <div class="card overflow-hidden">
                      <div class="el-card-item pb-1">
                        <div class=" el-card-avatar mb-1 el-overlay-1 w-100 overflow-hidden position-relative text-center">
                          <img src="../assets/images/blog/blog-img3.jpg" class="d-block position-relative w-100" alt="user">
                        </div>
                        <div class="el-card-content text-center my-0">
                          <h4 class="mb-0 card-title">title</h4>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
@endsection

