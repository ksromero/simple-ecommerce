<nav class="navbar navbar-default">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Ecommerce</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
            <li class="active"><a href="{{ url('/') }}">Home <span class="sr-only">(current)</span></a></li>
            </ul>
            @guest
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="{{ route('login') }}">Login</a></li>
                    <li><a href="{{ route('register') }}">Register</a></li>
                    <li><a href="{{ route('getCart') }}"><i class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></i><span class="badge">
                        {{ session()->has('cart') ? session()->get('cart')->totalQty : ''}}
                    </span></a></li>
                </ul>
            @else
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="{{ route('getCart') }}"><i class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></i><span class="badge">
                            {{ session()->has('cart') ? session()->get('cart')->totalQty : ''}}
                    </span></a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{ Auth::user()->name }} <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ route('logout')  }}" onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">Logout</a></li>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                            </form>
                        </ul>
                    </li>
                </ul>
            @endguest
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>