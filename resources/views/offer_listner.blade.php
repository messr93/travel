@extends('layouts.app')

@section('content')

    <h1>welcome aboard</h1>
    <h3 id="status_changed"></h3>
@endsection

@push('scripts')
    <script>
        Echo.private('offer.{{$id}}')
            .listen('OfferStatusChanged', (e) => {
                console.log(e);
                $('#status_changed').text('Your offer status changed to'+e.offer.status);
            });
    </script>
@endpush
