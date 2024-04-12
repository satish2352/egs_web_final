 <!-- left sidebar -->
 <?php $data_for_url = session('data_for_url');
    //   dd($data_for_url);
      ?>
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
                              Welcome <b>{{ session()->get('f_name') }} {{ session()->get('m_name') }} {{ session()->get('l_name') }}</b>
                          </p>
                          <p class="designation">
                          {{ session()->get('role_name') }}
                          </p>                      </div>
                  </div>
              </li>
              @if (in_array('dashboard', $data_for_url))
              <li
              class="{{request()->is('dashboard*')
                    ? 'nav-item active' : 'nav-item' }}">
                  <a class="{{request()->is('dashboard*')
                                    ? 'nav-link active' : 'nav-link' }}" href="{{ route('/dashboard') }}">
                      <i class="fa fa-home menu-icon"></i>
                      <span class="menu-title">Dashboard</span>
                  </a>
              </li>    
              @endif
              @if (in_array('list-role', $data_for_url) || in_array('list-maritalstatus', $data_for_url) || in_array('list-relation', $data_for_url) ||
                      in_array('list-gender', $data_for_url) || in_array('list-skills', $data_for_url) || in_array('list-registrationstatus', $data_for_url)
                      || in_array('list-documenttype', $data_for_url))
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
                              @if (in_array('list-role', $data_for_url))
                                  <li class="nav-item d-none d-lg-block"><a class="{{request()->is('list-role*')
                                    ? 'nav-link active' : 'nav-link' }}"
                                          href="{{ route('list-role') }}">Role</a></li>
                              @endif
                              @if (in_array('list-gender', $data_for_url))
                                  <li class="nav-item d-none d-lg-block"><a class="{{request()->is('list-gender*')
                                    ? 'nav-link active' : 'nav-link' }}"
                                          href="{{ route('list-gender') }}">Gender</a></li>
                              @endif
                              @if (in_array('list-maritalstatus', $data_for_url))
                                  <li class="nav-item d-none d-lg-block"><a class="{{request()->is('list-maritalstatus*')
                                    ? 'nav-link active' : 'nav-link' }}"
                                          href="{{ route('list-maritalstatus') }}">Marital Status</a></li>
                              @endif
                              @if (in_array('list-relation', $data_for_url))
                                  <li class="nav-item d-none d-lg-block"><a class="{{request()->is('list-relation*')
                                    ? 'nav-link active' : 'nav-link' }}"
                                          href="{{ route('list-relation') }}">Relation</a></li>
                              @endif
                              @if (in_array('list-skills', $data_for_url))
                                  <li class="nav-item d-none d-lg-block"><a class="{{request()->is('list-skills*')
                                    ? 'nav-link active' : 'nav-link' }}"
                                          href="{{ route('list-skills') }}">Skills</a></li>
                              @endif
                              @if (in_array('list-registrationstatus', $data_for_url))
                                  <li class="nav-item d-none d-lg-block"><a class="{{request()->is('list-registrationstatus*')
                                    ? 'nav-link active' : 'nav-link' }}"
                                          href="{{ route('list-registrationstatus') }}">Registration Status</a></li>
                              @endif
                              @if (in_array('list-documenttype', $data_for_url))
                                  <li class="nav-item d-none d-lg-block"><a class="{{request()->is('list-documenttype*')
                                    ? 'nav-link active' : 'nav-link' }}"
                                          href="{{ route('list-documenttype') }}">Document Type</a></li>
                              @endif
                              @if (in_array('list-usertype', $data_for_url))
                              <li class="nav-item d-none d-lg-block"><a class="{{request()->is('list-usertype*')
                                    ? 'nav-link active' : 'nav-link' }}"
                                          href="{{ route('list-usertype') }}">User Type</a></li>
                              @endif

                          </ul>
                      </div>
                  </li>
             @endif

             <li class="{{request()->is('list-role*')
                    ? 'nav-item active' : 'nav-item' }}">
                      <a class="{{request()->is('list-role*')
                                    ? 'nav-link active' : 'nav-link' }}" data-toggle="collapse" href="#master" aria-expanded="false"
                          aria-controls="master">
                          <i class="fa fa-th-large menu-icon"></i>
                          <span class="menu-title">Area</span>
                          <i class="menu-arrow"></i>
                      </a>
                      <div class="collapse" id="master">
                          <ul class="nav flex-column sub-menu">
                              <!-- @if (in_array('list-role', $data_for_url)) -->
                                  <li class="nav-item d-none d-lg-block"><a class="{{request()->is('list-district*')
                                    ? 'nav-link active' : 'nav-link' }}"
                                          href="{{ route('list-district') }}">District</a></li>
                              <!-- @endif -->
                              <!-- @if (in_array('list-gender', $data_for_url)) -->
                                  <li class="nav-item d-none d-lg-block"><a class="{{request()->is('list-taluka*')
                                    ? 'nav-link active' : 'nav-link' }}"
                                          href="{{ route('list-taluka') }}">Taluka</a></li>
                              <!-- @endif -->
                              <!-- @if (in_array('list-maritalstatus', $data_for_url)) -->
                                  <li class="nav-item d-none d-lg-block"><a class="{{request()->is('list-village*')
                                    ? 'nav-link active' : 'nav-link' }}"
                                          href="{{ route('list-village') }}">Village</a></li>
                              <!-- @endif -->
                              

                          </ul>
                      </div>
                  </li>

            <li class="nav-item">
                <a class="{{request()->is('list-role*')
                            ? 'nav-link active' : 'nav-link' }}" data-toggle="collapse" href="#master" aria-expanded="false"
                    aria-controls="master">
                    <i class="fa fa-th-large menu-icon"></i>
                    <span class="menu-title">Labour Managment</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="master">
                    <ul class="nav flex-column sub-menu">
                    @if (in_array('list-labours', $data_for_url))
                        @if(session()->get('role_id')=='1' || session()->get('role_id')=='2')
                            <li class="nav-item d-none d-lg-block"><a class="{{request()->is('list-labours*')
                            ? 'nav-link active' : 'nav-link' }}"
                                    href="{{ route('list-labours') }}">Received For Approval</a></li>
                        @elseif(session()->get('role_id')=='3')
                            <li class="nav-item d-none d-lg-block"><a class="{{request()->is('list-labours*')
                            ? 'nav-link active' : 'nav-link' }}"
                                    href="{{ route('list-labours') }}">Sended For Approval</a></li>
                        @endif

                    @endif
                    @if (in_array('list-labours', $data_for_url))
                        <li class="nav-item d-none d-lg-block"><a class="{{request()->is('list-approved-labours*')
                            ? 'nav-link active' : 'nav-link' }}"
                                    href="{{ route('list-approved-labours') }}">Approved Labours</a></li>
                    @endif
                    @if (in_array('list-labours', $data_for_url))
                        <li class="nav-item d-none d-lg-block"><a class="{{request()->is('list-disapproved-labours*')
                            ? 'nav-link active' : 'nav-link' }}"
                                    href="{{ route('list-disapproved-labours') }}">Not Approved Labours</a></li>
                    @endif
                    @if (in_array('list-labours', $data_for_url))
                        <li class="nav-item d-none d-lg-block"><a class="{{request()->is('list-resubmitted-labours*')
                            ? 'nav-link active' : 'nav-link' }}"
                                    href="{{ route('list-resubmitted-labours') }}">Resubmitted Labours</a></li>
                    @endif

                    </ul>
                </div>
            </li>                    
            
            @if (in_array('list-labour-attendance', $data_for_url))
            <li class="{{request()->is('list-labour-attendance*')
                ? 'nav-item active' : 'nav-item' }}">
                <?php $currenturl = Request::url(); ?>
                <a class="nav-link" href="{{ route('list-labour-attendance') }}">
                    <i class="fas fa-user menu-icon"></i>
                    <span class="menu-title">Labour Attendance</span>
                </a>
            </li>
            @endif    




             @if (in_array('list-users', $data_for_url))
        <li class="{{request()->is('list-users*')
            ? 'nav-item active' : 'nav-item' }}">
            <?php $currenturl = Request::url(); ?>
              <a class="nav-link" href="{{ route('list-users') }}">
                  <i class="fas fa-user menu-icon"></i>
                  <span class="menu-title">Users Management</span>
              </a>
          </li>
             @endif

             @if (in_array('list-projects', $data_for_url))
          <li class="{{request()->is('list-projects*')
                ? 'nav-item active' : 'nav-item' }}">
                <?php $currenturl = Request::url(); ?>
                <a class="nav-link" href="{{ route('list-projects') }}">
                    <i class="fas fa-file-alt fa-lg menu-icon"></i>
                    <span class="menu-title">Project Management</span>
                </a>
            </li>
            @endif

           
            @if (in_array('list-gramsevak', $data_for_url))
            <li class="{{request()->is('list-gramsevak*')
                ? 'nav-item active' : 'nav-item' }}">
                <?php $currenturl = Request::url(); ?>
                <a class="nav-link" href="{{ route('list-gramsevak') }}">
                    <i class="fas fa-file-alt fa-lg menu-icon"></i>
                    <span class="menu-title">Gramsevak Management</span>
                </a>
            </li>
            @endif

            @if(session()->get('role_id')=='2')
            <li class="nav-item">
                <a class="{{request()->is('list-role*')
                            ? 'nav-link active' : 'nav-link' }}" data-toggle="collapse" href="#master" aria-expanded="false"
                    aria-controls="master">
                    <i class="fa fa-th-large menu-icon"></i>
                    <span class="menu-title">Grampanchayt Documents</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="master">
                    <ul class="nav flex-column sub-menu">
                    <!-- @if (in_array('list-labours', $data_for_url)) -->
                        <!-- @if(session()->get('role_id')=='1' || session()->get('role_id')=='2') -->
                            <li class="nav-item d-none d-lg-block"><a class="{{request()->is('list-grampanchayt-doc-new*')
                            ? 'nav-link active' : 'nav-link' }}"
                                    href="{{ route('list-grampanchayt-doc-new') }}">Received For Approval</a></li>
                        <!-- @elseif(session()->get('role_id')=='3')
                            <li class="nav-item d-none d-lg-block"><a class="{{request()->is('list-grampanchayt-doc-new*')
                            ? 'nav-link active' : 'nav-link' }}"
                                    href="{{ route('list-grampanchayt-doc-new') }}">Sended For Approval</a></li>
                        @endif -->

                    <!-- @endif -->
                    @if (in_array('list-labours', $data_for_url))
                        <li class="nav-item d-none d-lg-block"><a class="{{request()->is('list-grampanchayt-doc-approved*')
                            ? 'nav-link active' : 'nav-link' }}"
                                    href="{{ route('list-grampanchayt-doc-approved') }}">Approved Documents</a></li>
                    @endif
                    @if (in_array('list-labours', $data_for_url))
                        <li class="nav-item d-none d-lg-block"><a class="{{request()->is('list-grampanchayt-doc-not-approved*')
                            ? 'nav-link active' : 'nav-link' }}"
                                    href="{{ route('list-grampanchayt-doc-not-approved') }}">Not Approved Documents</a></li>
                    @endif
                    @if (in_array('list-labours', $data_for_url))
                        <li class="nav-item d-none d-lg-block"><a class="{{request()->is('list-grampanchayt-doc-resubmitted*')
                            ? 'nav-link active' : 'nav-link' }}"
                                    href="{{ route('list-grampanchayt-doc-resubmitted') }}">Resubmitted Labours</a></li>
                    @endif

                    </ul>
                </div>
            </li> 
        @endif                    

        @if(session()->get('role_id')=='1' || session()->get('role_id')=='3')
        <li class="nav-item">
                <a class="{{request()->is('list-role*')
                            ? 'nav-link active' : 'nav-link' }}" data-toggle="collapse" href="#master" aria-expanded="false"
                    aria-controls="master">
                    <i class="fa fa-th-large menu-icon"></i>
                    <span class="menu-title">Grampanchayt Documents</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="master">
                    <ul class="nav flex-column sub-menu">
                    <!-- @if (in_array('list-labours', $data_for_url)) -->
                        <!-- @if(session()->get('role_id')=='1' || session()->get('role_id')=='2')
                            <li class="nav-item d-none d-lg-block"><a class="{{request()->is('list-grampanchayt-doc-new*')
                            ? 'nav-link active' : 'nav-link' }}"
                                    href="{{ route('list-grampanchayt-doc-new') }}">Received For Approval</a></li>
                        @elseif(session()->get('role_id')=='3') -->
                            <li class="nav-item d-none d-lg-block"><a class="{{request()->is('list-sended-for-approval*')
                            ? 'nav-link active' : 'nav-link' }}"
                                    href="{{ route('list-sended-for-approval') }}">Sended For Approval</a>
                                </li>
                        <!-- @endif -->

                    <!-- @endif -->
                    @if (in_array('list-labours', $data_for_url))
                        <li class="nav-item d-none d-lg-block"><a class="{{request()->is('list-approved-documents*')
                            ? 'nav-link active' : 'nav-link' }}"
                                    href="{{ route('list-approved-documents') }}">Approved Documents</a></li>
                    @endif
                    @if (in_array('list-labours', $data_for_url))
                        <li class="nav-item d-none d-lg-block"><a class="{{request()->is('list-not-approved-documents*')
                            ? 'nav-link active' : 'nav-link' }}"
                                    href="{{ route('list-not-approved-documents') }}">Not Approved Documents</a></li>
                    @endif
                    @if (in_array('list-labours', $data_for_url))
                        <li class="nav-item d-none d-lg-block"><a class="{{request()->is('list-resubmitted-labours*')
                            ? 'nav-link active' : 'nav-link' }}"
                                    href="{{ route('list-resubmitted-labours') }}">Resubmitted Labours</a></li>
                    @endif

                    </ul>
                </div>
            </li> 






<!-- 
            <li class="{{request()->is('list-grampanchayat-doc*')
                ? 'nav-item active' : 'nav-item' }}">
                <?php //$currenturl = Request::url(); ?>
                <a class="nav-link" href="{{ route('list-grampanchayat-doc') }}">
                    <i class="fas fa-file-alt fa-lg menu-icon"></i>
                    <span class="menu-title">Document Management</span>
                </a>
            </li> -->
        @endif    

            <li class="{{request()->is('list-role*') 
                ? 'nav-item active' : 'nav-item' }}">
                  <a class="{{request()->is('list-role*')
                                ? 'nav-link active' : 'nav-link' }}" data-toggle="collapse" href="#report" aria-expanded="false"
                      aria-controls="report">
                      <i class="fas fa-window-restore menu-icon"></i>
                      <span class="menu-title">Reports</span>
                      <i class="menu-arrow"></i>
                  </a>
                  <div class="collapse" id="report">
                      <ul class="nav flex-column sub-menu">
                          {{-- @if (in_array('list-role', $data_for_url)) --}}
                              <li class="nav-item d-none d-lg-block"><a class="{{request()->is('list-location-report*')
                                ? 'nav-link active' : 'nav-link' }}"
                                      href="{{ route('list-location-report') }}">Location Report</a></li>
                          {{-- @endif --}}
                          {{-- @if (in_array('list-labour-duration-report', $data_for_url)) --}}
                              <li class="nav-item d-none d-lg-block"><a class="{{request()->is('list-labour-duration-report*')
                                ? 'nav-link active' : 'nav-link' }}"
                                      href="{{ route('list-labour-duration-report') }}">Duration Report</a></li>
                          {{-- @endif --}}
                          {{-- @if (in_array('list-project-report', $data_for_url)) --}}
                              <li class="nav-item d-none d-lg-block"><a class="{{request()->is('list-project-report*')
                                ? 'nav-link active' : 'nav-link' }}"
                                      href="{{ route('list-project-report') }}">Project Report</a></li>
                          {{-- @endif --}}
                          {{-- @if (in_array('list-project-and-location-report', $data_for_url)) --}}
                              <li class="nav-item d-none d-lg-block"><a class="{{request()->is('list-project-and-location-report*')
                                ? 'nav-link active' : 'nav-link' }}"
                                      href="{{ route('list-project-and-location-report') }}">Project and Location</a></li>
                          {{-- @endif --}}
                      </ul>
                  </div>
              </li>

              
           
 
      {{-- @endif --}}
          </ul>
      </nav>
<!-- partial -->
 
      <script>
       
      </script>