@if(session()->has('success'))
    <div id="alert_success" class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session()->has('fails'))
    <div id="alert_success" class="alert alert-danger">
        {{ session('fails') }}
    </div>
@endif
