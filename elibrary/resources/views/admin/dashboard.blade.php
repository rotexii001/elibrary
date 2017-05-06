@extends('layout.master')
@section('html_class','')
@section('title','eLibrary System | Admin Dashboard')
@section('bodyClass','class="layout-container ls-top-navbar si-l3-md-up"')
@section('headerfile_unauth')
 @parent
@endsection

@section('pageLevelCss')
<!-- Vendor CSS -->
  <link rel="stylesheet" href="{{URL::asset('examples/css/morris.min.css')}}">
@endsection

@section('contentData')
 
 @include('includes.navBarMenu')

 @include('includes.adminSidebar')

<div class="layout-content" data-scrollable>
    <div class="container-fluid">

      <ol class="breadcrumb hidden-print">
        <li><a href="#">Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
      <div class="row">
        <div class="col-sm-6">
          <div class="card">
            <div class="card-header bg-white">
              <h4 class="card-title">Earnings</h4>
              <p class="card-subtitle">Last 30 Days</p>
            </div>
            <div class="card-block">
              <div id="stats" style="height:200px"></div>
            </div>
          </div>
          <div class="card">
            <div class="card-header bg-white">
              <h4 class="card-title">Latest Transactions</h4>
            </div>
            <table class="table text-subhead v-middle">
              <tbody>
                <tr>
                  <td>
                    <div class="label label-default">12 Jan 2015</div>
                  </td>
                  <td>Adrian Demian</td>
                  <td class="center"><a href="#">#8734</a></td>
                  <td class="center">$89</td>
                </tr>
                <tr>
                  <td>
                    <div class="label label-default">12 Jan 2015</div>
                  </td>
                  <td>Adrian Demian</td>
                  <td class="center"><a href="#">#6616</a></td>
                  <td class="center">$54</td>
                </tr>
                <tr>
                  <td>
                    <div class="label label-default">12 Jan 2015</div>
                  </td>
                  <td>Adrian Demian</td>
                  <td class="center"><a href="#">#12638</a></td>
                  <td class="center">$13</td>
                </tr>
                <tr>
                  <td>
                    <div class="label label-default">12 Jan 2015</div>
                  </td>
                  <td>Adrian Demian</td>
                  <td class="center"><a href="#">#12089</a></td>
                  <td class="center">$84</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="card">
            <div class="card-header bg-white">
              <div class="media">
                <div class="media-body">
                  <h4 class="card-title">Course Stats</h4>
                  <p class="card-subtitle">Sales Today</p>
                </div>
                <div class="media-right media-middle">
                  <a class="btn btn-white" href="#"> Manage</a>
                </div>
              </div>
            </div>
            <ul class="list-group list-group-fit m-b-0">
              <li class="list-group-item">
                <div class="media">
                  <div class="media-body media-middle">
                    <a href="take-course.html">Basics of HTML</a>
                  </div>
                  <div class="media-right media-middle">
                    <div class="center">
                      <span class="label label-pill label-primary">15</span>
                    </div>
                  </div>
                </div>
              </li>
              <li class="list-group-item">
                <div class="media">
                  <div class="media-body media-middle">
                    <a href="take-course.html">Angular in Steps</a>
                  </div>
                  <div class="media-right media-middle">
                    <div class="center">
                      <span class="label label-pill label-success">50</span>
                    </div>
                  </div>
                </div>
              </li>
              <li class="list-group-item">
                <div class="media">
                  <div class="media-body media-middle">
                    <a href="take-course.html">Bootstrap Foundations</a>
                  </div>
                  <div class="media-right media-middle">
                    <div class="center">
                      <span class="label label-pill label-warning">14</span>
                    </div>
                  </div>
                </div>
              </li>
              <li class="list-group-item">
                <div class="media">
                  <div class="media-body media-middle">
                    <a href="take-course.html">GitHub Basics</a>
                  </div>
                  <div class="media-right media-middle">
                    <div class="center">
                      <span class="label label-pill label-danger">14</span>
                    </div>
                  </div>
                </div>
              </li>
            </ul>
          </div>
          <div class="card">
            <div class="card-header bg-white">
              <div class="pull-xs-right">
                <a href="#" class="btn btn-primary btn-sm"><i class="material-icons">keyboard_arrow_left</i></a>
                <a href="#" class="btn btn-primary btn-sm"><i class="material-icons">keyboard_arrow_right</i></a>
              </div>
              <h4 class="card-title">Latest Comment</h4>
            </div>
            <div class="p-a-1">
              <div class="pull-xs-right">
                <small class="text-muted">27 min ago</small>
              </div>
              <a href="#">mosaicpro</a>
              <small class="text-muted"> on Course: <a href="#">Github Basics</a></small>
              <p class="m-b-0">How can I load Charts on a page?</p>
            </div>
            <div class="card-footer">
              <input type="text" class="form-control" placeholder="Quick Reply">
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
@endsection

@section('pageLevelJs')
<!-- Theme Colors -->
  <script src="{{URL::asset('assets/js/colors.js')}}"></script>

  <!-- Required by CHART (morrisjs) -->
  <script src="{{URL::asset('assets/vendor/raphael-min.js')}}"></script>
  <script src="{{URL::asset('assets/vendor/morris.min.js')}}"></script>

  <!-- Init -->
  <script src="{{URL::asset('examples/js/chart.js')}}"></script>
@endsection

