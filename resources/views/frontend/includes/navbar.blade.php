<nav class="navbar navbar-expand-md navbar-dark bg-dark shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ url('/') }}">
            {{ config('app.name', 'Travel') }}
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav m{{(langDir() === "rtl"? 'l': 'r')}}-auto">

            </ul>
            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav m{{(langDir() === "rtl"? 'r': 'l')}}-auto">
                <!-- Lang dropdown menu -->
                <li class="nav-item dropdown">
                    <span class="nav-link" data-toggle="dropdown">
                        {{currentLang()}}
                    </span>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        @foreach(\App\Models\Lang::where('status', 1)->select('name', 'code')->get() as $lang)
                            @if($lang->code == currentLang())
                                @continue
                            @endif
                            <a href="#" class="dropdown-item">              {{--add chang lang function here--}}
                                {{$lang->name}}
                            </a>
                        @endforeach
                    </div>
                </li>
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}" style="padding-right: 0px">{{ __('Login') }}</a>
                    </li>
                    @if (Route::has('register'))

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}" style="padding-left: 6px">/ {{ __('Register') }}</a>
                        </li>
                    @endif
                @else
                    <!-- Messages Dropdown Menu -->
                    <li class="nav-item dropdown">
                        <a class="nav-link" data-toggle="dropdown" href="#">
                            <i class="far fa-comments"></i>
                            <span class="badge badge-primary navbar-badge">{{--3--}}</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                            <a href="#" class="dropdown-item">
                                <!-- Message Start -->
                                <div class="media">
                                    <img src="{{asset('assets/admin/dist/img/user1-128x128.jpg')}}" alt="User Avatar" class="img-size-50 mr-3 img-circle">
                                    <div class="media-body">
                                        <h3 class="dropdown-item-title">
                                            Brad Diesel
                                            <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                                        </h3>
                                        <p class="text-sm">Call me whenever you can...</p>
                                        <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                                    </div>
                                </div>
                                <!-- Message End -->
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item">
                                <!-- Message Start -->
                                <div class="media">
                                    <img src="{{asset('assets/admin/dist/img/user8-128x128.jpg')}}" alt="User Avatar" class="img-size-50 img-circle mr-3">
                                    <div class="media-body">
                                        <h3 class="dropdown-item-title">
                                            John Pierce
                                            <span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
                                        </h3>
                                        <p class="text-sm">I got your message bro</p>
                                        <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                                    </div>
                                </div>
                                <!-- Message End -->
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item">
                                <!-- Message Start -->
                                <div class="media">
                                    <img src="{{asset('assets/admin/dist/img/user3-128x128.jpg')}}" alt="User Avatar" class="img-size-50 img-circle mr-3">
                                    <div class="media-body">
                                        <h3 class="dropdown-item-title">
                                            Nora Silvester
                                            <span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
                                        </h3>
                                        <p class="text-sm">The subject goes here</p>
                                        <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                                    </div>
                                </div>
                                <!-- Message End -->
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
                        </div>
                    </li>
                    <!-- Notifications Dropdown Menu -->
                    <li class="nav-item dropdown">
                        <a class="nav-link" data-toggle="dropdown" href="#" id="notify-show">
                            <i class="far fa-bell"></i>
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
                                    <div class="row  text-truncate notify-title">
                                        <i class="fas fa-bell mr-2"></i>
                                        @if(is_null($notification->read_at))
                                            <span class="notify-unread text-bold">{{$notification->data['notification_title']}}</span>
                                        @else
                                            {{$notification->data['notification_title']}}
                                        @endif
                                    </div>
                                    <div class="row overflow-hidden text-truncate notify-time">
                                        <span class="float-right text-muted text-sm">{{ $notification->created_at->diffForHumans() }}</span>
                                    </div>
                                </a>
                                <div class="dropdown-divider"></div>
                            @endforeach
                            {{--<a href="#" class="dropdown-item dropdown-footer" id="notify-see-all">See All Notifications</a>--}}
                        </div>
                    </li>
                    <!-- Authentication Links -->
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre style="padding: 6px">
                            <img src="{{ url('uploads/frontend/users/profile_picture/'.auth()->user()->photo) }}" class="img-circle" style="height: 30px; width:30px ">
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
            </ul>
        </div>
    </div>
</nav>
