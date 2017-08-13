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

            <h3>Your shopping cart contains: <span>{{$cartItemsCount}}</span></h3>
            <center><img id="loader" src="{{ asset('assets/admin/images/spinner.gif') }}" /></center>
            <div class="checkout-right" >
                <table class="timetable_sub">
                    <thead>
                    <tr>
                        <th>SL No.</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Product Name</th>
                        <th>Service Charges</th>
                        <th>Price</th>
                        <th>Remove</th>
                    </tr>
                    </thead>
                    @foreach($cartProducts as $key => $cartProduct)
                    <tr class="rem{{intval($key)+1}}">
                        <td class="invert">{{intval($key)+1}}</td>
                        <td class="invert-image">
                            <a href="{{route('singleProduct',['productName'=>$cartProduct->product->productName,'colorID'=>$cartProduct->product->colors[0]->colorID])}}">
                                <img src="{{asset('assets/admin/images/products/'.$cartProduct->product->colors[0]->images[0]->image)}}" alt=" " class="img-responsive" />
                                </a></td>
                        <td class="invert">
                            <div class="quantity">
                                <div class="quantity-select">
                                    <div class="entry value-minus">&nbsp;</div>
                                    <div class="entry value"><span>{{$cartProduct->quantity}}</span></div>
                                    <div class="entry value-plus active">&nbsp;</div>
                                </div>
                            </div>
                        </td>
                        <td class="invert">{{$cartProduct->product->productName}}</td>
                        <td class="invert">EGP &nbsp; 0.00</td>
                        <td class="invert">EGP &nbsp; {{$cartProduct->product->price}}</td>
                        <td class="invert">
                            <div class="rem">
                                <div class="close{{intval($key)+1}}"> </div>
                            </div>
                            <script>
                                $(document).ready(function(c) {
                                    $('.close{{intval($key)+1}}').on('click', function(c){
                                        $.ajax({
                                            type:'GET',
                                            url:'{{route('removeCartProduct')}}',
                                            data:{cartProductID:'{{$cartProduct->cartProductID}}'},
                                            success:function(data){
                                                console.log(data.msg);
                                            }
                                        });
                                        $('.rem{{intval($key)+1}}').fadeOut('slow', function(c){

                                            $('.rem{{intval($key)+1}}').remove();
                                        });
                                    });
                                });
                            </script>
                        </td>
                    </tr>
                    @endforeach
                    <!--quantity-->
                    <script>
                        $('.value-plus').on('click', function(){
                            var divUpd = $(this).parent().find('.value'), newVal = parseInt(divUpd.text(), 10)+1;
                            divUpd.text(newVal);
                        });

                        $('.value-minus').on('click', function(){
                            var divUpd = $(this).parent().find('.value'), newVal = parseInt(divUpd.text(), 10)-1;
                            if(newVal>=1) divUpd.text(newVal);
                        });
                    </script>
                    <!--quantity-->
                </table>
            </div>
            <div class="checkout-left">
                <div class="checkout-left-basket">
                    <h4>Continue to basket</h4>
                    <ul>
                        @foreach($cartProducts as $key => $cartProduct)
                        <li>Product{{intval($key)+1}} <i>-</i> <span>EGP &nbsp; {{$cartProduct->product->price}} </span></li>
                        @endforeach
                        {{--@for ($i=0 ; $i<3/$cartItemsCount ; $i++)--}}
                         {{--<li hidden></li>--}}
                            {{--@endfor--}}
                            {{--<li hidden>Total Service Charges <i>-</i> <span>$15.00</span></li>--}}

                        <li>Total Service Charges <i>-</i> <span>EGP &nbsp; 0.00</span></li>
                        <li id="totalLi">Total <i>-</i> <span>EGP &nbsp; {{$cartTotalPrice}}</span></li>
                    </ul>
                </div>
                <div class="checkout-right-basket">
                    <a href="products.html">Proceed to Order &nbsp;<span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span></a>
                </div>
                <div class="clearfix"> </div>
            </div>
        </div>
    </div>
@endsection