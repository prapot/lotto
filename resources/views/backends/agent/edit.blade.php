@extends('backends.layouts.master')
@section('page_title', "Edit Agent")
@section('content')
<form id="agent-edit" action="{{route('backends.agent.update',['id' => $agent->id])}}"  method="post" class="needs-validation" novalidate enctype="multipart/form-data">
    @csrf
    @method('put')
    <div class="card-header py-3">
        <div class="row">
            <div class="col-5 col-md-5 d-flex justify-content-md-start align-items-md-center">
                <h1 class="my-1 text-center text-md-left">Agent</h1>
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
                                <div class="col-12">
                                    @include('backends.agent.info')
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
                        <a href="{{ route('backends.agent.index') }}" class="btn btn-secondary btn-lg btn-block">Cancel</a>
                        </div>
                        <div class="col-12 col-sm-3 col-md-2">
                            <button type="submit" class="btn btn-primary btn-lg btn-block">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card-header py-3">
        <div class="row">
            <div class="col-5 col-md-5 d-flex justify-content-md-start align-items-md-center">
                <h1 class="my-1 text-center text-md-left">รายชื่อห้อง</h1>
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
                                <div class="col-12">
                                    <host
                                        oldhosts="{{ json_encode($hosts) }}"
                                        close_input="true"
                                        games="{{ json_encode(config('game')) }}"
                                    ></host>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card-header py-3">
        <div class="row">
            <div class="col-5 col-md-5 d-flex justify-content-md-start align-items-md-center">
                <h1 class="my-1 text-center text-md-left">สูตร</h1>
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
                                <div class="col-12">
                                    <formula
                                        oldformulas="{{ json_encode($formulas) }}"
                                        close_input="true"
                                    ></formula>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection('content')
