<div class="sidebar sidebar-left si-si-3 sidebar-visible-md-up sidebar-light ls-top-navbar-xs-up sidebar-transparent-md" id="sidebarLeft" data-scrollable>
    <div class="sidebar-heading">ONLINE LIBRARY</div>
    <ul class="sidebar-menu">
      <li class="sidebar-menu-item {{ Request::path()=='dashboard' ? 'active':'' }}">
          &nbsp;&nbsp;&nbsp;&nbsp;<a href="{{route('home')}}"><i class="sidebar-menu-icon material-icons">home</i></a> {{Session::get('accountDetails.fullname')}}
      </li>
    </ul>
    <div class="sidebar-heading">MENU</div>
    <ul class="sidebar-menu">
      <li class="sidebar-menu-item {{ Request::path()=='admin/library/categories' ? 'active':'' }}">
        <a class="sidebar-menu-button" href="{{route('admin-library-category')}}">
          <i class="sidebar-menu-icon material-icons">picture_as_pdf</i> Find eBooks
        </a>
      </li>

      <li class="sidebar-menu-item {{ Request::path()=='admin/library/categories' ? 'active':'' }}">
        <a class="sidebar-menu-button" href="{{route('admin-library-category')}}">
          <i class="sidebar-menu-icon material-icons">movie</i> Find Media
        </a>
      </li>

      <li class="sidebar-menu-item {{ Request::path()=='admin/library/categories' ? 'active':'' }}">
        <a class="sidebar-menu-button" href="{{route('admin-library-category')}}">
          <i class="sidebar-menu-icon material-icons">format_align_left</i> Journals
        </a>
      </li>

      <li class="sidebar-menu-item {{ Request::path()=='admin/library/categories' ? 'active':'' }}">
        <a class="sidebar-menu-button" href="{{route('admin-library-category')}}">
          <i class="sidebar-menu-icon material-icons">place</i> Find Maps (Places)
        </a>
      </li>

      <li class="sidebar-menu-item {{ Request::path()=='admin/library/categories' ? 'active':'' }}">
        <a class="sidebar-menu-button" href="{{route('admin-library-category')}}">
          <i class="sidebar-menu-icon material-icons">content_paste</i> Newspapers
        </a>
      </li>

      <li class="sidebar-menu-item {{ Request::path()=='admin/library/categories' ? 'active':'' }}">
        <a class="sidebar-menu-button" href="{{route('admin-library-category')}}">
          <i class="sidebar-menu-icon material-icons">supervisor_account</i> Available Authors
        </a>
      </li>

      <li class="sidebar-menu-item">
        <a class="sidebar-menu-button" href="#">
          <i class="sidebar-menu-icon material-icons">reorder</i> Catalog
        </a>
      </li>

      <li class="sidebar-menu-item {{ Request::path()=='admin/manage/library' ? 'active':'' }}">
        <a class="sidebar-menu-button" href="{{route('admin-library-content')}}">
          <i class="sidebar-menu-icon material-icons">sort_by_alpha</i> A - Z
        </a>
      </li>
      <li class="sidebar-menu-item">
        <a class="sidebar-menu-button" href="#">
          <i class="sidebar-menu-icon material-icons">language</i> Online Library Stores
        </a>
      </li>
      
      <li class="sidebar-menu-item">
        <a class="sidebar-menu-button" href="login.html">
          <i class="sidebar-menu-icon material-icons">lock_open</i> Logout
        </a>
      </li>
    </ul>
  </div>