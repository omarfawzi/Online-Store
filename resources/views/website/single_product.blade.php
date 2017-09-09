@extends('layouts.customer')

@section('content')
    <div class="breadcrumb_dress">
        <div class="container">
            <ul>
                <li><a href="{{route('index')}}"><span class="glyphicon glyphicon-home" aria-hidden="true"></span> Home</a> <i>/</i></li>
                <li><a href="{{route('supplierProducts',['supplierName'=>$product->supplier->supplierName])}}">{{strtoupper($product->supplier->supplierName)}} Products</a> <i>/</i></li>
                <li>{{$product->productName}}</li>
            </ul>
        </div>
    </div>
    <div class="single">
        <div class="container">
            <div class="col-md-4 single-left">
                <div class="flexslider">
                    <ul class="slides">
                        @foreach($product->colors[0]->images as $image)
                        <li data-thumb="{{asset('assets/admin/images/products/'.$image->image)}}">
                            <div class="thumb-image"> <img src="{{asset('assets/admin/images/products/'.$image->image)}}" data-imagezoom="true" class="img-responsive loading"> </div>
                        </li>
                            @endforeach
                    </ul>
                </div>
                <!-- flixslider -->
                <script defer src="{{asset('assets/website/js/jquery.flexslider.js')}}"></script>
                <link rel="stylesheet" href="{{asset('assets/website/css/flexslider.css')}}" type="text/css" media="screen" />
                <script>
                    // Can also be used with $(document).ready()
                    $(window).load(function() {
                        $('.flexslider').flexslider({
                            animation: "slide",
                            controlNav: "thumbnails"
                        });
                    });
                </script>
                <!-- flixslider -->
                <!-- zooming-effect -->
                <script src="{{asset('assets/website/js/imagezoom.js')}}"></script>
                <!-- //zooming-effect -->
            </div>
            <form id="singleForm" method="POST" action="{{route('addToCart')}}">
                {{csrf_field()}}
                <input type="hidden" name="productID" value="{{$product->productID}}">
            <div class="col-md-8 single-right">
                <h3>{{$product->productName}}</h3>
                <div class="rating1">
					<span class="starRating">
						<input id="rating5" type="radio" name="rating" value="5">
						<label for="rating5">5</label>
						<input id="rating4" type="radio" name="rating" value="4">
						<label for="rating4">4</label>
						<input id="rating3" type="radio" name="rating" value="3" checked>
						<label for="rating3">3</label>
						<input id="rating2" type="radio" name="rating" value="2">
						<label for="rating2">2</label>
						<input id="rating1" type="radio" name="rating" value="1">
						<label for="rating1">1</label>
					</span>
                </div>
                <div class="color-quality">
                    <div class="color-quality-left">
                        <h5>Color</h5>
                        <ul>
                            <li>
                                <a>
                                <span class="colorSquare" style="background: {{$product->colors[0]->colorcode}}"></span>
                                {{$mainColorName}}
                                </a>
                            </li>
                        </ul>
                        <input type="hidden" name="colorID" value="{{$product->colors[0]->colorID}}">
                    </div>
                    <div class="clearfix"> </div>
                </div>

                <div class="color-quality">
                    @if (count($otherColors) > 0)
                        <br>
                    <div class="color-quality-left">
                        <h5>Other Colors</h5>
                        <ul>
                        @foreach($otherColors as $key=>$color)
                            <li>
                            <a href="{{route('singleProduct',['productName'=>$product->productName,'colorID'=>$color->colorID])}}">
                                <span class="colorSquare" style="background: {{$color->colorcode}}"></span>{{$otherColorsNames[$key]}}
                            </a>
                            </li>
                        @endforeach
                        </ul>
                    </div>
                    @endif
                        <div class="clearfix"> </div>
                </div>

                <div class="occasional">
                    <h5>Size</h5>
                    <div class="w3ls_dresses_grid_right_grid2">
                        <select name="sizeID" class="select_item">
                        @foreach($product->colors[0]->sizes as $size)
                                <option value="{{$size->sizeID}}">{{$size->size}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="simpleCart_shelfItem">
                    <p>EGP &nbsp;{{$product->price}}</p>
                    <input name="price" value="{{$product->price}}" type="hidden">
                    @if ($errors->has('msg'))
                        <span class="help-block">
                            <strong style="color: red">{{ $errors->first('msg') }}</strong>
                        </span>
                    @endif
                    <input type="button" style="width: 200px" class="btn btn-danger" id="addToCart" value="ADD TO CART">
                </div>
                <script>
                    $(document).ready(function () {
                       @if($errors->has('disable'))
                            $('#addToCart').attr('disabled','disabled');
                        @endif
                    });
                </script>
                <script>
                    $('#addToCart').click(function () {
                        @if(!Auth::guard('customer')->check())
                           $('#myModal88').modal('show');
                        @else
                        $('#singleForm').submit();
                        @endif
                    });
                </script>
            </div>
            </form>
            <div class="col-md-12 single-right">
                <div class="sap_tabs">
                    <div id="horizontalTab1" style="display: block; width: 100%; margin: 0px;">
                        <ul>
                            <li class="resp-tab-item" aria-controls="tab_item-0" role="tab"><span>Product Information</span></li>
                        </ul>
                        <div class="tab-1 resp-tab-content additional_info_grid" aria-labelledby="tab_item-0">
                            <h3>{{$product->productName}}</h3>
                            <h5>Brand : {{strtoupper($product->brand)}}</h5>
                            <br>
                            <h5>Description : {{(strlen($product->description)==0)?'None':''}}{{$product->description}}</h5>
                        </div>
                    </div>
                </div>
                <script src="{{asset('assets/website/js/easyResponsiveTabs.js')}}" type="text/javascript"></script>
                <script type="text/javascript">
                    $(document).ready(function () {
                        $('#horizontalTab1').easyResponsiveTabs({
                            type: 'default', //Types: default, vertical, accordion
                            width: 'auto', //auto or any width like 600px
                            fit: true   // 100% fit in a container
                        });
                    });
                </script>
            </div>
            <div class="clearfix"> </div>
        </div>
    </div>

    {{--<div class="additional_info">--}}
        {{--<div class="container">--}}
            {{--<div class="sap_tabs">--}}
                {{--<div id="horizontalTab1" style="display: block; width: 100%; margin: 0px;">--}}
                    {{--<ul>--}}
                        {{--<li class="resp-tab-item" aria-controls="tab_item-0" role="tab"><span>Product Information</span></li>--}}
                    {{--</ul>--}}
                    {{--<div class="tab-1 resp-tab-content additional_info_grid" aria-labelledby="tab_item-0">--}}
                        {{--<h3>{{$product->productName}}</h3>--}}
                        {{--<p>{{(strlen($product->description)==0)?'None':''}}{{$product->description}}</p>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}

        {{--</div>--}}
    {{--</div>--}}
@endsection