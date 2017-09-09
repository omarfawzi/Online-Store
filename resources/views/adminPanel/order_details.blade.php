@extends('layouts.admin')

@section('content')
    <div class="content">

        <div class="container-fluid">

            <div class="row">

                <div class="col-md-12">

                    <div class="card">

                        <div class="header">

                            <h4 class="title">Order Details</h4>

                            <p class="category">Order Details</p>

                        </div>

                        <div class="content table-responsive table-full-width">

                            <table class="table table-striped">

                                <thead>

                                <th>Order ID</th>

                                <th>Product Name</th>

                                <th>Supplier</th>

                                <th>Color</th>

                                <th>Size</th>

                                <th>Quantity</th>

                                <th>Price</th>

                                {{--<th>Cancel Product</th>--}}

                                </thead>

                                <tbody>

                                @foreach($orderDetails as $order)

                                    <tr>

                                        <td>{{$order->orderID}}</td>

                                        <td>
                                            <a href="{{route('product',['productName'=>$order->product->productName,'colorID'=>$order->color->colorID])}}">{{$order->product->productName}}</a>
                                        </td>

                                        <td>{{$order->supplier->supplierName}}</td>

                                        <td>
                                            <a href="{{route('product',['productName'=>$order->product->productName,'colorID'=>$order->color->colorID])}}"><span class="colorSquare" style="background: {{$order->color->colorcode}}"></span></a>
                                        </td>

                                        <td>{{$order->size->size}}</td>

                                        <td>{{$order->quantity}}</td>

                                        <td>EGP {{$order->product->price * $order->quantity }}</td>

                                        {{--<td>--}}
                                            {{--<a class="btn btn-danger btn-fill btn-sm" href="javascript:;" title="Cancel Order">--}}

                                                {{--<span class="fa fa-remove"></span>--}}

                                            {{--</a>--}}
                                        {{--</td>--}}

                                    </tr>

                                @endforeach

                                </tbody>

                            </table>

                        </div>

                    </div>
                    <div class="col-md-2 pull-right">
                        <label for="totalPrice">Total Price</label>
                        <input id="totalPrice" type="text" class="form-control border-input" value="EGP {{$totalPrice}}" disabled>
                    </div>


                </div>

            </div>

        </div>

    </div>
@endsection