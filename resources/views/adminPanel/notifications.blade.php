@extends('layouts.layout')
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="header">
                            <h4 class="title">Notifications</h4>
                            <p class="category">{{(auth()->user()->type == 'company')?'Customers':'Companies'}}
                                Notifications</p>
                        </div>
                        <div class="content table-responsive table-full-width">
                            <table class="table table-striped">
                                <thead>
                                <th>Notification</th>
                                <th>Date</th>
                                </thead>
                                <tbody>
                                @foreach($myNotifications as $myNotification)
                                    <tr>
                                        <td><a href="{{$myNotification->url}}" style="color: {{($myNotification->seen == '0')?'red':'black'}}">{{$myNotification->data}}</a></td>
                                        <td>{{$myNotification->timestamp}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                    <div style="float: right">
                        {{$myNotifications->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection