<!doctype html><html lang="en"><head>    <meta charset="utf-8"/>    <link rel="apple-touch-icon" sizes="76x76" href="{{asset('assets/admin/img/apple-icon.png')}}">    <link rel="icon" type="image/png" sizes="96x96" href="{{asset('assets/admin/img/favicon.png')}}">    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>    <meta name="csrf-token" content="{{ csrf_token() }}">    <title>Store</title>    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport'/>    <meta name="viewport" content="width=device-width"/>    <link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">    <link rel="stylesheet" href="{{asset('assets/admin/css/carousel.css')}}">    <link rel="stylesheet" href="{{asset('assets/admin/css/popup.css')}}">    <link rel="stylesheet" href="{{asset('assets/admin/fancybox/jquery.fancybox.css')}}">    <style>        .selected img {            opacity: 0.5;        }        .colorSquare {            width: 20px;            height: 20px;            display: inline-flex;            border-radius: 5px;            box-shadow: 0 0 5px #888888;        }        #productColor {            background-color: buttonface;            border-width: 1px;            border-style: solid;            border-color: rgb(169, 169, 169);            border-image: initial;            padding: 1px 2px;        }        .fileUpload {            position: relative;            overflow: hidden;            margin: 10px;        }        .fileUpload input.upload {            position: absolute;            top: 0;            right: 0;            margin: 0;            padding: 0;            font-size: 20px;            cursor: pointer;            opacity: 0;            filter: alpha(opacity=0);        }        #certain li {            border-radius: 0px;        }        input[type=checkbox]:not(old),        input[type=radio   ]:not(old) {            width: 28px;            margin: 0;            padding: 0;            opacity: 0;        }        #map {            height: 100%;        }        input[type=checkbox]:not(old) + label,        input[type=radio   ]:not(old) + label {            display: inline-block;            margin-left: -28px;            padding-left: 28px;            background: url({{asset('assets/admin/images/checks.png')}}) no-repeat 0 0;            line-height: 24px;        }        input[type=checkbox]:not(old):checked + label {            background-position: 0 -24px;        }        input[type=radio]:not(old):checked + label {            background-position: 0 -48px;        }    </style>    <!-- Bootstrap core CSS     -->    <link href="{{asset('assets/admin/css/bootstrap.min.css')}}" rel="stylesheet"/>    <!-- Animation library for notifications   -->    <link href="{{asset('assets/admin/css/animate.min.css')}}" rel="stylesheet"/>    <!--  Paper Dashboard core CSS    -->    <link href="{{asset('assets/admin/css/paper-dashboard.css')}}" rel="stylesheet"/>    <!--  Fonts and icons     -->    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">    <link href='https://fonts.googleapis.com/css?family=Muli:400,300' rel='stylesheet' type='text/css'>    <link href="{{asset('assets/admin/css/themify-icons.css')}}" rel="stylesheet">    <meta name="csrf-token" content="{{ csrf_token() }}"/>    <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>    <script src="https://js.pusher.com/4.1/pusher.min.js"></script>    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">    <script>        // Ensure CSRF token is sent with AJAX requests        $.ajaxSetup({            headers: {                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')            }        });        // Added Pusher logging        var pusher = new Pusher('9f182cebbd2f9bd2fde7', {            cluster: 'eu',            encrypted: true        });        var channel = pusher.subscribe('{{auth()->user()->id}}');        channel.bind('event', function (data) {            document.getElementById('notificationBadge').display = true;            if (document.getElementById('notificationBadge').innerHTML)                document.getElementById('notificationBadge').innerHTML = parseInt(document.getElementById('notificationBadge').innerHTML) + 1;            else                document.getElementById('notificationBadge').innerHTML = '1';            demo.showNotification(data.message, 'top', 'left');            console.log(data.message);            $('#notifications').prepend("<li><a href='"+data.url+"' style='background: lightblue;'>"+data.message+"</a></li>")        });        //        Pusher.log = function(msg) {        //            console.log(msg);        //        //       };    </script></head><body><div class="wrapper">    <div class="sidebar" data-background-color="white" data-active-color="danger">        <!--            Tip 1: you can change the color of the sidebar's background using: data-background-color="white | black"            Tip 2: you can change the color of the active button using the data-active-color="primary | info | success | warning | danger"        -->        <div class="sidebar-wrapper">            <div class="logo">                <a href="{{route('home')}}" class="simple-text">                    Dashboard                </a>            </div>            <ul class="nav">                <li class="{{(Route::getCurrentRoute()->getName() == 'home'||(Route::getCurrentRoute()->getName() == 'ApprovedProducts'&& auth()->user()->type =='company')||(Route::getCurrentRoute()->getName() == 'RejectedProducts'&& auth()->user()->type =='company')||(Route::getCurrentRoute()->getName() == 'WaitingProducts'&& auth()->user()->type =='company')||(Route::getCurrentRoute()->getName() == 'productView') ||(Route::getCurrentRoute()->getName() == 'product'&& auth()->user()->type =='company'))?'active':''}}">                    <a href={{route('home')}}>                        <i class="{{(auth()->user()->type == 'admin')?'fa fa-industry':'fa fa-cubes'}}"></i>                        @if (auth()->user()->type == 'company')                            <p>Products</p>                        @else                            <p>Comapnies</p>                        @endif                        <script>                        </script>                    </a>                </li>                <li class="{{(Route::getCurrentRoute()->getName() == 'company.sidebar.addProductView' || Route::getCurrentRoute()->getName() == 'admin.sidebar.categoriesView')?'active':''}}">                    <a href="{{(auth()->user()->type == 'company')?route('company.sidebar.addProductView'):route('admin.sidebar.categoriesView')}}">                        <i class="{{(auth()->user()->type == 'company')?'fa fa-plus-circle':'fa fa-shopping-bag'}}"></i>                        @if (auth()->user()->type == 'company')                            <p>Add Products</p>                        @else                            <p>Categories</p>                        @endif                    </a>                </li>                <li class="{{((Route::getCurrentRoute()->getName() == 'ApprovedProducts'&& auth()->user()->type =='admin')||(Route::getCurrentRoute()->getName() == 'WaitingProducts'&& auth()->user()->type =='admin')||(Route::getCurrentRoute()->getName() == 'RejectedProducts'&& auth()->user()->type =='admin')||(Route::getCurrentRoute()->getName() == 'productView')||(Route::getCurrentRoute()->getName() == 'product'&& auth()->user()->type =='admin')||Route::getCurrentRoute()->getName() == 'company.sidebar.profile')?'active':''}}">                    <a href="{{(auth()->user()->type == 'company')?route('company.sidebar.profile'):route('ApprovedProducts')}}">                        <i class="{{(auth()->user()->type == 'company')?'ti ti-user':'fa fa-cubes'}}"></i>                        @if (auth()->user()->type == 'admin')                            <p>Products</p>                        @else                            <p>Profile</p>                        @endif                    </a>                </li>                <li class="{{(Route::getCurrentRoute()->getName() == 'notifications')?'active':''}}">                    <a href="{{route('notifications')}}">                        <i class="fa fa-bell-o"></i>                        <p>Notifications &nbsp; <span id="notificationBadge" class="badge">{{($unseenCounter == 0)?'':$unseenCounter}}</span> </p>                    </a>                </li>            </ul>        </div>    </div>    <div class="main-panel">        <nav class="navbar navbar-default">            <div class="container-fluid">                <div class="navbar-header">                    <button type="button" class="navbar-toggle">                        <span class="sr-only">Toggle navigation</span>                        <span class="icon-bar bar1"></span>                        <span class="icon-bar bar2"></span>                        <span class="icon-bar bar3"></span>                    </button>                    <a class="navbar-brand" href="{{route('home')}}">Store</a>                </div>                <div class="collapse navbar-collapse">                    <ul class="nav navbar-nav navbar-right">                        {{--<li class="dropdown">--}}                            {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown">--}}                                {{--<i class="fa fa-globe"></i>--}}                                {{--<p>Notifications &nbsp;<span id="notificationBadge" class="badge">{{($unseenCounter == 0)?'':$unseenCounter}}</span></p>--}}                                {{--<b class="caret"></b>--}}                            {{--</a>--}}                            {{--<ul id="notifications" class="dropdown-menu">--}}                                {{--@foreach($myNotifications as $myNotification)--}}                                    {{--<li><a href="{{$myNotification->url}}" style="background:{{($myNotification->seen == 0)?'lightblue;':''}}">{{$myNotification->data}} <br> <span style="font-size: 8px;">{{$myNotification->timestamp}}</span></a> </li>--}}                                {{--@endforeach--}}                            {{--</ul>--}}                        {{--</li>--}}                        <li class="dropdown">                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">                                <i class="ti-user"></i>                                <p>{{ucfirst(auth()->user()->name)}}</p>                                <span style="font-size:0.6em">{{(auth()->user()->type)}}</span>                                <b class="caret"></b>                            </a>                            <ul class="dropdown-menu">                                <li>                                    <a href="{{route('company.sidebar.profile')}}"                                       class="ti-user"> Profile </a></li>                                @if (auth()->user()->type == 'admin')                                    <li><a href="{{route('register')}}" class="ti-plus"> Add Admin / Company </a></li>                                @endif                                <li><a class="ti-new-window" href="{{ route('logout') }}"                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">                                        Sign Out                                    </a>                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"                                          style="display: none;">                                        {{ csrf_field() }}                                    </form>                                </li>                            </ul>                        </li>                    </ul>                </div>            </div>        </nav>        @yield('content')        <footer class="footer">            <div class="container-fluid">                <div class="copyright pull-right">                    &copy;                    <script>document.write(new Date().getFullYear())</script>                    Store. All Rights Reserved</a>                </div>            </div>        </footer>    </div></div><div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"     aria-hidden="true">    <div class="modal-dialog">        <div class="modal-content">            <div class="modal-header">                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>                <h4 class="modal-title" id="myModalLabel">Confirm Delete</h4>            </div>            <div class="modal-body">                <p>You are about to delete this item , <span style="color: red;"> this is irreversible </span> .</p>                <p>Do you want to proceed ?</p>                <p class="debug-url"></p>            </div>            <div class="modal-footer">                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>                <a class="btn btn-danger btn-ok">Delete</a>            </div>        </div>    </div></div></body><!--   Core JS Files   --><script src="{{asset('assets/admin/js/jquery-2.1.1.min.js')}}" type="text/javascript"></script><script src="{{asset('assets/admin/js/bootstrap.min.js')}}" type="text/javascript"></script><!--  Charts Plugin -->{{--<script src="{{asset('assets/admin/js/chartist.min.js')}}"></script>--}}<!--  Notifications Plugin    --><script src="{{asset('assets/admin/js/bootstrap-notify.js')}}"></script><!-- Paper Dashboard Core javascript and methods for Demo purpose --><script src="{{asset('assets/admin/js/paper-dashboard.js')}}"></script><script src="{{asset('assets/admin/js/demo.js')}}"></script><script src="{{asset('assets/admin/js/jquery-show-first.js')}}"></script><script src="{{asset('assets/admin/fancybox/jquery.fancybox.js?v=2.1.4')}}"></script><script>    $(document).ready(function () {        $('[name = "imageView"]').click(function () {            var largeImage = $(this).attr('data-full');            $('.selected').removeClass();            $(this).addClass('selected');            $('.full img').hide();            $('.full img').attr('src', largeImage);            $('.full img').fadeIn();            $('#mainBtn').attr('href', $(this).attr('data-content1'));            $('#removeBtn').attr('href', $(this).attr('data-content2'));        }); // closing the listening on a click        $('.full img').on('click', function () {            var modalImage = $(this).attr('src');            $.fancybox.open(modalImage);        });    }); //closing our doc ready</script><script>    $(function () {        function reposition() {            var modal = $(this),                dialog = modal.find('.modal-dialog');            modal.css('display', 'block');            // or four works better for larger screens.            dialog.css("margin-top", Math.max(0, ($(window).height() - dialog.height()) / 2));        }        // Reposition when a modal is shown        $('.modal').on('show.bs.modal', reposition);        // Reposition when the window is resized        $(window).on('resize', function () {            $('.modal:visible').each(reposition);        });    });    $('#confirm-delete').on('show.bs.modal', function (e) {        $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));    });</script><script>    $(document).ready(function () {        $('#myButton').click(function () {            $.ajax({                type: "GET",                {{--url: '{{route('tryAjax')}}',--}}                data: {categoryName: document.getElementById('categoryName').value},                success: function (data) {                    console.log(data.res);                },                error: function (response) {                    console.log(response.status + " " + response.statusText);                }            });        });    });    $('#button1').click(function (e) {        e.preventDefault();    });</script><script>    function resetCounter() {        document.getElementById('notificationBadge').innerHTML = '';    }</script></html>