@extends('layouts.customer')

@section('content')
    <script>
        $(document).ready(function () {
            $('#loader').hide();
            jQuery.ajaxSetup({
                beforeSend: function() {
                    $('#loader').show();
                },
                complete: function(){
                    $('#loader').hide();
                },
                success: function() {}
            });
        });

    </script>
    <div class="checkout">
        <div class="container">
            {{--<img src="{{ asset('assets/admin/images/spinner.gif') }}" class="cover" />--}}
            <input type="hidden" value="{{$cartItemsCount}}" id="itemsCount">
            <h3>Your shopping cart contains: <span id="shoppingItemsNumber">{{$cartItemsCount}}</span></h3>
            <center><img id="loader" src="{{ asset('assets/admin/images/spinner.gif') }}" /></center>
            <div class="checkout-right" >
                <table class="timetable_sub">
                    <thead>
                    <tr>
                        <th>SL No.</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Product Name</th>
                        <th>Color</th>
                        <th>Size</th>
                        <th>Price</th>
                        <th>Remove</th>
                    </tr>
                    </thead>
                    @foreach($cartProducts as $key => $cartProduct)
                        <tr class="rem{{intval($key)+1}}">
                            <td class="invert">{{intval($key)+1}}</td>
                            <td class="invert-image">
                                <a href="{{route('singleProduct',['productName'=>$cartProduct->product->productName,'colorID'=>$cartProduct->color->colorID])}}">
                                    <img src="{{asset('assets/admin/images/products/'.$cartProduct->color->images[0]->image)}}" alt=" " class="img-responsive" />
                                </a></td>
                            <td class="invert">
                                <div class="quantity">
                                    <div class="quantity-select">
                                        <div class="entry value-minus">&nbsp;</div>
                                        <div class="entry value" ><span id="quantity">{{$cartProduct->quantity}}</span></div>
                                        <div class="entry value-plus active">&nbsp;</div>
                                        <input type="hidden" id="cartProductID" value="{{$cartProduct->cartProductID}}">
                                        <input type="hidden" id="key" value="{{$key}}" >
                                        <input type="hidden" id="price" value="{{$cartProduct->product->price}}">
                                    </div>
                                </div>
                            </td>
                            <td class="invert">{{$cartProduct->product->productName}}</td>
                            <td class="invert"><span class="colorSquare" style="background: {{$cartProduct->color->colorcode}}"></span> <p style="display: inline;">{{$colorNames[$key]}}</p></td>
                            <td class="invert">{{$cartProduct->size->size}}</td>
                            <td class="invert">EGP &nbsp; <p id="price{{$key}}" style="display: inline;">{{$cartProduct->product->price*$cartProduct->quantity}}</p></td>
                            <td class="invert">
                                <div class="rem">
                                    <div class="close{{intval($key)+1}}"> </div>
                                </div>
                                <script>
                                    $(document).ready(function(c) {
                                        $('.close{{intval($key)+1}}').on('click', function(c){
                                            var newItemsCount =  $('#itemsCount').val() - 1 ;
                                            var total = 0 ;
                                            $("[name='finalPrices[]'").each(function () {
                                            });
                                            $.ajax({
                                                type:'GET',
                                                url:'{{route('removeCartProduct')}}',
                                                data:{cartProductID:'{{$cartProduct->cartProductID}}'},
                                                success:function(data){

                                                    $('.rem{{intval($key)+1}}').fadeOut('slow', function(c){
                                                        $('.rem{{intval($key)+1}}').remove();
                                                        $('#list{{$key}}').remove();
                                                        $('#itemsCount').val(newItemsCount);
                                                        $("[name='finalPrices[]'").each(function () {
                                                            total += parseInt($(this).text());
                                                        });
                                                        $('#totalPrice').text(total);
                                                        $('#cartTotalMoney').text(total);
                                                        $('#cartTotalItems').text(newItemsCount);
                                                        $('#shoppingItemsNumber').text(newItemsCount);
                                                    });
                                                }
                                            });

                                        });
                                    });
                                </script>
                            </td>
                        </tr>
                @endforeach

                <!--quantity-->
                    <!--quantity-->
                </table>
            </div>
            <div class="checkout-left">
                <div class="checkout-left-basket">
                    <h4>Continue to basket</h4>
                    <ul>
                        @foreach($cartProducts as $key => $cartProduct)
                            <li id="list{{$key}}">Product{{intval($key)+1}} <i>-</i> <span>EGP &nbsp;<p name="finalPrices[]" id="price1{{$key}}" style="display: inline;"> {{$cartProduct->product->price*$cartProduct->quantity}}</p> </span></li>
                        @endforeach
                        {{--@for ($i=0 ; $i<3/$cartItemsCount ; $i++)--}}
                        {{--<li hidden></li>--}}
                        {{--@endfor--}}
                        {{--<li hidden>Total Service Charges <i>-</i> <span>$15.00</span></li>--}}
                        <li>Total Service Charges <i>-</i> <span>EGP &nbsp; 0.00</span></li>
                        <li id="totalLi">Total <i>-</i> <span>EGP &nbsp; <p id="totalPrice" style="display: inline;">{{$cartTotalPrice}}</p></span></li>
                    </ul>
                </div>

                <div class="checkout-right-basket">
                    <a href="products.html">Proceed to Order &nbsp;<span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span></a>
                </div>
                <div class="clearfix"> </div>
            </div>
        </div>
    </div>
    <script>
        $('.value-plus').on('click', function(){
            var itemsCount = $('#itemsCount').val();
            var divUpd = $(this).parent().find('.value'), newVal = parseInt(divUpd.text(), 10)+1;
            var key = $(this).parent().find('#key').val();
            var originalPrice = $(this).parent().find('#price').val();
            var newPrice = originalPrice*newVal;
            var total = 0 ;

            $.ajax({
                type:'GET',
                url:'{{route('cartQuantity')}}',
                data:{cartProductID:$(this).parent().find('#cartProductID').val(),quantity:newVal},
                success:function(data){
                    $('#price'+key).text(newPrice);
                    $('#price1'+key).text(newPrice);
                    divUpd.text(newVal);
                    $("[name='finalPrices[]'").each(function () {
                        total += parseInt($(this).text());
                    });
                    $('#totalPrice').text(total);
                    $('#cartTotalMoney').text(total);
                }
            });
        });
        $('.value-minus').on('click', function(){
            var itemsCount = $('#itemsCount').val();
            var divUpd = $(this).parent().find('.value'), newVal = parseInt(divUpd.text(), 10)-1;
            var key = $(this).parent().find('#key').val();
            var originalPrice = $(this).parent().find('#price').val();
            var newPrice = originalPrice*newVal;
            var total = 0 ;

            if(newVal>=1) {
                $.ajax({
                    type: 'GET',
                    url: '{{route('cartQuantity')}}',
                    data: {cartProductID: $(this).parent().find('#cartProductID').val(), quantity: newVal},
                    success: function (data) {
                        $('#price'+key).text(newPrice);
                        $('#price1'+key).text(newPrice);
                        divUpd.text(newVal);
                        $("[name='finalPrices[]'").each(function () {
                            total += parseInt($(this).text());
                        });
                        $('#totalPrice').text(total);
                        $('#cartTotalMoney').text(total);
                    }
                });
            }
        });
    </script>
@endsection