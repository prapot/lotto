@if (session()->has('error_message'))
    @php
    $error_message = session()->get('error_message');
    $type = (! empty($error_message['type']))?$error_message['type'] :'danger';
    $content = $error_message['message'];
    @endphp
    <div class="col-12 mt-3">
        <div class="alert alert-{{ $type }} alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              <h5><i class="icon fa fa-check"></i> Alert!</h5>
              {!! $content !!}
        </div>
    </div>
@endif
