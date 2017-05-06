<div class="sidebar sidebar-left si-si-3 sidebar-visible-md-up sidebar-light ls-top-navbar-xs-up sidebar-transparent-md" id="sidebarLeft" data-scrollable>
    <div class="sidebar-heading">APPLICATIONS</div>
    <ul class="sidebar-menu">
      <li class="sidebar-menu-item active">
        <a class="sidebar-menu-button" href="{{route('admin-dashboard')}}">
          <i class="sidebar-menu-icon material-icons">account_box</i> {{Session::get('accountDetails.fullname')}}
        </a>
      </li>
    </ul>
    <div class="sidebar-heading">MENU</div>
    <ul class="sidebar-menu">
      <li class="sidebar-menu-item {{ Request::path()=='admin/library/categories' ? 'active':'' }}">
        <a class="sidebar-menu-button" href="{{route('admin-library-category')}}">
          <i class="sidebar-menu-icon material-icons">import_contacts</i> Category
        </a>
      </li>
      <li class="sidebar-menu-item">
        <a class="sidebar-menu-button" href="#">
          <i class="sidebar-menu-icon material-icons">help</i> Admin Users
        </a>
      </li>
      <li class="sidebar-menu-item {{ Request::path()=='admin/manage/library' ? 'active':'' }}">
        <a class="sidebar-menu-button" href="{{route('admin-library-content')}}">
          <i class="sidebar-menu-icon material-icons">language</i> Library Manager
        </a>
      </li>
      <li class="sidebar-menu-item">
        <a class="sidebar-menu-button" href="#">
          <i class="sidebar-menu-icon material-icons">account_box</i> System Settings
        </a>
      </li>
      
      <li class="sidebar-menu-item">
        <a class="sidebar-menu-button" href="{{route('admin-out')}}">
          <i class="sidebar-menu-icon material-icons">lock_open</i> Logout
        </a>
      </li>
    </ul>
  </div>