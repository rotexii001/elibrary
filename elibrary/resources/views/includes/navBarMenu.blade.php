<nav class="navbar navbar-dark bg-primary navbar-full navbar-fixed-top">


    <button class="navbar-toggler pull-xs-left" type="button" data-toggle="sidebar" data-target="#sidebarLeft"><span class="material-icons">menu</span></button>


    <a href="#" class="navbar-brand"><i class="material-icons">school</i> eLibrary</a>


    <form class="form-inline pull-xs-left hidden-sm-down">
      <div class="input-group">
        <input type="text" class="form-control" placeholder="Search Library">
        <span class="input-group-btn"><button class="btn" type="button"><i class="material-icons">search</i></button></span>
      </div>
    </form>

    @if(Session::has('AdminOn')==false)
      <ul class="nav navbar-nav hidden-sm-down">
        <li class="nav-item">
          <a class="nav-link" href="#">A-Z</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Catalog</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Newspaper</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Category</a>
        </li>
      </ul>
    @endif

    <ul class="nav navbar-nav pull-xs-right">


      <li class="nav-item dropdown">
        <a class="nav-link active dropdown-toggle p-a-0" data-toggle="dropdown" href="#" role="button" aria-haspopup="false">
          <img src="{{URL::asset('assets/images/people/no_avater.png')}}" alt="Avatar" class="img-circle" width="40">
        </a>
        <div class="dropdown-menu dropdown-menu-right dropdown-menu-list" aria-labelledby="Preview">
          <a class="dropdown-item" href="#"><i class="material-icons md-18">lock</i> <span class="icon-text">Edit Account</span></a>
          @if(Session::has('AdminOn') && Session::get('AdminOn')=='1')
            <a class="dropdown-item" href="{{route('admin-out')}}">Logout</a>
          @else
            <a class="dropdown-item" href="{{route('user-out')}}">Logout</a>
          @endif
          
        </div>
      </li>
      <!-- // END User dropdown -->

    </ul>
    <!-- // END Menu -->

  </nav>