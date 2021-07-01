@extends('backends.layouts.master')
@section('page_title', "Host")
@section('content')

<div class="card" id="host-page">

  <div class="card-header">
    <div class="row">
      <div class="col-5 col-md-5 d-flex justify-content-md-start align-items-md-center">
        <h3 class="my-1 text-center text-md-left">รายชื่อห้อง</h3>
      </div>
    </div>
  </div>
  <!-- /.card-header -->

  <div class="card-body">

    @include('backends.alert')

    <legend class="the-legend">ห้อง Line</legend>
    <host
      oldhosts="{{ json_encode($hosts) }}"
      close_input="false"
    ></host>
  </div>
  <!-- /.card-body -->

</div>
@endsection('content')
