<header>
    <div class="header-area">
        <div id="sticky-header" class="main-header-area" style="background-color: #f8f9fa; padding: 15px 35px">
            <div class="container-fluid">
                <div class="header_bottom_border">
                    <div class="row align-items-between">
                        <div class="col-xl-2 col-lg-2">
                            <div class="logo">
                                <a href="{{ url('/') }}">
                                    <img src="{{asset('assets/frontend/img/logo.png')}}" alt="">
                                </a>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6">
                            <div class="main-menu  d-none d-lg-block">
                                <nav>
                                    <ul id="navigation">
                                        <li><a class="active" href="{{ url('home') }}">home</a></li>
                                        <li><a href="about.html">About</a></li>
                                        <li><a class="" href="travel_destination.html">Destination</a></l/li>
                                        <li><a href="#">pages <i class="ti-angle-down"></i></a>
                                            <ul class="submenu">
                                                <li><a href="destination_details.html">Destinations details</a></li>
                                                <li><a href="elements.html">elements</a></li>
                                            </ul>
                                        </li>
                                        <li><a href="#">blog <i class="ti-angle-down"></i></a>
                                            <ul class="submenu">
                                                <li><a href="blog.html">blog</a></li>
                                                <li><a href="single-blog.html">single-blog</a></li>
                                            </ul>
                                        </li>
                                        <li><a href="contact.html">Contact</a></li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 d-none d-lg-block">
                            <div class="social_wrap d-flex align-items-center justify-content-end">
                                @guest()
                                    <div class="social_links d-none d-xl-block mt-2">
                                        <ul>
                                            <li><a href="#"> <i class="fa fa-facebook"></i> </a></li>
                                            <li><a href="#"> <i class="fa fa-google"></i> </a></li>
                                        </ul>
                                    </div>
                                    <div class="social_links d-none d-xl-block mt-2">
                                        <ul>
                                            <li><a href="{{ route('login') }}" style="margin-right: 2px"> Login </a></li> /
                                            <li><a href="{{ route('register') }}" style="margin-left: 2px">Register </a></li>
                                        </ul>
                                    </div>
                                @else()

                                        <!-- Messages Dropdown Menu -->
                                        <li class="nav-item dropdown">
                                            <a class="nav-link" data-toggle="dropdown" href="#">
                                                <i class="fa fa-comments"></i>
                                                <span class="badge badge-primary navbar-badge">{{--3--}}</span>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <span class="dropdown-item dropdown-header"><strong>Messages</strong></span>
                                                <div class="dropdown-divider"></div>
                                                <a href="#" class="dropdown-item message-link p-1" >
                                                    <div class="w-100 d-flex align-items-center">
                                                        <div class="flex-grow-1 text-truncate message-title">
                                                            <i class="fas fa-comments mr-2"></i>
                                                            Message from
                                                        </div>
                                                        <div class="ml-2 overflow-hidden message-time">
                                                            <span class="text-muted">mins ago</span>
                                                        </div>
                                                    </div>
                                                </a>
                                                <div class="dropdown-divider"></div>

                                            </div>
                                        </li>
                                        <!-- Notifications Dropdown Menu -->
                                        <li class="nav-item dropdown" style="margin-left: 0px">
                                            <a class="nav-link" data-toggle="dropdown" href="#" id="notify-show">
                                                <i class="fa fa-bell"></i>
                                                @if(auth()->user()->unreadNotifications->count() > 0)
                                                    <span class="badge badge-danger navbar-badge" id="notify-count">{{auth()->user()->unreadNotifications->count()}}</span>
                                                @else
                                                    <span class="badge badge-danger navbar-badge" id="notify-count"></span>
                                                @endif
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right p-2" id="notify-container" style="max-height: 300px ;overflow: auto">
                                                <span class="dropdown-item dropdown-header"><strong>Notifications</strong></span>
                                                <div class="dropdown-divider"></div>
                                                @foreach(auth()->user()->notifications()->orderBy('created_at', 'desc')->get() as $notification)
                                                    <a href="{{$notification->data['url']}}" class="dropdown-item notify-link" id="{{$notification->id}}">
                                                        <div class="d-flex align-items-center w-100">
                                                            <div class="flex-grow-1 text-truncate notify-title">
                                                                <i class="fas fa-bell mr-2"></i>
                                                                @if(is_null($notification->read_at))
                                                                    <span class="notify-unread text-bold">{{$notification->data['notification_title']}}</span>
                                                                @else
                                                                    {{$notification->data['notification_title']}}
                                                                @endif
                                                            </div>
                                                            <div class=" ml-2 overflow-hidden notify-time">
                                                                <span class="text-muted ">{{ $notification->created_at->diffForHumans() }}</span>
                                                            </div>
                                                        </div>
                                                    </a>
                                                    <div class="dropdown-divider"></div>
                                                @endforeach
                                                {{--<a href="#" class="dropdown-item dropdown-footer" id="notify-see-all">See All Notifications</a>--}}
                                            </div>
                                        </li>
                                        <!-- Authentication Links -->
                                        <li class="nav-item dropdown"  style="margin-left: 0px">
                                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown"   >
                                                <img src="{{ url('uploads/frontend/users/profile_picture/'.auth()->user()->photo) }}" class="img-circle" style="height: 30px; width:30px; border-radius: 15px ">
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                                <a class="dropdown-item" href="{{ route('home') }}">
                                                    {{ __('Profile') }}
                                                </a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item" href="{{ route('logout') }}"
                                                   onclick="event.preventDefault();
                                                        document.getElementById('logout-form').submit();">
                                                    {{ __('Logout') }}
                                                </a>
                                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                                    @csrf
                                                </form>
                                            </div>
                                        </li>

                                @endif
                            </div>

                        </div>
                        <div class="col-12">
                            <div class="mobile_menu d-block d-lg-none"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</header>
