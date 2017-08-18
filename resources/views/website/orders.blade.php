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
                        <th>Cancel</th>
                    </tr>
                    </thead>
                    @foreach($orderDetails as $key => $orderDetail)
                        <tr class="rem{{intval($key)+1}}">
                            <td class="invert">{{intval($key)+1}}</td>
                            <td class="invert-image">
                                <a href="{{route('singleProduct',['productName'=>$orderDetail->product->productName,'colorID'=>$orderDetail->color->colorID])}}">
                                    <img src="{{asset('assets/admin/images/products/'.$orderDetail->color->images[0]->image)}}" alt=" " class="img-responsive" />
                                </a></td>
                            <td class="invert">{{$orderDetail->quantity}}</td>
                            <td class="invert">{{$orderDetail->product->productName}}</td>
                            <td class="invert"><span class="colorSquare" style="background: {{$orderDetail->color->colorcode}}"></span> <p style="display: inline;">{{$colorNames[$key]}}</p></td>
                            <td class="invert">{{$orderDetail->size->size}}</td>
                            <td class="invert">EGP &nbsp; <p id="price{{$key}}" style="display: inline;">{{$orderDetail->product->price*$orderDetail->quantity}}</p></td>
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
                                                url:'{{route('cancelOrder')}}',
                                                data:{orderDetailID:'{{$orderDetail->orderdetailsID}}'},
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
                                                        if(newItemsCount == 0){
                                                            $('#orderButton').hide();
                                                        }
                                                    });
                                                }
                                            });

                                        });
                                    });
                                </script>
                            </td>
                        </tr>
                @endforeach
                </table>
            </div>
        </div>
    </div>
@endsection