@extends('layout.master')
@section('html_class','')
@section('title','Library Item')
@section('bodyClass','class="layout-container ls-top-navbar si-l3-md-up"')
@section('PageContainerClass','class="page-container"')
@section('headerfile_unauth')
 @parent
@endsection

@section('navbar_navigation')
  
@endsection

@section('sidebar_navigation')
  
@endsection

@section('contentData')
@include('includes.navBarMenu')
@include('includes.userSidebar')

<div class="layout-content" data-scrollable>
    <div class="container-fluid">

      <h1 class="page-heading h2">{{$item->name}}</h1>
      <div class="row">
        <div class="col-md-8">
          <div class="card">
            <div style="overflow:hidden; height:300px">
              <img src="{{URL::asset('assets/images/cover/'.$item->cover_image)}}" alt="" class="img-rounded">
            </div>
            <div class="card-block">
              {{$item->description}}
            </div>
          </div>

        </div>
        <div class="col-md-4">
          <div class="card">
            <div class="card-block center">
              <p class="m-b-05">

                @if($item->book_type_format == 'mp4')
                    <a href="#" data-toggle="modal" data-target="#addNewCate" class="btn btn-success btn-block btn-rounded"><i class="material-icons md-48">play_arrow</i>PLAY MEDIA</a>
                @else
                    <a href="#" data-toggle="modal" data-target="#addNewCate" class="btn btn-success btn-block btn-rounded"><i class="material-icons md-48">import_contacts</i>OPEN BOOK</a>
                @endif

              </p>
             
            </div>
          </div>
          <div class="card">
            <div class="card-header bg-white">
              <div class="media">
                <div class="media-body media-middle">
                  <h4 class="card-title">Author(s)</h4>
                  <p class="card-subtitle"><a href="#">{{$item->authors}}</a></p>

                  <h4 class="card-title">Category</h4>
                  <p class="card-subtitle"><a href="#">{{$item->libraryCategory->name}}</a></p>

                  <h4 class="card-title">Sub Category</h4>
                  <p class="card-subtitle"><a href="#">{{$item->librarySubCategory->subcategory_name}}</a></p>

                </div>

                

              </div>
            </div>
            <div class="card-block">
              <p>TAGS:</p>
              <div>{{$item->tags}}</div>
             
            </div>
          </div>
        
        </div>
      </div>

    </div>
  </div>

  <div class="modal fade" id="addNewCate">
    <div class="modal-dialog-2">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h5 class="modal-title">LIBRARY ITEM LOADER</h5>
        </div>
        <div class="modal-body">
          <h3>...Item Loading</h3>
        </div>
      </div>
    </div>
  </div>
@endsection

