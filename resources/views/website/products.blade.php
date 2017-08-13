@extends('layouts.customer')

@section('content')
    <div class="breadcrumb_dress">
        <div class="container">
            <ul>
                <li><a href="{{route('index')}}"><span class="glyphicon glyphicon-home" aria-hidden="true"></span> Home</a> <i>/</i></li>
                <li>Products</li>
            </ul>
        </div>
    </div>
    <!-- //breadcrumbs -->

    <!-- dresses -->
    <div class="dresses">
        <div class="container">
            <div class="w3ls_dresses_grids">
                <div class="col-md-4 w3ls_dresses_grid_left">
                    <div class="w3ls_dresses_grid_left_grid">
                        <form method="GET" action="{{route('filterProducts')}}">
                        <h3>Filter</h3>
                        <div class="w3ls_dresses_grid_left_grid_sub">
                            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="headingOne">
                                        <h4 class="panel-title asd">
                                            <a class="pa_italic" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                <span class="glyphicon glyphicon-minus" aria-hidden="true"></span><i class="glyphicon glyphicon-minus" aria-hidden="true"></i>
                                                Brands
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                                        <div class="panel-body">
                                            <ul style="list-style: none">
                                                @foreach($suppliers as $supplier)
                                                <li>
                                                    <label style="display: inline" class="checkbox"><input type="checkbox" name="brands[]" value="{{$supplier->supplierName}}" {{($checkedMap[$supplier->supplierName])?'checked':''}}>
                                                        <i></i>
                                                    </label>
                                                    <span>{{$supplier->supplierName}}</span>

                                                </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="headingTwo">
                                        <h4 class="panel-title asd">
                                            <a class="pa_italic collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                <span class="glyphicon glyphicon-minus" aria-hidden="true"></span><i class="glyphicon glyphicon-minus" aria-hidden="true"></i>
                                                Colors
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                                        <div class="panel-body">
                                            <ul style="list-style: none;">
                                                @foreach($colors as $key => $color)
                                                    <li>
                                                    <label style="display: inline" class="checkbox"><input type="checkbox" name="colors[]" value="{{$color->colorcode}}" {{($checkedMap[$color->colorcode])?'checked':''}}>
                                                        <i></i><span class="colorSquare" style="background: {{$color->colorcode}};"> </span>&nbsp;
                                                    </label>
                                                       <span>{{$colorNames[$key]}}</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="headingThree">
                                        <h4 class="panel-title asd">
                                            <a class="pa_italic" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                                <span class="glyphicon glyphicon-minus" aria-hidden="true"></span><i class="glyphicon glyphicon-minus" aria-hidden="true"></i>
                                                Sizes
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                                        <div class="panel-body">
                                            <ul>
                                                @foreach($sizes as $size)
                                                    <li>
                                                        <label style="display: inline" class="checkbox"><input type="checkbox" name="sizes[]" value="{{$size->size}}" {{($checkedMap[$size->size])?'checked':''}}>
                                                            <i></i>
                                                        </label>
                                                        <span>{{$size->size}}</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="headingFour">
                                        <h4 class="panel-title asd">
                                            <a class="pa_italic" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                                <span class="glyphicon glyphicon-minus" aria-hidden="true"></span><i class="glyphicon glyphicon-minus" aria-hidden="true"></i>
                                                Gender
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour">
                                        <div class="panel-body">
                                            <ul>
                                                <li>
                                                    <label style="display: inline" class="checkbox"><input value="Men" type="checkbox" name="gender[]" {{($checkedMap['Men'])?'checked':''}}>
                                                        <i></i>
                                                    </label>
                                                    <span>Men</span>
                                                </li>
                                                <li>
                                                    <label style="display: inline" class="checkbox"><input value="Women" type="checkbox" name="gender[]" {{($checkedMap['Women'])?'checked':''}}>
                                                        <i></i>
                                                    </label>
                                                    <span>Women</span>
                                                </li>
                                                <li>
                                                    <label style="display: inline" class="checkbox"><input value="Girls" type="checkbox" name="gender[]" {{($checkedMap['Girls'])?'checked':''}}>
                                                        <i></i>
                                                    </label>
                                                    <span>Girls</span>
                                                </li>
                                                <li>
                                                    <label style="display: inline" class="checkbox"><input value="Boys" type="checkbox" name="gender[]" {{($checkedMap['Boys'])?'checked':''}}>
                                                        <i></i>
                                                    </label>
                                                    <span>Boys</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="headingFive">
                                        <h4 class="panel-title asd">
                                            <a class="pa_italic" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                                <span class="glyphicon glyphicon-minus" aria-hidden="true"></span><i class="glyphicon glyphicon-minus" aria-hidden="true"></i>
                                                Price
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseFive" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFive">
                                        <div class="panel-default">
                                            <div class="form-group clearfix">
                                                <div class="amount-box">
                                                    <div class="col-sm-6">
                                                        <label for="amount-from">From </label>
                                                        <input type="number" name="prices[]" value="0" min="0" max="5000">
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label for="amount-to">To </label>
                                                        <input type="number" name="prices[]" value="5000" min="0" max="5000">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            {{--<ul class="panel_bottom">--}}
                                {{--<li><a href="products.html">Summer Store</a></li>--}}
                                {{--<li><a href="dresses.html">New In Clothing</a></li>--}}
                                {{--<li><a href="sandals.html">New In Shoes</a></li>--}}
                                {{--<li><a href="products.html">Latest Watches</a></li>--}}
                            {{--</ul>--}}
                            <br>
                            
                            <div class="form-group">
                                <button class="form-control btn btn-fill btn-success">Submit</button>
                            </div>
                            {{--<center>--}}
                            {{--<div class="register">--}}
                            {{--<div class="sign-up">--}}
                                {{--<input width="100px;" type="submit" value="Submit"/>--}}
                            {{--</div>--}}
                            {{--</div>--}}
                            {{--</center>--}}
                        </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-8 w3ls_dresses_grid_right">
                    <div class="w3ls_dresses_grid_right_grid2">
                        <div class="w3ls_dresses_grid_right_grid2_left">
                            {{--<h3>Showing Results: 0-1</h3>--}}
                        </div>
                        <div class="w3ls_dresses_grid_right_grid2_right">
                            <select name="select_item" class="select_item">
                                <option selected="selected">Default sorting</option>
                                <option>Sort by popularity</option>
                                <option>Sort by average rating</option>
                                <option>Sort by newness</option>
                                <option>Sort by price: low to high</option>
                                <option>Sort by price: high to low</option>
                            </select>
                        </div>
                        <div class="clearfix"> </div>
                    </div>
                    <div class="w3ls_dresses_grid_right_grid3">
                        @foreach($products as $product)
                        <div class="col-md-4 agileinfo_new_products_grid agileinfo_new_products_grid_dresses">
                            <div class="agile_ecommerce_tab_left dresses_grid">
                                <div class="hs-wrapper hs-wrapper2">
                                    @for($i = 0 ; $i < 6/count($product->colors[0]->images);$i++)
                                    @foreach($product->colors[0]->images as $image)
                                    <img src="{{asset('assets/admin/images/products/'.$image->image)}}" alt=" " class="img-responsive" />
                                    @endforeach
                                    @endfor
                                    <div class="w3_hs_bottom w3_hs_bottom_sub1">
                                        <ul>
                                            <li>
                                                <a href="{{route('singleProduct',['productName'=>$product->productName,'colorID'=>$product->colors[0]->colorID])}}"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <br>
                                <p style="color: grey;">{{$product->brand}}</p>
                                <h5 style="display: inline;"><a href="{{route('singleProduct',['productName'=>$product->productName,'colorID'=>$product->colors[0]->colorID])}}">{{$product->productName}}</a></h5>
                                <div class="simpleCart_shelfItem">
                                    <small  style="color: grey;">EGP&nbsp;{{$product->price}}</small>
                                    <br>
                                    {{--<p><a href="#" name="addtoCart[]" data-price={{$product->price}}>Add to cart</a></p>--}}
                                    {{--@foreach($product->colors as $color)--}}
                                        {{--<span class="colorSquare" style="background: {{$color->colorcode}}"></span>--}}
                                    {{--@endforeach--}}
                                    {{--<br>--}}
                                    {{--@foreach($product->colors as $color)--}}
                                        {{--<span class="colorSquare" style="background: {{$color->colorcode}}"></span>--}}
                                    {{--@endforeach--}}
                                    <hr style="border-color: black">

                                    {{--<p><span>EGP420</span> <i class="item_price">EGP{{$product->price}}</i></p>--}}
                                    {{--<p><a class="item_add" href="#">Add to cart</a></p>--}}
                                </div>
                                {{--<div class="dresses_grid_pos">--}}
                                    {{--<h6>New</h6>--}}
                                {{--</div>--}}
                            </div>
                        </div>
                        @endforeach
                            {{--<script>--}}
                                {{--$("[name='addtoCart[]']").click(function () {--}}
                                    {{--@if(Auth::guard('customer')->check())--}}
                                    {{--var totalMoney = parseFloat($('#cartTotalMoney').html()) + parseFloat($(this).attr("data-price"));--}}
                                    {{--var totalItems = parseFloat($('#cartTotalItems').html()) + 1 ;--}}
                                    {{--$('#cartTotalMoney').html(totalMoney);--}}
                                    {{--$('#cartTotalItems').html(totalItems);--}}
                                    {{--@else--}}
                                       {{--$('#myModal88').modal('show');--}}
                                    {{--@endif--}}
                                {{--});--}}
                            {{--</script>--}}
                    <div class="modal video-modal fade" id="myModal6" tabindex="-1" role="dialog" aria-labelledby="myModal6">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                </div>
                                <section>
                                    <div class="modal-body">
                                        <div class="col-md-5 modal_body_left">
                                            <img src="images/39.jpg" alt=" " class="img-responsive" />
                                        </div>
                                        <div class="col-md-7 modal_body_right">
                                            <h4>a good look women's Long Skirt</h4>
                                            <p>Ut enim ad minim veniam, quis nostrud
                                                exercitation ullamco laboris nisi ut aliquip ex ea
                                                commodo consequat.Duis aute irure dolor in
                                                reprehenderit in voluptate velit esse cillum dolore
                                                eu fugiat nulla pariatur. Excepteur sint occaecat
                                                cupidatat non proident, sunt in culpa qui officia
                                                deserunt mollit anim id est laborum.</p>
                                            <div class="rating">
                                                <div class="rating-left">
                                                    <img src="images/star-.png" alt=" " class="img-responsive" />
                                                </div>
                                                <div class="rating-left">
                                                    <img src="images/star-.png" alt=" " class="img-responsive" />
                                                </div>
                                                <div class="rating-left">
                                                    <img src="images/star-.png" alt=" " class="img-responsive" />
                                                </div>
                                                <div class="rating-left">
                                                    <img src="images/star.png" alt=" " class="img-responsive" />
                                                </div>
                                                <div class="rating-left">
                                                    <img src="images/star.png" alt=" " class="img-responsive" />
                                                </div>
                                                <div class="clearfix"> </div>
                                            </div>
                                            <div class="modal_body_right_cart simpleCart_shelfItem">
                                                <p><span>$320</span> <i class="item_price">$250</i></p>
                                                <p><a class="item_add" href="#">Add to cart</a></p>
                                            </div>
                                            <h5>Color</h5>
                                            <div class="color-quality">
                                                <ul>
                                                    <li><a href="#"><span></span>Red</a></li>
                                                    <li><a href="#" class="brown"><span></span>Yellow</a></li>
                                                    <li><a href="#" class="purple"><span></span>Purple</a></li>
                                                    <li><a href="#" class="gray"><span></span>Violet</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="clearfix"> </div>
                                    </div>
                                </section>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clearfix"> </div>
            </div>
        </div>
    </div>
@endsection