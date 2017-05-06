@extends('layout.master')
@section('html_class','')
@section('title','eLibrary System | Category')
@section('bodyClass','class="layout-container ls-top-navbar si-l3-md-up"')
@section('headerfile_unauth')
 @parent
@endsection

@section('pageLevelCss')

  <link rel="stylesheet" href="{{URL::asset('examples/css/bootstrap-touchspin.min.css')}}">
  <link rel="stylesheet" href="{{URL::asset('examples/css/nestable.min.css')}}">

@endsection

@section('contentData')
 
 @include('includes.navBarMenu')

 @include('includes.adminSidebar')

 <div class="layout-content" data-scrollable>
    <div class="container-fluid">
      <div class="card">
        <div class="card-header">
          <h6>@include('includes.message')</h6>
          <h4 class="card-title">AVAILABLE LIBRARY CATEGORY</h4>
        </div>
        <div class="card-header bg-white">
          <a href="#" data-toggle="modal" data-target="#addNewCate" class="btn btn-success btn-rounded">Add New Category<i class="material-icons">add</i></a>
        </div>
        <div class="nestable" id="nestable">
          <ul class="list-group list-group-fit nestable-list-plain m-b-0">
            @foreach($categories as $category)
               <li class="list-group-item nestable-item">
                <div class="media">
                    <div class="media-left media-middle">
                    <a href="#" class="btn btn-default nestable-handle"><i class="material-icons">menu</i></a>
                    </div>
                    <div class="media-body media-middle">
                    {{$category->name}}
                    </div>
                    <div class="media-right right">
                    <div style="width:100px">
                        <a href="#" data-toggle="modal" data-target="#addNewCate" class="btn btn-primary btn-sm"><i class="material-icons">edit</i></a>
                    </div>
                    </div>
                </div>
               </li>
            @endforeach
          </ul>
        </div>
      </div>

    </div>
  </div>
@endsection

@section('pageLevelJs')

  <script src="{{URL::asset('assets/vendor/jquery.nestable.js')}}"></script>
  <script src="{{URL::asset('assets/vendor/jquery.bootstrap-touchspin.js')}}"></script>
  <script src="{{URL::asset('examples/js/nestable.js')}}"></script>
  <script src="{{URL::asset('examples/js/touchspin.js')}}"></script>

  <div class="modal fade" id="addNewCate">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h5 class="modal-title">Add Category</h5>
        </div>
        <div class="modal-body">
          <form action="{{route('create-library-category')}}" method="post">
            <div class="form-group row">
              <label for="" class="form-control-label col-md-3">Name (20 Characters Max)</label>
              <div class="col-md-9">
                <input type="text" name="category_name" class="form-control" value="" placeholder="new category name">
              </div>
            </div>

            <div class="form-group row">
              <label for="" class="form-control-label col-md-3">Short Name (10 Characters Max)</label>
              <div class="col-md-9">
                <input type="text" name="category_short_name" class="form-control" value="" placeholder="new category short name">
              </div>
            </div>

            <div class="form-group row">
              <label for="" class="form-control-label col-md-3">Visibility</label>
              <div class="col-md-4">
                <select name="visibility" class="c-select form-control">
                  <option value="">---SELECT---</option>
                  <option value="1">Enable</option>
                  <option value="0">Hide for Now</option>
                </select>
              </div>
            </div>
           
            <div class="form-group row">
              <div class="col-md-8 col-md-offset-3">
                <input type="hidden" name="_token" value="{{Session::token()}}" />
                <button type="submit" class="btn btn-success">Add</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

