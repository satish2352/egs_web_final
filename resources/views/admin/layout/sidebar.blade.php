 
 <!-- left sidebar -->
      <?php $data_for_url = session('data_for_url'); ?>
      <style>
        .sidebar li .submenu{ 
	list-style: none; 
	margin: 0; 
	padding: 0; 
	padding-left: 1rem; 
	padding-right: 1rem;
}
        </style>
      <nav class="sidebar sidebar-offcanvas fixed-nav" id="sidebar">
          <ul class="nav">
              <li class="nav-item nav-profile">
                  <div class="nav-link">
                      {{-- <div class="profile-image">
                          <img src="images/faces/face5.jpg" alt="image" />
                      </div> --}}
                      <div class="profile-name">
                          <p class="name">
                              Welcome Admin
                          </p>
                          {{-- <p class="designation">
                              Super Admin
                          </p> --}}
                      </div>
                  </div>
              </li>
              {{-- @if (in_array('dashboard', $data_for_url)) --}}
              <li
              class="{{request()->is('dashboard*') 
                    ? 'nav-item active' : 'nav-item' }}">
                  <a class="{{request()->is('dashboard*') 
                                    ? 'nav-link active' : 'nav-link' }}" href="{{ route('/dashboard') }}">
                      <i class="fa fa-home menu-icon"></i>
                      <span class="menu-title">Dashboard</span>
                  </a>
              </li>    
              {{-- @endif           --}}
              {{-- @if (in_array('list-role', $data_for_url) ||
                      in_array('list-incident-type', $data_for_url)) --}}
                  <li class="{{request()->is('list-role*') 
                    ? 'nav-item active' : 'nav-item' }}">
                      <a class="{{request()->is('list-role*') 
                                    ? 'nav-link active' : 'nav-link' }}" data-toggle="collapse" href="#master" aria-expanded="false"
                          aria-controls="master">
                          <i class="fa fa-th-large menu-icon"></i>
                          <span class="menu-title">Master</span>
                          <i class="menu-arrow"></i>
                      </a>
                      <div class="collapse" id="master">
                          <ul class="nav flex-column sub-menu">
                              {{-- @if (in_array('list-role', $data_for_url)) --}}
                                  <li class="nav-item d-none d-lg-block"><a class="{{request()->is('list-role*') 
                                    ? 'nav-link active' : 'nav-link' }}"
                                          href="{{ route('list-role') }}">Role</a></li>
                              {{-- @endif --}}
                              {{-- @if (in_array('list-gender', $data_for_url)) --}}
                                  <li class="nav-item d-none d-lg-block"><a class="{{request()->is('list-gender*') 
                                    ? 'nav-link active' : 'nav-link' }}"
                                          href="{{ route('list-gender') }}">Gender</a></li>
                              {{-- @endif --}}
                              {{-- @if (in_array('list-maritalstatus', $data_for_url)) --}}
                                  <li class="nav-item d-none d-lg-block"><a class="{{request()->is('list-maritalstatus*') 
                                    ? 'nav-link active' : 'nav-link' }}"
                                          href="{{ route('list-maritalstatus') }}">Marital Status</a></li>
                              {{-- @endif --}}
                              {{-- @if (in_array('list-incident-type', $data_for_url)) --}}
                                  <li class="nav-item d-none d-lg-block"><a class="{{request()->is('list-incident-type*') 
                                    ? 'nav-link active' : 'nav-link' }}"
                                          href="{{ route('list-incident-type') }}">Incident Type</a></li>
                              {{-- @endif --}}
                          </ul>
                      </div>
                  </li>
             {{-- @endif --}}
        <li class="{{request()->is('list-users*')

            ? 'nav-item active' : 'nav-item' }}">

            <?php $currenturl = Request::url(); ?>

              <a class="nav-link" href="{{ route('list-users') }}">

                  <i class="fas fa-user menu-icon"></i>

                  <span class="menu-title">Users Management</span>

              </a>

          </li>

      {{-- @endif --}}
          </ul>
      </nav>
<!-- partial -->

      <script>
        
      </script>