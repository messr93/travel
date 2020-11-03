

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
        <img src="{{asset('assets/admin/dist/img/AdminLTELogo.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
             style="opacity: .8">
        <span class="brand-text font-weight-light">AdminLTE 3</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{asset('assets/admin/dist/img/user3-128x128.jpg')}}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">
                    @if(auth()->guard('admin')->check())
                        {{ auth()->guard('admin')->user()->name }}
                    @endif
                </a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->
                <li class="nav-item has-treeview">
                    <a href="{{ route('admins.all') }}" class="nav-link active">
                        <i class="nav-icon fas fa-shield-alt"></i>
                        <p>
                            {{ __('backend.Admins') }}
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admins.all') }}" class="nav-link">
                                <i class="fa fa-list nav-icon"></i>
                                <p>{{ __('backend.All admins') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admins.create') }}" class="nav-link">
                                <i class="fa fa-plus nav-icon"></i>
                                <p>{{ __('backend.Create admin') }}</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item has-treeview menu-open">
                    <a href="{{ route('offers.all') }}" class="nav-link active">
                        <i class="nav-icon fas fa-cloud-moon"></i>
                        <p>
                            {{ __('backend.Offers') }}
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('offers.all') }}" class="nav-link">
                                <i class="fa fa-list nav-icon"></i>
                                <p>{{ __('backend.All offers') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('offers.create') }}" class="nav-link">
                                <i class="fa fa-plus nav-icon"></i>
                                <p>{{ __('backend.Create offer') }}</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item has-treeview">
                    <a href="{{ route('programs.all') }}" class="nav-link active">
                        <i class="nav-icon fas fa-cloud-moon"></i>
                        <p>
                            {{ __('backend.Programs') }}
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('programs.all') }}" class="nav-link">
                                <i class="fa fa-list nav-icon"></i>
                                <p>{{ __('backend.All programs') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('programs.create') }}" class="nav-link">
                                <i class="fa fa-plus nav-icon"></i>
                                <p>{{ __('backend.Create program') }}</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item has-treeview">
                    <a href="{{ route('trips.all') }}" class="nav-link active">
                        <i class="nav-icon fas fa-table-tennis"></i>
                        <p>
                            {{ __('backend.Trips') }}
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('trips.all') }}" class="nav-link">
                                <i class="fa fa-list nav-icon"></i>
                                <p>{{ __('backend.All trips') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('trips.create') }}" class="nav-link">
                                <i class="fa fa-plus nav-icon"></i>
                                <p>{{ __('backend.Create trip') }}</p>
                            </a>
                        </li>
                    </ul>
                </li>


                <li class="nav-header">LABELS</li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon far fa-circle text-danger"></i>
                        <p class="text">Important</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon far fa-circle text-warning"></i>
                        <p>Warning</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon far fa-circle text-info"></i>
                        <p>Informational</p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
