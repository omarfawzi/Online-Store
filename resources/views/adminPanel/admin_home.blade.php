@extends('layouts.layout')
@section('content')

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="header">
                            <h4 class="title">Companies</h4>
                            <p class="category">Registered Companies</p>
                        </div>
                        <div class="content table-responsive table-full-width">
                            <table class="table table-striped">
                                <thead>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Member Since</th>
                                </thead>
                                <tbody>
                                @foreach($companies as $company)
                                    <form class="form-horizontal" role="form" method="POST" action="#">
                                        <tr>
                                            <td>{{$company->id}}</td>
                                            <td>{{$company->name}}</td>
                                            <td>{{$company->email}}</td>
                                            <td>{{$company->created_at}}</td>
                                        </tr>
                                    </form>
                                @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{$companies->links()}}

    </div>
@stop