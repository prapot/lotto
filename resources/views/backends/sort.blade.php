@extends('backends.layouts.master')
@section('page_title', "Sort")
@section('content')
<div id="list-sort">
  <div class="row">
    <div class="col-md-12">
      <ul id="sortable" class="list-unstyled">
        @foreach($lists as $list)
        <li class="ui-state-default mt-2 position" data-order="{{$list->id}}">
              <div class="cursor-move">
                <i class="fa fa-arrows-v m-2"></i>
                <img src="{{$list->ShowImage}}" width="100" class="mr-4">
                <span>{{$list->title}}</span>
            </div>
        </li>
        @endforeach
      </ul>
    </div>
  </div>

  <div class="row">
      <div class="col-12">
          <div class="card">
              <div class="card-body">
                  <div class="row">
                      <div class="col-12 col-sm-3 offset-sm-3 col-md-2 offset-md-4">
                          <a href="{{ URL::previous() }}" class="btn btn-secondary btn-lg btn-block">Back</a>
                      </div>
                      <div class="col-12 col-sm-3 col-md-2">
                          <button type="button" class="btn btn-primary btn-lg btn-block" @click="save_position()">Save</button>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
</div>
@endsection('content')
@section('js')
<script>
  var page = '{{ $page }}'
</script>
@endsection('js')