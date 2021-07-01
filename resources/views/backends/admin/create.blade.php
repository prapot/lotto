@extends('backends.layouts.master')
@section('page_title', "Create Admin")
@section('content')
<form id="admin-create" action="{{route('backends.admin.store')}}" method="post" class="needs-validation" novalidate enctype="multipart/form-data">
    @csrf

    <div class="card-header py-3">
        <div class="row">
            <div class="col-5 col-md-5 d-flex justify-content-md-start align-items-md-center">
                <h1 class="my-1 text-center text-md-left">สร้างผู้ดูแลระบบ</h1>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
          <div class="card">
              <div class="card-body">
                  <div class="form-horizontal">
                      <div class="form-body">
                            <div class="row">
                                <div class="col-md-12">
                                    @include('backends.admin.info')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-sm-3 offset-sm-3 col-md-2 offset-md-4">
                        <a href="{{ route('backends.admin.index') }}" class="btn btn-secondary btn-lg btn-block">Cancel</a>
                        </div>
                        <div class="col-12 col-sm-3 col-md-2">
                            <button type="submit" class="btn btn-primary btn-lg btn-block">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection('content')
