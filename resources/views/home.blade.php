@extends('layouts.profile')

@section('content')
<h1 class="text-center display-3 text-monospace text-muted">welcome aboard {{auth()->user()->name}}</h1>
@endsection
