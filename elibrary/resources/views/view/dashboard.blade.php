@extends('layout.master')
@section('html_class','')
@section('title','')
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

      <div class="clearfix"></div>
      <div class="card-columns">
      @foreach($libraryContents as $libraryContent)
        <div class="card">
          <div class="card-header bg-white center">
            <h4 class="card-title"><a href="{{route('get-library-item-content',['category'=>$libraryContent->libraryCategory->id,'id'=>$libraryContent])}}">{{$libraryContent->name}}</a></h4>
          </div>
          <a href="{{route('get-library-item-content',['category'=>$libraryContent->libraryCategory->id,'id'=>$libraryContent])}}">
            <img src="{{ URL::asset('assets/images/cover/'.$libraryContent->cover_image)}}" alt="{{$libraryContent->name}}" style="width:100%;">
          </a>
          <div class="card-block">
            <small class="text-muted">Category: {{$libraryContent->libraryCategory->name}} (subcategory - {{$libraryContent->librarySubCategory->subcategory_name}})</small>
            <p class="m-b-0">
             {{(strlen($libraryContent->description)>150)? substr($libraryContent->description,0,150).'...':$libraryContent->description}}
            </p>
            <p>Tag:<span class="label label-primary"> {{$libraryContent->tags}}</span></p>
          </div>
        </div>
      @endforeach
      </div>
      <!--
      <nav class="center">
        <ul class="pagination pagination-sm">
          <li class="page-item disabled">
            <a class="page-link" href="#" aria-label="Previous">
              <span aria-hidden="true">&laquo;</span>
              <span class="sr-only">Previous</span>
            </a>
          </li>
          <li class="page-item active">
            <a class="page-link" href="#">1 <span class="sr-only">(current)</span></a>
          </li>
          <li class="page-item"><a class="page-link" href="#">2</a></li>
          <li class="page-item"><a class="page-link" href="#">3</a></li>
          <li class="page-item"><a class="page-link" href="#">4</a></li>
          <li class="page-item"><a class="page-link" href="#">5</a></li>
          <li class="page-item">
            <a class="page-link" href="#" aria-label="Next">
              <span aria-hidden="true">&raquo;</span>
              <span class="sr-only">Next</span>
            </a>
          </li>
        </ul>
      </nav>
      -->

    </div>
  </div>
@endsection

