@extends('layouts.layout')
@section('content')

    <div class="content" >
        <div class="container-fluid" >
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="header">
                            <div class="col-md-3">
                            <h4 class="title">Products</h4>
                            <p class="category">{{auth()->user()->name}} Products</p>
                            </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <span class="input-group-addon" style="background-color: gainsboro"><i class="fa fa-search fa-fw"></i></span>
                                        <input id="myInput" onkeyup="check()" type="text" name="searchInfo" class="form-control border-input"
                                               placeholder="Search..." autocomplete="on">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <select onchange="check()" class="form-control border-input" id="searchBy">
                                            <option hidden>Search By ( Default : Name )</option>
                                            <option name="searchInputs" class="form-control">Name</option>
                                            <option name="searchInputs" class="form-control">Gender</option>
                                            <option name="searchInputs" class="form-control">Brand</option>
                                            <option name="searchInputs" class="form-control">Price</option>
                                            <option name="searchInputs" class="form-control">Category</option>
                                        </select>
                                    </div>
                                </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <select class="form-control border-input" onchange="handle(this)">
                                        @if(Route::getCurrentRoute()->getName() == 'ApprovedProducts')
                                            <option hidden> Approved</option>
                                            <option value="{{route('WaitingProducts')}}">Waiting</option>
                                            <option value="{{route('RejectedProducts')}}">Rejected</option>
                                        @endif
                                        @if(Route::getCurrentRoute()->getName() == 'WaitingProducts')
                                            <option hidden>Waiting</option>
                                            <option value="{{route('ApprovedProducts')}}">Approved</option>
                                            <option value="{{route('RejectedProducts')}}">Rejected</option>
                                        @endif
                                        @if(Route::getCurrentRoute()->getName() == 'RejectedProducts')
                                            <option hidden> Rejected </option>
                                            <option value="{{route('ApprovedProducts')}}">Approved</option>
                                            <option value="{{route('WaitingProducts')}}">Waiting</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <script>
                                function handle(elm)
                                {
                                    //   console.log(elm);
                                    if (elm.value)
                                        window.location.href = elm.value;
                                }
                            </script>
                        </div>
                        <div class="content table-responsive table-full-width">
                            <table id="searchTable" class="table table-striped" style="display: none;">
                                <thead>
                                <th>Name</th>
                                <th>Gender</th>
                                <th>Brand</th>
                                <th>Price</th>
                                <th>Category</th>
                                <th>Colors</th>
                                </thead>
                                <tbody>
                                @foreach($allProducts as $product)
                                    @if(count($product->colors) != 0)
                                        <tr style="display: none;">
                                        <td>{{$product->productName}}</td>
                                        <td>{{$product->gender}}</td>
                                        <td>{{$product->brand}}</td>
                                        <td>{{$product->price}}</td>
                                        <td>{{$product->category->categoryName}}</td>
                                        <td>
                                            @foreach($product->colors as $color)
                                                <a href="{{route('product',['productID'=>encrypt($product->productID),'colorID'=>encrypt($color->colorID)])}}"><span class="colorSquare" style="background: {{$color->colorcode}}"></span></a>
                                            @endforeach
                                        </td>
                                        <td>
                                            <a class="btn btn-info btn-fill btn-sm" href="{{route('product',['productID'=>encrypt($product->productID),'colorID'=>encrypt($product->colors[0]->colorID)])}}" title="Edit">
                                                <span class="fa fa-edit"></span>
                                            </a>
                                            {{--<button class="btn btn-danger btn-fill btn-sm" title="Delete" data-href="{{route('removeWholeProduct',['productID'=>encrypt($product->productID)])}}" data-toggle="modal" data-target="#confirm-delete">--}}
                                                {{--<span class="fa fa-trash"></span>--}}
                                            {{--</button>--}}
                                        </td>
                                    </tr>
                                        @endif
                                @endforeach
                                </tbody>
                            </table>
                            <table id="myTable" class="table table-striped">
                                <thead>
                                <th>Name</th>
                                <th>Gender</th>
                                <th>Brand</th>
                                <th>Price</th>
                                <th>Category</th>
                                <th>Colors</th>
                                </thead>
                                <tbody>
                                @foreach($products as $product)
                                    @if(count($product->colors) != 0)
                                        <tr>
                                            <td>{{$product->productName}}</td>
                                            <td>{{$product->gender}}</td>
                                            <td>{{$product->brand}}</td>
                                            <td>{{$product->price}}</td>
                                            <td>{{$product->category->categoryName}}</td>
                                            <td>
                                            @foreach($product->colors as $color)
                                                    <a href="{{route('product',['productID'=>encrypt($product->productID),'colorID'=>encrypt($color->colorID)])}}"><span class="colorSquare" style="background: {{$color->colorcode}}"></span></a>
                                                @endforeach
                                            </td>
                                            <td>
                                                <a class="btn btn-info btn-fill btn-sm" href="{{route('product',['productID'=>encrypt($product->productID),'colorID'=>encrypt($product->colors[0]->colorID)])}}" title="Edit">
                                                    <span class="fa fa-edit"></span>
                                                </a>
                                                {{--<button class="btn btn-danger btn-fill btn-sm" title="Delete" data-href="{{route('removeWholeProduct',['productID'=>encrypt($product->productID)])}}" data-toggle="modal" data-target="#confirm-delete">--}}
                                                    {{--<span class="fa fa-trash"></span>--}}
                                                {{--</button>--}}

                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                                </tbody>
                            </table>
                            <script>
                                function myFunction() {
                                    var input, filter, table, tr, td, i,idx = 0;
                                    var searchBy = document.getElementById("searchBy").value;
                                    var searchInputs = document.getElementsByName("searchInputs");
                                    for (var j = 0 ; j < searchInputs.length ; j++) {
                                         if(searchInputs[j].value == searchBy){
                                             idx = j;
                                             break;
                                         }
                                    }
                                    input = document.getElementById("myInput");
                                    filter = input.value.toLowerCase();
                                    table = document.getElementById("searchTable");
                                    table.style.display = "";
                                    tr = table.getElementsByTagName("tr");
                                    for (i = 0; i < tr.length; i++) {
                                        td = tr[i].getElementsByTagName("td")[idx];
                                        if (td) {
                                            if (td.innerHTML.replace(/\s+/g, '').toLowerCase().indexOf(filter) > -1 || td.innerHTML.toLowerCase().indexOf(filter) > -1) {
                                                tr[i].style.display = "";
                                            } else {
                                                tr[i].style.display = "none";
                                            }
                                        }
                                    }
                                }
                                function check() {
                                    var input = document.getElementById("myInput").value;
                                    var table , tr , i;
                                    if (input != "") {
                                        table = document.getElementById("myTable");
                                        table.style.display = "none";
                                        myFunction();
                                    }
                                    else{
                                        table = document.getElementById("searchTable");
                                        tr = table.getElementsByTagName("tr");
                                        table.style.display="none";
                                        table = document.getElementById("myTable");
                                        table.style.display="";
                                    }
                                }
                            </script>
                        </div>
                    </div>
                    <div style="float: right">
                        {{$products->links()}}
                    </div>
                </div>


            </div>

        </div>
    </div>
@stop