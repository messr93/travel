@extends('layouts.profile')
@section('content')
    @include('frontend.includes.sessionsFlash')
    <div class="container mt-3">
        @if(session('social_user_note'))
            <div><span class="badge badge-danger">{{session()->pull('social_user_note')}}</span></div>
        @endif
        <form action="{{route('updateProfilePassword')}}" method="post" id="form-data">
            @csrf
            @not_social_user()
                <div class="form-group form-row">
                    <div class="col-sm-6 offset-sm-2">
                        <label for="current_password">Current password: </label>
                        <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password" placeholder="Current password">
                        @error('current_password')
                            <small id="valErr_current_password" class="text-danger valErr_current_password">{{$message}}</small>
                        @enderror
                    </div>
                </div>
            @endnot_social_user
            <div class="form-group form-row">
                <div class="col-sm-6 offset-sm-2">
                    <label for="current_password">New Password: </label>
                    <input type="password" class="form-control @error('new_password') is-invalid @enderror" id="new_password" name="new_password" placeholder="New Password">
                    @error('new_password')
                        <small id="valErr_new_password" class="text-danger valErr_new_password">{{$message}}</small>
                    @enderror
                </div>
            </div>
            <div class="form-group form-row">
                <div class="col-sm-6 offset-sm-2">
                    <label for="current_password">Confirm New password: </label>
                    <input type="password" class="form-control @error('new_password_confirmation') is-invalid @enderror" id="new_password_confirmation" name="new_password_confirmation" placeholder="Confirm New password">
                </div>
            </div>
            <input type="hidden" name="submit_type" value="{{session()->get('from_change_email', 'normal')}}">
            <button type="submit" value="uo" class="btn btn-info d-block mx-auto w-75 mt-5">Update</button>
        </form>
    </div>
@stop
