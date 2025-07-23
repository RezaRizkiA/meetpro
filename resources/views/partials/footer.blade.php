
  <footer class="bg-dark mt-auto">
    <div class="container-fluid">
      <div class="row py-7 py-md-14 py-lg-11">
        <div class="col-md-3 col-6 mb-7 mb-md-0">
          <img src="{{asset('assets/images/logos/key-person-white.png')}}" width="140" alt="white logo">

          <ul class="d-flex flex-column gap-9 mt-7 mb-0">
            <li>
              <a href="{{route('home')}}" class="fs-4 text-light link-primary">Home</a>
            </li>
            <li>
              <a href="{{route('about')}}" class="fs-4 text-light link-primary">About
                List</a>
            </li>
            <li>
              <a href="{{route('support')}}" class="fs-4 text-light link-primary">Support</a>
            </li>
          </ul>
        </div>
        <div class="col-md-3 col-6 mb-7 mb-md-0">
          <h3 class="fs-4 text-white fw-bolder mb-7">Forms</h3>
          <ul class="d-flex flex-column gap-9 mb-0">
            <li>
              <a href="{{route('terms')}}" class="fs-4 text-light link-primary"> Terms Of Service</a>
            </li>
            <li>
              <a href="{{route('privacy')}}" class="fs-4 text-light link-primary">Privacy Policy</a>
            </li>
          </ul>
        </div>
        <div class="col-md-3 col-6 mb-7 mb-md-0">
          <h3 class="fs-4 text-white fw-bolder mb-7">Other Aplication</h3>
          <ul class="d-flex flex-column gap-9 mb-0">
            <li>
              <a href="https://trainingstudio.id/" class="fs-4 text-light link-primary">trainingstudio.id</a>
            </li>
            <li>
              <a href="https://kerjapintar.id/" class="fs-4 text-light link-primary">kerjapintar.id</a>
            </li>
            <li>
              <a href="https://profiling.id/" class="fs-4 text-light link-primary">profiling.id</a>
            </li>
            <li>
              <a href="https://shiokaya.com/" class="fs-4 text-light link-primary">shiokaya.com</a>
            </li>
          </ul>
        </div>
        <div class="col-md-3 col-6 mb-7 mb-md-0">
          <h3 class="fs-4 text-white fw-semibold mb-7">Follow us</h3>
          <div class="d-flex gap-9">
            <a href="javascript:void(0)" data-bs-toggle="tooltip" data-bs-title="Facebook">
              <img src="../assets/images/frontend-pages/icon-facebook.svg" alt="facebook">
            </a>
            <a href="javascript:void(0)" data-bs-toggle="tooltip" data-bs-title="Twitter">
              <img src="../assets/images/frontend-pages/icon-twitter.svg" alt="twitter">
            </a>
            <a href="javascript:void(0)" data-bs-toggle="tooltip" data-bs-title="Instagram">
              <img src="../assets/images/frontend-pages/icon-instagram.svg" alt="instagram">
            </a>
          </div>
        </div>
      </div>

      <div class="d-flex justify-content-between flex-md-nowrap flex-wrap py-13 border-top border-dark-subtle">
        <div class="d-flex gap-3">
          <img src="{{asset('assets/images/logos/key-color.png')}}" width="40" alt="logo">
          <p class="text-white opacity-50 mb-0">All rights reserved {{ date('Y') }}</p>
        </div>
        <div>
          <p class="text-white mb-0">
            <span class="opacity-50">Produced by</span>
            <a href="" class="text-white link-primary">Kastara Parama Nusantara</a>
          </p>
        </div>
      </div>
    </div>
  </footer>

  <a href="javascript:void(0)" class="top-btn btn btn-primary d-flex align-items-center justify-content-center round-54 p-0 rounded-circle">
    <i class="ti ti-arrow-up fs-7"></i>
  </a>