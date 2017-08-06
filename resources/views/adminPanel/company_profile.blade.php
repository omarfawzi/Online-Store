@extends('layouts.layout')
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <form method="post" action="{{route('updateProfile')}}" enctype="multipart/form-data">
                    {{csrf_field()}}

                <div class="col-lg-3">
                    <div class="image">
                    <img width="250px" height="300px" style="border-radius: 5px;box-shadow:  0 0 5px #888888;" src="{{($supplier->suppImage == null)?'http://s3.amazonaws.com/nvest/Blank_Club_Website_Avatar_Gray.jpg':asset('assets/images/logos/'.$supplier->suppImage)}}">
                    </div>
                    <br>
                    <div class="fileUpload btn btn-primary btn-fill">
                        <span>Upload Logo</span>
                        <input type="file" name="logo" id="uploadBtn" class="upload"
                               accept="image/*"/>
                    </div>
                    <br>
                </div>
                <div class="col-lg-9 col-md-7">
                    <div class="card">
                        <div class="header">
                            <h4 class="title">Edit Profile</h4>
                        </div>
                        <div class="content">
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label>Company</label>
                                            <input type="text" class="form-control border-input" name="supplierName" placeholder="Company" value="{{$supplier->supplierName}}">
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Email address</label>
                                            <input type="email" class="form-control border-input" placeholder="Email" value="{{$supplier->Email}}" disabled>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Address</label>
                                            <input name="address" type="text" class="form-control border-input" placeholder="Home Address" value="{{$supplier->Address}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Phone Number</label>
                                            <input name="number" type="tel" class="form-control border-input" placeholder="Phone Number" value="{{$supplier->phoneNumber}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>About Company</label>
                                            <textarea name="description" rows="5" class="form-control border-input" placeholder="Here can be your description">{{$supplier->Description}}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-success btn-fill btn-wd">Update Profile</button>
                                </div>
                                <div class="clearfix"></div>
                        </div>
                    </div>
                </div>

                </form>
            </div>
        </div>
    </div>
@endsection