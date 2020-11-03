@extends('layouts.profile')
@section('content')
    @include('frontend.includes.sessionsFlash')
    <div class="container mt-3">
    <form action="{{route('updateProfileInfo')}}" method="post" id="form-data" enctype="multipart/form-data">
        @csrf
        <input type="hidden" id="pass" name="pass" value="{{(!isset($user->password)? 'empty': '')}}">
        <div class="form-group form-row">
            <div class="col">
                <label for="name">User name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{$user->name}}" placeholder="user name">
                @error('name')
                    <small id="valErr_name" class="text-danger valErr_name">{{$message}}</small>
                @enderror
            </div>
            <div class="col">
                <label for="gender">Gender</label>
                <select class="form-control @error('gender') is-invalid @enderror" name="gender" id="gender">
                    <option value="{{$user->gender==1? 1: 0}}">{{$user->gender==1? 'Male': 'Female'}}</option>
                    <option value="{{$user->gender==1? 0: 1}}">{{$user->gender==1? 'Female': 'Male'}}</option>
                </select>
                @error('gender')
                <small id="valErr_gender" class="text-danger valErr_gender">{{$message}}</small>
                @enderror
            </div>
        </div>
        <div class="form-group form-row">
            <label for="address">Address</label>
            <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{$user->address}}" placeholder="Address">
            @error('address')
                <small id="valErr_address" class="text-danger valErr_address">{{$message}}</small>
            @enderror
        </div>
        <div class="form-group form-row">
            <label for="photo">Profile picture</label>
            <input type="file" class="form-control @error('photo') is-invaled @enderror" id="photo" name="photo">
            @error('photo')
            <small id="valErr_photo" class="text-danger valErr_photo">{{$message}}</small>
            @enderror
        </div>
        <div id="image_album" class="image_album">
            <img src="{{url('uploads/frontend/users/profile_picture/'.$user->photo)}}" id="previewImg" style="width: 150px; height: 150px;">
        </div>
        <button type="submit" class="btn btn-info btn-block mt-2" id="update_btn">Update</button>

    </form>
    </div>

@endsection
