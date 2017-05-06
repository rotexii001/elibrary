@extends('layout.master')
@section('html_class','')
@section('title','eLibrary System | Login')
@section('bodyClass','class="login"')
@section('headerfile_unauth')
 @parent
@endsection

@section('contentData')
  <div class="row">
    <div class="col-sm-8 col-sm-push-1 col-md-4 col-md-push-4 col-lg-4 col-lg-push-4">
      <div class="center m-a-2">
        <div class="icon-block img-circle">
          <i class="material-icons md-36 text-muted">lock</i>
        </div>
      </div>
      <div class="card bg-transparent">
        <div class="card-header bg-white center">
          <h4 class="card-title">Login</h4>
          <p class="card-subtitle">Access eLibrary System</p>
          <h6>@include('includes.message')</h6>
        </div>
        <div class="p-a-2">
          <form action="{{route('user-in')}}" method="post">
            <div class="form-group">
            
              <input type="text" class="form-control" name="loginid" placeholder="Login Id">
            </div>
            <div class="form-group">
              <input type="password" class="form-control" name="password" placeholder="Password">
            </div>
            <div class="form-group ">
              <input type="hidden" name="_token" value="{{Session::token()}}" />
              <button type="submit" class="btn  btn-primary  btn-block btn-rounded">
                Login
              </button>
            </div>
            <div class="center">
              <a href="#">
                <small>Forgot Password?</small>
              </a>
            </div>
          </form>
        </div>
        <div class="card-footer center bg-white">
         Developed By {{config('app.developer')}}
        </div>
      </div>
    </div>
  </div>
@endsection