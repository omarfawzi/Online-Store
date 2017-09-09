@extends('layouts.admin')

@section('content')
    <div class="content">

        <div class="container-fluid">

            <div class="row">

                <div class="col-md-12">

                    <div class="card">

                        <div class="header">

                            <h4 class="title">Orders</h4>

                            <p class="category">{{(auth()->user()->type == 'company')?'Your':'All'}}

                                Orders</p>

                        </div>

                        <div class="content table-responsive table-full-width">

                            <table class="table table-striped">

                                <thead>

                                <th>ID</th>

                                <th>Name</th>

                                <th>Phone</th>

                                <th>Address</th>

                                <th>Time</th>

                                <th>Status</th>

                                <th>Details</th>

                                <th>Track</th>

                                <th>Cancel Order</th>

                                </thead>

                                <tbody>

                                @foreach($orders as $order)
                                    <form method="get" action="#">
                                    <tr>

                                        <td>{{$order->orderID}}</td>

                                        <td>{{$order->name}}</td>

                                        <td>{{$order->phone}}</td>

                                        <td>{{$order->address}}</td>

                                        <td>{{$order->date}}</td>

                                        <td>{{($order->status)?'On Way':'In Stock'}}</td>

                                        <td>
                                            <a class="btn btn-info btn-fill btn-sm" href="{{route('orderDetails',['orderID'=>$order->orderID])}}" title="Details">

                                                <span class="fa fa-edit"></span>

                                            </a>
                                        </td>

                                        <td>
                                            <button class="btn btn-info btn-fill btn-sm" href="javascript:;" title="Track" {{($order->status)?'':'disabled'}}>

                                                <span class="fa fa-map-marker"></span>

                                            </button>
                                        </td>
                                        <td>
                                            <a class="btn btn-danger btn-fill btn-sm" data-toggle="modal" data-target="#confirm-delete" data-href="{{route('cancelOrder',['orderID'=>$order->orderID])}}" title="Cancel Order">

                                                <span class="fa fa-remove"></span>

                                            </a>
                                        </td>
                                    </tr>
                                    </form>

                                @endforeach

                                </tbody>

                            </table>



                        </div>

                    </div>

                    <div style="float: right">
                        {{$orders->links()}}
                    </div>

                </div>

            </div>

        </div>

    </div>
@endsection