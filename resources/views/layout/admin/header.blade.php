<header class="">
   <nav class="navbar navbar-expand-lg">
      <h3 class="page-title martix-page-title d-none">
         <button class="back-btn">
            <img src="{{ asset('assets/images/back.svg') }}" alt="Back Button" />
         </button> Account Matrix of Jgarcia01
      </h3>
      <button class="hamburger d-sm-block d-md-none btn btn-dark">
         <i class="bi bi-list list-icon"></i>
      </button>
      <div class="right-side ms-auto">
         <div class="user-menu">
            <div class="user-dropdown dropdown">
               <a href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  <span class="fw-bold text-white name text-capitalize fs-6">{{ Auth::guard('admin')->user()->name }}</span> <img class="user-img" src="{{ asset('assets/images/dashboard/user.png') }}" alt="user" />
               </a>
               <ul class="dropdown-menu dropdown-menu-end bg-dark p-0">
                  <li>
                     <a class="text-white d-block p-3" href="{{ route('admin.logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                     </a>
                  </li>
               </ul>
               <form id="logout-form" action="{{ route('admin.logout') }}" method="get" class="d-none">
                  @csrf
               </form>
            </div>
         </div>
      </div>
   </nav>
</header>
