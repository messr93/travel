<div class="card">
    <div class="card-header">
        <img src="{{ url('uploads/frontend/users/profile_picture/'.auth()->user()->photo) }}" class="img-rounded img-thumbnail mx-auto d-block mw-100">
    </div>
    <div class="card-body">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item has-treeview  menu-open">
                <a href="#" class="nav-link active">
                    <i class="nav-icon fas fa-user-edit"></i>
                    <p>
                        {{ __('frontend.Profile Settings') }}
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('editProfileInfo') }}" class="nav-link">
                            <i class="fa fa-user-cog nav-icon"></i>
                            <p>{{ __('frontend.edit profile') }}</p>
                        </a>
                    </li>
                </ul>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('editProfileEmail') }}" class="nav-link">
                            <i class="fa fa-key nav-icon"></i>
                            <p>{{ __('frontend.change email') }}</p>
                        </a>
                    </li>
                </ul>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('editProfilePassword') }}" class="nav-link">
                            <i class="fa fa-key nav-icon"></i>
                            @not_social_user()
                                <p>{{ __('frontend.change password') }}</p>
                            @else
                                <p>{{ __('frontend.set new password') }}</p>
                             @endnot_social_user()
                        </a>
                    </li>
                </ul>
            </li>
            @not_customer()
            <li class="nav-item has-treeview  menu-open">
                <a href="{{ route('userOffers.all') }}" class="nav-link active">
                    <i class="nav-icon fas fa-user-edit"></i>
                    <p>
                        {{ __('frontend.Offers') }}
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview ">
                    <li class="nav-item">
                        <a href="{{ route('userOffers.all') }}" class="nav-link">
                            <i class="fa fa-key nav-icon"></i>
                            <p>{{ __('frontend.My Offers') }}</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('userOffers.create') }}" class="nav-link">
                            <i class="fa fa-user-secret nav-icon"></i>
                            <p>{{ __('frontend.Create Offer') }}</p>
                        </a>
                    </li>
                </ul>
            </li>
            @endnot_customer()
        </ul>
    </div>
</div>
