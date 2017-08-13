@extends('layouts.layout')
@section('content')

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="header">
                            <h4 class="title">Categories</h4>
                            <p class="category">Existing Categories</p>
                        </div>
                        <div class="content table-responsive table-full-width">
                            <table class="table table-striped">
                                <thead>
                                <th>ID</th>
                                <th>Name</th>
                                </thead>
                                <tbody>
                                @foreach($categories as $category)
                                    <form method="post" action="{{route('updateCategory')}}" >
                                        {{csrf_field()}}
                                    <tr>
                                        <input type="hidden" name="categoryID" value="{{$category->categoryID}}" >
                                        <td>{{$category->categoryID}}</td>
                                        <td><input name="categoryName" style="width:50%;" type="text" class="form-control border-input" value="{{$category->categoryName}}"></td>
                                        <td>
                                            <button type="submit" class="btn btn-info btn-fill">Update</button>
                                            <a class="btn btn-danger btn-fill" href="{{route('deleteCategory',['categoryID'=>$category->categoryID])}}">
                                                Delete
                                            </a>
                                        </td>
                                    </tr>
                                        </form>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="content">
                        <form method="post" action="{{route('addCategory')}}">
                            {{csrf_field()}}
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label>Admin</label>
                                        <input type="text" style="color:red;" class="form-control border-input" disabled
                                               placeholder="Company" value="{{auth()->user()->name}}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group{{ $errors->has('categoryName') ? ' has-error' : '' }}">
                                        <label>Category name</label>
                                        <input type="text" name="categoryName" class="form-control border-input"
                                               placeholder="Category Name" value="{{old('categoryName')}}" required>
                                        @if ($errors->has('categoryName'))
                                            <span class="help-block">
                                                <strong style="color: red;">{{ $errors->first('categoryName') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <span><button type="submit" class="btn btn-info btn-fill btn-wd">Add Category</button></span>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
            {{$categories->links()}}
        </div>
    </div>
@stop