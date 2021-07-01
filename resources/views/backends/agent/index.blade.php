@extends('backends.layouts.master')
@section('page_title', "Agent")
@section('content')

<div class="card" id="agent-page">

  <div class="card-header">
    <div class="row">
      <div class="col-5 col-md-5 d-flex justify-content-md-start align-items-md-center">
        <h3 class="my-1 text-center text-md-left">Agent</h3>
      </div>
      <div class="col-7 col-md-7 d-flex flex-wrap justify-content-end align-items-center">
        <a href="{{route('backends.agent.create')}}" class="btn btn-mobile-size btn-primary my-1 ml-2">
          <i class="fa fa-plus"></i>
          เพิ่ม Agent
        </a>
      </div>
    </div>
  </div>
  <!-- /.card-header -->

  <div class="card-body">

    <div class="row">
      <div class="col-12">
        <form action="{{ url()->current() }}" method="get">
          <div class="d-flex justify-content-end mb-1">
            <div class="flex-fill flex-sm-grow-0 pr-3"><input type="text" class="form-control" name="q"
                placeholder="ค้นหา" value="{{ @$_GET['q'] }}"></div>
            <div><button type="submit" class="btn btn-primary">ค้นหา</button></div>
          </div>
          <p class="text-muted small text-right mb-0">ค้นหา: ชื่อ, อีเมล</p>
        </form>
      </div>
    </div>
    @include('backends.alert')

    <div class="table-responsive my-3">
      <table id="data-table" class="table table-bordered table-striped display responsive">
        <thead>
          <tr>
            <th>ชื่อ</th>
            <th width="500">อีเมล</th>
            <th width="130">วันที่สร้าง</th>
            <th width="150">ตั้งค่า</th>
          </tr>
        </thead>
        <tbody>
          @foreach($agents as $agent)
          <tr>
            <td>
              {{$agent->name}}
            </td>
            <td>
              {{$agent->email}}
            </td>
            <td>
              {{$agent->created_at}}
            </td>
            <td>
              <button class="btn btn-info text-white" type="button" @click="editData({{$agent->id}})">ดูข้อมูล</button>
              <button class="btn btn-danger  text-white" type="button" @click="deleteData({{$agent->id}})">ลบ</button>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    @include('backends.paginator', ['paginator' => $agents])
  </div>
  <!-- /.card-body -->

</div>
@endsection('content')
