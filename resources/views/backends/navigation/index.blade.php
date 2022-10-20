@extends('backends.layouts.master')
@section('page_title', "navigation")
@section('content')

<div class="card" id="navigation-page">

  <div class="card-header">
    <div class="row">
      <div class="col-5 col-md-5 d-flex justify-content-md-start align-items-md-center">
        <h3 class="my-1 text-center text-md-left">Navigation Management</h3>
      </div>
    </div>
  </div>
  <!-- /.card-header -->

  <div class="card-body"> 
    <div class="row">
      <div class="col-4">
        <div class="list-group" id="list-tab" role="tablist">
        @foreach($productOurs as $key => $our)
          <a class="list-group-item list-group-item-action" id="{{$our->id}}" data-toggle="list" href="#ltp-{{$our->id}}" role="tab" aria-controls="home">{{$our->title_th}}</a>
        @endforeach
        </div>
      </div>
      <div class="col-8">
        <div class="tab-content" id="nav-tabContent">
          @foreach($productOurs as $key => $our)
            <div class="tab-pane fade" id="ltp-{{$our->id}}" role="tabpanel" aria-labelledby="{{$our->id}}">
              <ul class="list-group navigation" id="navigation-{{$our->id}}">
                @foreach($our->ProductAllCategory as $key => $category)
                  <li class="list-group-item cursor-pointer" data-id="{{$category->id}}">
                    <div class="row">
                      <div class="col-md-1 d-flex justify-content-center">
                        <span class="cil-resize-height m-auto"></span>
                      </div>
                      <div class="col-md-2 d-flex justify-content-center">
                        <img src="{{$category->ShowIcon}}" width="100" class="">
                      </div>
                      <div class="col-md-9 d-flex align-items-center">
                        {{$category->title_th}}
                      </div>
                    </div>
                  </li>
                @endforeach
              </ul>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
  <!-- /.card-body -->

</div>
@endsection('content')
