<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Agroinform.TJ</title>

    <!-- Bootstrap -->
    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{asset('css/font-awesome.min.css')}}" rel="stylesheet">
    <!-- Custom Theme Style -->
    <link href="{{asset('css/custom.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/pnotify.css')}}" rel="stylesheet">
    <link href="{{asset('css/pnotify.buttons.css')}}" rel="stylesheet">
    <link href="{{asset('css/style.css')}}" rel="stylesheet">

    @yield("custom-css")
</head>

<body class="nav-md">
<div class="container body">
    <div class="main_container">
        <div class="col-md-3 left_col">
            <div class="left_col scroll-view">
                <div class="navbar nav_title" style="border: 0;">
                    <a href="index.html" class="site_title"> <span>Agroinform.TJ</span></a>
                </div>

                <div class="clearfix"></div>

                <!-- menu profile quick info -->
                <div class="profile clearfix">
                    <div class="profile_pic">
                        <img src="/images/{{Auth::user()->photo}}" alt="..." class="img-circle profile_img">
                    </div>
                    <div class="profile_info">
                        {{--<span>Welcome,</span>--}}
                        <h2>{{Auth::user()->fio}}</h2>
                    </div>
                </div>
                <!-- /menu profile quick info -->

                <br />

                <!-- sidebar menu -->
                <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                    <div class="menu_section">
                        <ul class="nav side-menu">
                            <li><a><i class="fa fa-pagelines"></i> Индекс хлопка <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="{{route('cottonindex-index')}}">Мировой</a></li>
                                    <li><a href="{{route('local-cottonindex-index')}}">Местный</a></li>
                                    <li><a href="{{route('suppliers.index')}}">Поставщик</a></li>
                                </ul>
                            </li>
                            <li><a href="{{route('pogoda-index')}}"><i class="fa fa-flash"></i> Погода</a>
                            <li><a><i class="fa fa-shopping-bag"></i> Рыночные цены <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="{{route('market-price-index')}}">Рыночные цены</a></li>
                                    <li><a href="{{route('countrymp-index')}}">Страны</a></li>
                                    <li><a href="{{route('marketmp-index')}}">Рынок</a></li>
                                    <li><a href="{{route('categorymp-index')}}">Категория продуктов</a></li>
                                    <li><a href="{{route('country-productmp-index')}}">Продукт страны</a></li>
                                    <li><a href="{{route('productmp-index')}}">Продукт</a></li>
                                    <li><a href="{{route('unitmp-index')}}">Единица измерения</a></li>
                                    <li><a href="{{route('languagemp-index')}}">Язык</a></li>
                                </ul>
                            </li>
                            <li><a><i class="fa fa-shopping-bag"></i> Цены на средства производства <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="{{route('categories.index')}}">Категория продуктов</a></li>
                                    <li><a href="{{route('units.index')}}">Единица измерения</a></li>
                                    <li><a href="{{route('shops.index')}}">Агромагазины</a></li>
                                    <li><a href="{{route('products.index')}}">Продукты</a></li>
                                    <li><a href="{{route('prices-index')}}">Цены на средства производства</a></li>
                                </ul>
                            </li>
                            <li><a><i class="fa fa-sliders"></i> Настройка <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="{{route('roles.index')}}">Группа</a></li>
                                    <li><a href="{{route('user-index')}}">Пользователи</a></li>
                                    <li><a href="{{route('permissions.index')}}">Операции</a></li>
                                    <li><a href="{{route('user-edit', Auth::user()->id)}}">Профиль</a></li>
                                </ul>
                            </li>

                        </ul>
                    </div>

                </div>
                <!-- /sidebar menu -->

            </div>
        </div>

        <!-- top navigation -->
        <div class="top_nav">
            <div class="nav_menu">
                <nav>
                    <div class="nav toggle">
                        <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                    </div>

                    <ul class="nav navbar-nav navbar-right">

                        <li class="">
                                <a class="" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    Выход
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                        </li>
                        <li class="">
                            <a>{{Auth::user()->email}}</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
        <!-- /top navigation -->

        <!-- page content -->
        <div class="right_col" role="main">

            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        @yield('breadcrumbs')
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>

        <!-- /page content -->


        <!-- footer content -->
        <footer>
            <div class="pull-right">
                @php echo "2009 - ". date("Y"); @endphp
            </div>
            <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
    </div>
</div>

<!-- jQuery -->
<script src="{{asset('js/jquery.min.js')}}"></script>
<!-- Bootstrap -->
<script src="{{asset('js/bootstrap.min.js')}}"></script>
<!-- Custom Theme Scripts -->
<script src="{{asset('js/custom.min.js')}}"></script>
<script src="{{asset('js/pnotify.js')}}"></script>
<script src="{{asset('js/pnotify.buttons.js')}}"></script>

<script>
    $(document).ready(function (){
        $('.ui-pnotify').remove();
    });

    @if (\Illuminate\Support\Facades\Session::has('create'))

    $(document).ready(function (){
            new PNotify({
                title: 'Запись успешно добавлена',
                text: '',
                type: 'success',
                styling: 'bootstrap3'
            });
    });
    @elseif (\Illuminate\Support\Facades\Session::has('update'))
    $(document).ready(function (){
        new PNotify({
            title: 'Запись успешно изменина',
            text: '',
            type: 'success',
            styling: 'bootstrap3'
        });
    });
    @elseif (\Illuminate\Support\Facades\Session::has('destroy'))
    $(document).ready(function (){
        new PNotify({
            title: 'Запись успешно удалена',
            text: '',
            type: 'success',
            styling: 'bootstrap3'
        });
    });
    @endif

</script>

@yield("custom-script")

</body>
</html>
