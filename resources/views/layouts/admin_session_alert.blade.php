@if(session()->has('message'))
    <div class="alert alert-{{ session('type')}} alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        {{ session('message') }}
    </div>
@endif
@if (session('status'))
    <div class="alert alert-success alert-dismissible">
        {{ session('status') }}
    </div>
@endif
@if (session('warning'))
    <div class="alert alert-warning alert-dismissible">
        <strong>{!! session('warning') !!}</strong>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>

        <ul>
        @foreach ($errors->all() as $error)
            <li> {{  $error }}</li>
        @endforeach
        </ul>
    </div>
@endif

