@extends('layouts.profile')
@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{session()->pull('success')}}</div>
    @endif
    <div class="container mt-3">
        <form action="{{route('updateProfileEmail')}}" method="post" id="form-data">
        @csrf
            <input type="hidden" id="old_email" name="old_email" value="{{auth()->user()->email}}">
            <div class="form-group form-row">
                <div class="col-sm-6 offset-sm-2">
                    <label for="new_email">New Email: </label>
                    <input type="email" class="form-control @error('new_email') is-invalid @enderror" id="new_email" name="new_email" value="{{auth()->user()->email}}" placeholder="New Email">
                    @error('new_email')
                    <small id="valErr_new_email" class="text-danger valErr_new_email">{{$message}}</small>
                    @enderror
                </div>
            </div>

            <button type="submit" value="uo" class="btn btn-info d-block mx-auto w-75 mt-5">Update</button>
        </form>
    </div>
@stop
