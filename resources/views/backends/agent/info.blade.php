@include('backends.alert')
<div class="form-group row">
    <label for="name" class="col-md-4 col-form-label text-md-right">ชื่อ - นามสกุล <span class="text-danger">*</span></label>

    <div class="col-md-4">
        <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ @$agent->name ? @$agent->name : old('name') }}" required autofocus>
        @include('backends.error-message' ,['error_message' => $errors->first('name')])
    </div>
</div>

<div class="form-group row">
    <label for="email" class="col-md-4 col-form-label text-md-right">อีเมล <span class="text-danger">*</span></label>

    <div class="col-md-4">
        <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }} " name="email" value="{{ @$agent->email ? @$agent->email : old('email') }}" required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" @if(@$agent->id) readonly @endif>
        @include('backends.error-message' ,['error_message' => $errors->first('email') ])

    </div>
</div>

<div class="form-group row">
    <label for="role" class="col-md-4 col-form-label text-md-right">หน้าที่ <span class="text-danger">*</span></label>

    <div class="col-md-4">
        <select id="role" type="role" class="form-control{{ $errors->has('role') ? ' is-invalid' : '' }}" name="role" value="{{ @$agent->role ? @$agent->role : old('role') }}" required style="text-transform:capitalize;">
            <option value="" disabled selected>เลือกหน้าที่</option>
            @foreach($roles as $role)
              <option value="{{ $role->name }}"  style="text-transform:capitalize;" @if(@$agent->role == $role->name) selected @endif >{{ $role->name }}</option>
            @endforeach
        </select>
        @include('backends.error-message' ,['error_message' => $errors->first('role')])

    </div>
</div>
@if(!@$agent->id || @$agent->id == @Auth::User()->id)
<div class="form-group row">
    <label for="password" class="col-md-4 col-form-label text-md-right">รหัสผ่าน <span class="text-danger">*</span></label>

    <div class="col-md-4">
        <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
        @include('backends.error-message' ,['error_message' =>  $errors->first('password') ])

    </div>
</div>

<div class="form-group row">
    <label for="password-confirm" class="col-md-4 col-form-label text-md-right">ยืนยันรหัสผ่าน <span class="text-danger">*</span></label>

    <div class="col-md-4">
        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
        @include('backends.error-message' ,['error_message' => 'กรุณายืนยันรหัสผ่าน'])
    </div>
</div>
@endif
