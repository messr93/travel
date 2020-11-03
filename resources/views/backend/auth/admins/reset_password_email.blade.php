
@component('mail::message')
    # Reset Account
    Hello {{ $data['name'] }}
    Looks like you forgot your password, Click below button to reset it.

    @component('mail::button', ['url' => url('admin/reset/password/'.$data['token'])])
        Click Here To Reset Your Password
    @endcomponent
    Thanks
    {{ config('app.name') }}
@endcomponent
