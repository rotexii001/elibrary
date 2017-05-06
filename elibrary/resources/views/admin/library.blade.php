@extends('layout.master')
@section('html_class','')
@section('title','eLibrary System | Manage Library')
@section('bodyClass','class="layout-container ls-top-navbar si-l3-md-up"')
@section('headerfile_unauth')
 @parent
@endsection

@section('pageLevelCss')

  <link rel="stylesheet" href="{{URL::asset('examples/css/morris.min.css')}}">

@endsection

@section('contentData')
 
@include('includes.navBarMenu')

@include('includes.adminSidebar')
<div class="layout-content" data-scrollable>
    <div class="container-fluid">

      <div class="card">
        <div class="card-header bg-white">
         <h6>@include('includes.message')</h6>
          <a href="#" data-toggle="modal" data-target="#addToLibrary" class="btn btn-success btn-rounded">ADD TO LIBRARY<i class="material-icons">add</i></a>
        </div>
        <div class="card-header bg-white left">
          <h5 class="card-title">AVAILABLE CONTENT<span class="text-primary"></span></h5>
        </div>
        <table class="table table-striped m-b-0">
          <thead>
            <tr class="text-uppercase small">
              <th style="width:200px">Cover &amp; Title</th>
              <th style="width:200px">Category</th>
              <th style="width:200px">Sub-Category</th>
              <th class="center" style="width:130px">&nbsp;</th>
            </tr>
          </thead>
          <tbody>
          @foreach($libraryContents as $libraryContent)
            <tr>
              <td>
                <div class="media">
                  <div class="media-left">
                    <img src="{{URL::asset('assets/images/cover/'.$libraryContent->cover_image)}}" alt="" width="80" class="img-rounded">
                  </div>
                  <div class="media-body media-middle">
                   {{$libraryContent->name}} 
                    <div class="text-muted small"> Author: {{$libraryContent->authors}} , Book Format: {{$libraryContent->book_type_format}}</div>
                  </div>
                </div>
              </td>
              <td class="">
                <span class="text-orange bold">{{$libraryContent->libraryCategory->name}}</span>
              </td>
              <td class="">
                <span class="text-orange bold">{{$libraryContent->librarySubCategory->subcategory_name}}</span><br/>
                Tags: <span class="label label-default">{{$libraryContent->tags}}</span>
              </td>
              <td class="center vertical-middle">
                <a href="#" data-toggle="modal" data-target="#addNewCate" class="btn btn-primary btn-sm"><i class="material-icons">edit</i></a>
                <a href="#" data-toggle="modal" data-target="#addNewCate" class="btn btn-danger btn-sm"><i class="material-icons">delete</i></a>
               
                @if($libraryContent->book_type_format == 'mp4')
                    <a href="#" data-toggle="modal" data-target="#addNewCate" class="btn btn-secondary btn-sm"><i class="material-icons">play_arrow</i></a>
                @else
                    <a href="#" data-toggle="modal" data-target="#addNewCate" class="btn btn-secondary btn-sm"><i class="material-icons">link</i></a>
                @endif
              </td>
            </tr>
          @endforeach
          </tbody>
        </table>
      </div>

    </div>
  </div>
@endsection

@section('pageLevelJs')

  <script src="{{URL::asset('assets/js/colors.js')}}"></script>
  <script src="{{URL::asset('assets/vendor/raphael-min.js')}}"></script>
  <script src="{{URL::asset('assets/vendor/morris.min.js')}}"></script>
  <script src="{{URL::asset('examples/js/chart.js')}}"></script>

  

  <div class="modal fade" id="addToLibrary">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h5 class="modal-title">ADD CONTENT TO LIBRARY</h5>
        </div>
        <div class="modal-body">

        @inject("category", "App\library_categories")

        @inject("subCategory", "App\library_subcategory")

          <form action="{{route('add-library-content')}}" method="post" enctype="multipart/form-data">
            <div class="form-group row">
              <label for="" class="form-control-label col-md-3">Name</label>
              <div class="col-md-9">
                <input type="text" name="name" value="{{Request::old('name')}}" required class="form-control" value="" placeholder="Name of media to add">
              </div>
            </div>

             <div class="form-group row">
              <label for="" class="form-control-label col-md-3">Description (Not more than 500 characters)</label>
              <div class="col-md-9">
                <textarea rows="3" cols="40" value="{{Request::old('description')}}" required name="description"></textarea>
              </div>
            </div>

             <div class="form-group row">
              <label for="" class="form-control-label col-md-3">Author</label>
              <div class="col-md-9">
                <input type="text" required name="author" class="form-control" value="{{Request::old('author')}}" placeholder="Author (Seperate multiple authors with comma)">
              </div>
            </div>

             <div class="form-group row">
              <label for="" class="form-control-label col-md-3">Category</label>
              <div class="col-md-4">
                <select required name="category" class="c-select form-control">
                  <option value="">---SELECT---</option>
                  @foreach($category->getCategories() as $cateList)
                    <option value="{{$cateList->id}}">{{$cateList->name}}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="form-group row">
              <label for="" class="form-control-label col-md-3">Sub-Category</label>
              <div class="col-md-4">
                <select name="subcategory" class="c-select form-control">
                  <option value="">---SELECT---</option>
                  @foreach($subCategory->getSubCategories() as $subcateList)
                    <option value="{{$subcateList->id}}">{{$subcateList->subcategory_name}}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="form-group row">
              <label for="" class="form-control-label col-md-3">Book ISBN</label>
              <div class="col-md-9">
                <input type="text" value="{{Request::old('book_isbn')}}" required name="book_isbn" class="form-control">
              </div>
            </div>

            <div class="form-group row">
              <label for="" class="form-control-label col-md-3">Book Cover (JPG Only)</label>
              <div class="col-md-9">
                <input type="file" name="book_cover" class="form-control">
              </div>
            </div>

            <div class="form-group row">
              <label for="" class="form-control-label col-md-3">Media File (PDF, MP4, OGG)</label>
              <div class="col-md-9">
                <input type="file" required name="media_file" class="form-control">
              </div>
            </div>

            <div class="form-group row">
              <label for="" class="form-control-label col-md-3">Tag</label>
              <div class="col-md-9">
                <input type="text" name="tag" class="form-control" value="{{Request::old('tag')}}" placeholder="create multiple tag with comma">
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

