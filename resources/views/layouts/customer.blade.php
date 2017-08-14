<!DOCTYPE html>
<html>
<head>
    <title>Fashion</title>
    <!-- for-mobile-apps -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="apple-touch-icon" sizes="76x76" href="{{asset('assets/admin/img/apple-icon.png')}}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="icon" type="image/png" sizes="96x96" href="{{asset('assets/admin/img/favicon.png')}}">

    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>

    <meta name="keywords" content="Fashion"/>
    {{--<script type="application/x-javascript"> addEventListener("load", function () {--}}
            {{--setTimeout(hideURLbar, 0);--}}
        {{--}, false);--}}
        {{--function hideURLbar() {--}}
            {{--window.scrollTo(0, 1);--}}
        {{--} </script>--}}
    <!-- //for-mobile-apps -->
    <link href="{{asset('assets/website/css/bootstrap.css')}}" rel="stylesheet" type="text/css" media="all"/>
    <link href="{{asset('assets/website/css/style.css')}}" rel="stylesheet" type="text/css" media="all"/>
    <link href="{{asset('assets/website/css/fasthover.css')}}" rel="stylesheet" type="text/css" media="all"/>
    <!-- js -->
    <script src="{{asset('assets/website/js/jquery.min.js')}}"></script>
    <script src="{{asset('assets/website/js/pagination.js')}}"></script>
    <script src="{{asset('assets/admin/js/bootstrap-notify.js')}}"></script>

    <script src="{{asset('assets/admin/js/demo.js')}}"></script>

    <!-- //js -->
    <!-- countdown -->
    <link rel="stylesheet" href="{{asset('assets/website/css/jquery.countdown.css')}}"/>
    <!-- //countdown -->
    <!-- cart -->
    <script src="{{asset('assets/website/js/simpleCart.min.js')}}"></script>
    <!-- cart -->
    <!-- for bootstrap working -->
    <script type="text/javascript" src="{{asset('assets/website/js/bootstrap-3.1.1.min.js')}}"></script>
    <!-- //for bootstrap working -->
    <link href='//fonts.googleapis.com/css?family=Glegoo:400,700' rel='stylesheet' type='text/css'>
    <link href='//fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic,800,800italic'
          rel='stylesheet' type='text/css'>
    <!-- start-smooth-scrolling -->

    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $(".scroll").click(function (event) {
                event.preventDefault();
                $('html,body').animate({scrollTop: $(this.hash).offset().top}, 1000);
            });
        });
    </script>
    <style>
        .amount-box{
            width: 100%;
            max-width: 400px;
        }
        .amount-box input[type=text], .amount-box input[type=tel]{
            display: block;
            border: 1px solid #ccc;
            padding: 8px 10px;
            width: 100%;
            font-size: 16px;
            color: #272727;
            -webkit-appearance: none;
            border-radius: 0;
        }
        .colorSquare {

            width: 15px;

            height: 15px;

            display: inline-flex;

            border-radius: 5px;

            box-shadow: 0 0 5px #888888;

        }



        #productColor {

            background-color: buttonface;

            border-width: 1px;

            border-style: solid;

            border-color: rgb(169, 169, 169);

            border-image: initial;

            padding: 1px 2px;

        }
    </style>
    <!-- //end-smooth-scrolling -->
</head>

<body>
<!-- header -->
<div class="modal fade" id="myModal88" tabindex="-1" role="dialog" aria-labelledby="myModal88"
     aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="myModalLabel">
                    Don't Wait, Login now!</h4>
            </div>
            <div class="modal-body modal-body-sub">
                <div class="row">
                    <div class="col-md-8 modal_body_left modal_body_left1"
                         style="border-right: 1px dotted #C2C2C2;padding-right:3em;">
                        <div class="sap_tabs">
                            <div id="horizontalTab" style="display: block; width: 100%; margin: 0px;">
                                <ul>
                                    <li class="resp-tab-item" aria-controls="tab_item-{{($errors->first('status') == 'register')?'1':'0'}}"><span>{{($errors->first('status') == 'register')?'Sign Up':'Sign In'}}</span></li>
                                    <li class="resp-tab-item" aria-controls="tab_item-{{($errors->first('status') == 'register')?'0':'1'}}"><span>{{($errors->first('status') == 'register')?'Sign In':'Sign Up'}}</span></li>
                                </ul>
                                <div id="signInDiv" class="tab-1 resp-tab-content" aria-labelledby="tab_item-0">
                                    <div class="facts">
                                        <div class="register">
                                            <form action="{{route('customer_login')}}" method="post">
                                                {{ csrf_field() }}

                                                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                                        <input id="email" type="email" placeholder="Email" name="email" value="{{ old('email') }}" required>
                                                        @if ($errors->has('email') && $errors->first('status') != 'register')
                                                            <span class="help-block">
                                                                <strong>{{ $errors->first('email') }}</strong></span>
                                                        @endif
                                                </div>

                                                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">

                                                        <input id="password" type="password" placeholder="Password" name="password" required>
                                                        @if ($errors->has('password') && $errors->first('status') != 'register')
                                                            <span class="help-block">
                                                                <strong>{{ $errors->first('password') }}</strong>
                                                            </span>
                                                        @endif
                                                </div>
                                                <div class="form-group">
                                                    <br>
                                                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : ''}}> Remember Me
                                                </div>
                                                <div class="sign-up">
                                                    <br>
                                                    <input type="submit" value="Sign In"/>
                                                </div>

                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div id="signUpDiv" class="tab-2 resp-tab-content" aria-labelledby="tab_item-1">
                                    <div class="facts">
                                        <div class="register">
                                            <form action="{{route('customer_register')}}" method="post">
                                                {{ csrf_field() }}
                                                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                                        <input id="email" type="email" placeholder="Email" name="email" value="{{ old('email') }}" required>
                                                        @if ($errors->has('email') && $errors->first('status') == 'register')
                                                            <span class="help-block">
                                                                <strong>{{ $errors->first('email') }}</strong></span>
                                                        @endif
                                                </div>

                                                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">

                                                        <input id="password" type="password" placeholder="Password" name="password" required>
                                                        @if ($errors->has('password') && $errors->first('status') == 'register')
                                                            <span class="help-block">
                                                                <strong>{{ $errors->first('password') }}</strong>
                                                            </span>
                                                        @endif
                                                </div>

                                                <div class="form-group">
                                                        <input id="password-confirm" type="password" placeholder="Confirm Password" name="password_confirmation" required>
                                                </div>
                                                <div class="form-group{{ $errors->has('gender') ? ' has-error' : '' }}">

                                                        <div class="form-control" style="box-shadow: none; border: none; background-color: transparent;">
                                                            <br>
                                                            <input type="radio" id="gender" name="gender" value="male">
                                                            Male &nbsp; &nbsp;
                                                            <input type="radio" id="gender" name="gender" value="female">
                                                            Female
                                                            @if ($errors->has('gender') && $errors->first('status') == 'register')
                                                                <span class="help-block">
                                                                    <strong>{{ $errors->first('gender') }}</strong>
                                                                </span>
                                                            @endif
                                                        </div>
                                                </div>
                                                <div class="sign-up">
                                                    <br>
                                                    <input type="submit" value="Sign up"/>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <script src="{{asset('assets/website/js/easyResponsiveTabs.js')}}"
                                type="text/javascript"></script>
                        <script type="text/javascript">
                            $(document).ready(function () {
                                $('#horizontalTab').easyResponsiveTabs({
                                    type: 'default', //Types: default, vertical, accordion
                                    width: 'auto', //auto or any width like 600px
                                    fit: true   // 100% fit in a container
                                });
                            });
                        </script>
                        <div id="OR" class="hidden-xs">
                            OR
                        </div>
                    </div>
                    <div class="col-md-4 modal_body_right modal_body_right1">
                        <div class="row text-center sign-with">
                            <div class="col-md-6">
                                <h3 class="other-nw">
                                    Sign in with</h3>
                            </div>
                            <div class="col-md-10">
                                <ul class="social">
                                    <li class="social_facebook"><a  href="{{route('socialAuth',['provider'=>'facebook'])}}" class="entypo-facebook"></a></li>
                                    {{--<li class="social_dribbble"><a  href="{{route('socialAuth',['provider'=>'google'])}}" class="entypo-dribbble"></a></li>--}}
                                    {{--<li class="social_twitter"><a  href="{{route('socialAuth',['provider'=>'twitter'])}}" class="entypo-twitter"></a></li>--}}
                                    {{--<li class="social_behance"><a href="#" class="entypo-behance"></a></li>--}}
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    @if(count($errors->all()) > 0)
    $('#myModal88').modal('show');
    @if ($errors->first('status') == 'register')
    var div1 = $('#signUpDiv');
    var div2 = $('#signInDiv');
    var tdiv1 = div1.clone();
    var tdiv2 = div2.clone();
    div1.replaceWith(tdiv2);
    div2.replaceWith(tdiv1);
    tdiv1.addClass("replaced");
    @endif
    @endif
</script>
<div class="header" id="home">
    <div class="container">
        @if(!Auth::guard('customer')->check())
        <div class="w3l_login">
            <a href="#" data-toggle="modal" data-target="#myModal88"><span class="glyphicon glyphicon-user"
                                                                           aria-hidden="true"></span></a>
        </div>
        @endif
            @if(Auth::guard('customer')->check())
            <div class="w3l_login">
                <a href="{{ route('customer_logout') }}"

                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    Sign Out
                </a>
                <form id="logout-form" action="{{ route('customer_logout') }}" method="POST"

                      style="display: none;">

                    {{ csrf_field() }}

                </form>
            </div>
            @endif

            <div class="w3l_logo">
            <h1><a href="{{route('index')}}">Fashion<span>For Fashion Lovers</span></a></h1>
            </div>
        {{--<div class="search">--}}
            {{--<input class="search_box" type="checkbox" id="search_box">--}}
            {{--<label class="icon-search" for="search_box"><span class="glyphicon glyphicon-search"--}}
                                                              {{--aria-hidden="true"></span></label>--}}
            {{--<div class="search_form">--}}
                {{--<form action="#" method="post">--}}
                    {{--<input type="text" name="Search" placeholder="Search...">--}}
                    {{--<input type="submit" value="Send">--}}
                {{--</form>--}}
            {{--</div>--}}
        {{--</div>--}}
        <div class="cart box_1">
            <a id="myCart" href="javascript:;">
                <div class="total">
                    EGP
                    @if(Auth::guard('customer')->check())
                    <span id="cartTotalMoney">{{$cartTotalPrice}}</span> (<span id="cartTotalItems">{{$cartItemsCount}}</span> items)
                    @else
                        <span id="cartTotalMoney">0.00</span> (<span id="cartTotalItems">0</span> items)
                    @endif
                </div>
                <img src="{{asset('assets/website/images/bag.png')}}" alt=""/>
            </a>
            <p><a href="javascript:;" id="emptyCart" class="simpleCart_empty" >Empty Cart</a></p>
            <div class="clearfix"></div>
        </div>
            <div class="clearfix"></div>
        <script>
            $('#emptyCart').click(function () {
                var itemsCount = parseInt($('#cartTotalItems').text());
                $.ajax({
                    type: 'GET',
                    url: '{{route('emptyCart')}}',
                    data: {},
                    success: function (data) {
                        $('#totalPrice').text(0);
                        $('#cartTotalMoney').text('0.00');
                        $('#cartTotalItems').text(0);
                        for (var i = 1 ; i <= itemsCount ; i++ ) {
                            $('.rem' + i).remove();
                            $('#list' + (i - 1)).remove();
                        }

                        $('#shoppingItemsNumber').text(0);
                    }
                });
            });
        </script>
    </div>
</div>
<script>
        $('#myCart').click(function () {
            var url = '{{route('myCart')}}';
            @if(Auth::guard('customer')->check())
                window.location.replace(url);
            @else
            $('#myModal88').modal('show');
            @endif
        });
</script>
<div class="navigation">
    <div class="container">
        <nav class="navbar navbar-default">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header nav_2">
                <button type="button" class="navbar-toggle collapsed navbar-toggle1" data-toggle="collapse"
                        data-target="#bs-megadropdown-tabs">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>

            <div class="collapse navbar-collapse" id="bs-megadropdown-tabs">
                <ul class="nav navbar-nav">
                    <li class="active"><a href="{{route('index')}}" class="{{(Route::getCurrentRoute()->getName() == 'index')?'act':''}}">Home</a></li>
                    <!-- Mega Menu -->
                    @foreach($categoriesWeb as $key => $category)
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle"  data-toggle="dropdown">{{$key}}<b class="caret"></b></a>
                            <ul class="dropdown-menu multi-column columns-3">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <ul class="multi-column-dropdown">
                                            <h6>Categories</h6>
                                            @foreach($category as $value)
                                            <li><a href="{{route('categoryProducts',['gender'=>$key,'categoryName'=>$value])}}">{{$value}}</a></li>
                                            @endforeach
                                        </ul>
                                    </div>

                                    <div class="col-sm-9">
                                        <ul class="multi-column-dropdown">
                                        @if($key == 'Men')
                                            <li>
                                                <img src="http://www.menstylefashion.com/wp-content/uploads/2016/02/Italian-Men-Fashion-Sense-Sunglasses-and-cup-of-Coffee.jpg" class="img-responsive">
                                            </li>
                                        @endif
                                        @if($key == 'Women')
                                            <li>
                                            <img src="http://www.michellebelau.ae/img/collection/wrinkeled-coat-ivory-michelle-belau.jpg" class="img-responsive">
                                            </li>
                                            @endif
                                        @if($key == 'Girls')
                                            <li>
                                            <img src="https://s-media-cache-ak0.pinimg.com/736x/24/ca/b6/24cab6e05caa6c08afb4f524ca8584ad--outfits-for-school-for-kids-girls-tennis-outfits.jpg" class="img-responsive">
                                            </li>
                                                @endif
                                        @if($key == 'Boys')
                                            <li>
                                            <img src="https://s-media-cache-ak0.pinimg.com/736x/ea/16/cc/ea16cce8aa9334eabe1f1b666db70a74--little-boy-style-boys-style.jpg" class="img-responsive">
                                            </li>
                                            @endif
                                        </ul>
                                    </div>

                                    {{--<div class="col-sm-4">--}}
                                        {{--<div class="w3ls_products_pos">--}}
                                            {{--<h4>50%<i>Off/-</i></h4>--}}
                                            {{--<img src="images/1.jpg" alt=" " class="img-responsive"/>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                    <div class="clearfix"></div>
                                </div>
                            </ul>
                        </li>
                    @endforeach

                    <li><a href="about.html">About Us</a></li>
                </ul>
            </div>
        </nav>
    </div>
</div>
<div class="banner" id="home1">
    <div class="container">
        <h3>fashions fade, <span>style is eternal</span></h3>
    </div>
</div>
@yield('content')
<div class="newsletter">
    <div class="container">
        <div class="col-md-6 w3agile_newsletter_left">
            <h3>Email us</h3>
            <p>We will reach you as soon as possible</p>
        </div>
        <div class="col-md-6 w3agile_newsletter_right">
            <form action="#" method="post">
                <input type="email" name="Email" value="Email" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Email';}" required="">
                <input type="submit" value="">
            </form>
        </div>
        <div class="clearfix"> </div>
    </div>
</div>
<div class="footer">
    <div class="container">
        <div class="w3_footer_grids">
            <div class="col-md-4 w3_footer_grid">
                <h3>Contact</h3>
                <p>Duis aute irure dolor in reprehenderit in voluptate velit esse.</p>
                <ul class="address">
                    <li><i class="glyphicon glyphicon-map-marker" aria-hidden="true"></i>1234k Avenue, 4th block, <span>New York City.</span>
                    </li>
                    <li><i class="glyphicon glyphicon-envelope" aria-hidden="true"></i><a
                                href="mailto:info@example.com">info@example.com</a></li>
                    <li><i class="glyphicon glyphicon-earphone" aria-hidden="true"></i>+1234 567 567</li>
                </ul>
            </div>
            <div class="col-md-4 w3_footer_grid">
                <h3>Information</h3>
                <ul class="info">
                    <li><a href="about.html">About Us</a></li>
                    <li><a href="mail.html">Contact Us</a></li>
                    <li><a href="short-codes.html">Short Codes</a></li>
                    <li><a href="faq.html">FAQ's</a></li>
                    <li><a href="products.html">Special Products</a></li>
                </ul>
            </div>
            {{--<div class="col-md-3 w3_footer_grid">--}}
                {{--<h3>Category</h>--}}
                {{--<ul class="info">--}}
                    {{--<li><a href="dresses.html">Dresses</a></li>--}}
                    {{--<li><a href="sweaters.html">Sweaters</a></li>--}}
                    {{--<li><a href="shirts.html">Shirts</a></li>--}}
                    {{--<li><a href="sarees.html">Sarees</a></li>--}}
                    {{--<li><a href="skirts.html">Shorts & Skirts</a></li>--}}
                {{--</ul>--}}
            {{--</div>--}}
            <div class="col-md-4 w3_footer_grid">
                <h3>Profile</h3>
                <ul class="info">
                    <li><a href="products.html">Summer Store</a></li>
                    <li><a href="checkout.html">My Cart</a></li>
                </ul>
                <h4>Follow Us</h4>
                <div class="agileits_social_button">
                    <ul>
                        <li><a href="#" class="facebook"> </a></li>
                        <li><a href="#" class="twitter"> </a></li>
                        <li><a href="#" class="google"> </a></li>
                    </ul>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="footer-copy">
        <div class="footer-copy1">
            <div class="footer-copy-pos">
                <a href="#home" class="scroll"><img src="{{asset('assets/website/images/arrow.png')}}" alt=" "
                                                     class="img-responsive"/></a>
            </div>
        </div>
        {{--<div class="container">--}}
        {{--<p>&copy; 2016 Women's Fashion. All rights reserved | Design by <a href="http://w3layouts.com/">W3layouts</a></p>--}}
        {{--</div>--}}
    </div>
</div>
<!-- //footer -->
</body>
</html>