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
                        <th></th>
                        <th>Remove</th>
                    </tr>
                    </thead>
                    @foreach($cartProducts as $key => $cartProduct)
                        <input type="hidden" name="cartProductIDs[]" value="{{$cartProduct->cartProductID}}">
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
                                        <input type="hidden" id="availableUnits" value="{{$productQuantity[$key]}}">
                                    </div>
                                </div>
                            </td>
                            <td class="invert">{{$cartProduct->product->productName}}</td>
                            <td class="invert"><span class="colorSquare" style="background: {{$cartProduct->color->colorcode}}"></span> <p style="display: inline;">{{$colorNames[$key]}}</p></td>
                            <td class="invert">{{$cartProduct->size->size}}</td>
                            <td class="invert">EGP &nbsp; <p id="price{{$key}}" style="display: inline;">{{$cartProduct->product->price*$cartProduct->quantity}}</p></td>
                            <td class="invert">
                                @if ($errors->has('cartProduct'.$cartProduct->cartProductID))
                                    <span class="help-block">
                                        <strong style="color: red;">This product has {{$errors->first('cartProduct'.$cartProduct->cartProductID)}} items remaining </strong>
                                    </span>
                                @endif
                            </td>
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
                                                success:function(data) {
                                                    if (data.msg == 'success') {
                                                        $('.rem{{intval($key)+1}}').fadeOut('slow', function (c) {
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
                                                            if (newItemsCount == 0) {
                                                                $('#orderButton').hide();
                                                            }
                                                        });
                                                    }
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
            @if($cartItemsCount != 0)
                <div class="checkout-right-basket">
                    <a href="javascript:;" id="orderButton">Proceed to Order &nbsp;&nbsp;&nbsp;<span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span></a>
                </div>
            @endif
                <script>
                    $('#orderButton').click(function () {
                        $('#orderModal').modal('show');
                    });
                </script>
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
            var availableUnits = $(this).parent().find('#availableUnits').val();
            var newPrice = originalPrice*newVal;
            var total = 0 ;
            if (newVal <= availableUnits) {
                $.ajax({
                    type: 'GET',
                    url: '{{route('cartQuantity')}}',
                    data: {cartProductID: $(this).parent().find('#cartProductID').val(), quantity: newVal},
                    success: function (data) {
                        $('#price' + key).text(newPrice);
                        $('#price1' + key).text(newPrice);
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
    <div class="modal fade" id="orderModal" tabindex="-1" role="dialog" aria-labelledby="orderModal"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                    <h4 class="modal-title" id="myModalLabel">
                        Order Details</h4>
                </div>
                <div class="modal-body modal-body-sub">
                    <div class="row">
                        <div class="register">
                    <form action="{{route('placeOrder')}}" method="post">
                        {{csrf_field()}}
                        @foreach($cartProducts as $cartProduct)
                            <input type="hidden" name="cartProductIDs[]" value="{{$cartProduct->cartProductID}}">
                        @endforeach
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="First Name">First Name</label>
                                    <input id="First Name" name="firstName" placeholder="First Name" value="{{$customer->firstName}}" type="text" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="Last Name">Last Name</label>
                                    <input id="Last Name" name="lastName" placeholder="Last Name" value="{{$customer->lastName}}" type="text" required>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="address">Address</label>
                                    <input id="address" name="address" value="{{$customer->address}}" placeholder="Address" type="text" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phoneNumber">Mobile Number</label>
                                    <input id="phoneNumber" name="phoneNumber" value="{{$customer->phoneNumber}}" placeholder="Mobile Number" type="text" required>
                                </div>
                            </div>
                        </div>
                        <center>
                            <div class="sign-up">
                                <br>
                                <input type="submit" value="Confirm Order"/>
                            </div>
                        </center>
                    </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection