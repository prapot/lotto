@extends('backends.layouts.master')

@section('content')

    <div class="c-body">
        <main class="c-main">
            <div class="container-fluid">
                <div id="ui-view">
                  <div>
                      <div class="fade-in">
                          <div class="card">
                              <div class="card-header">ยินดีต้อนรับ {{ auth::User()->name }} {{config('app.name')}}</div>
                          </div>
                      </div>
                  </div>
              </div>
            </div>
        </main>
    </div>
@endsection
