@extends('layouts.layout')
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="header">
                            <h4 class="title">Add Product</h4>
                        </div>
                        <div class="content">
                            <form method="post" action="{{route('addProduct')}}" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Company</label>
                                            <input type="text" style="color:red;" class="form-control border-input"
                                                   disabled placeholder="Company" value="{{auth()->user()->name}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group"{{ $errors->has('productName') ? ' has-error' : '' }}>
                                            <label>Product name</label>
                                            <input type="text" name="productName" class="form-control border-input"
                                                   placeholder="Product name" required>
                                        </div>
                                        @if ($errors->has('productName'))
                                            <span class="help-block">
                                                <strong style="color: red;">{{ $errors->first('productName') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Gender</label>
                                            <select id="gender" class="form-control border-input" name="gender">
                                                <option class="form-control">Men</option>
                                                <option class="form-control">Women</option>
                                                <option class="form-control">Girls</option>
                                                <option class="form-control">Boys</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Brand</label>
                                            <input type="text" name="brand" class="form-control border-input"
                                                   placeholder="Brand" value="{{auth()->user()->name}}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Price</label>
                                            <input type="number" name="price" class="form-control border-input"
                                                   placeholder="Price" min="0" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Category</label>
                                            <select id="category" class="form-control border-input" name="category">
                                                @foreach($categories as $category)
                                                    <option class="form-control" > {{$category->categoryName}} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Color</label>
                                            <input type="color" name="color" class="form-control border-input"
                                                   id="productColor" value="#ffffff" required>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label>Size</label>
                                                <table style="margin-top:-15px" class="table-striped" id="dynamic_field">
                                                    <tr>
                                                        <td>
                                                            <select class="form-control border-input" name="size[]">
                                                                <option hidden>Product Size</option>
                                                            </select>
                                                            {{--<input type="text" name="size[]" placeholder="Product Size" class="form-control border-input"  required/>--}}
                                                        </td>
                                                        <td><input type="number" name="quantity[]" min="0" placeholder="Quantity" class="form-control border-input" required/></td>
                                                        <td><button type="button" name="add" id="add" class="btn btn-success btn-fill">Add More</button></td>
                                                    </tr>
                                                </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Product Images</label>
                                            <input id="uploadFile" name="imageName" type="text"
                                                   class="form-control border-input" value="Choose Images"
                                                   disabled="disabled"/>
                                            <div class="fileUpload btn btn-primary btn-fill">
                                                <span>Browse</span>
                                                <input type="file" name="imageFile[]" id="uploadBtn" class="upload"
                                                       accept="image/*" multiple required/>
                                            </div>
                                            <script>
                                                document.getElementById("uploadBtn").onchange = function () {
                                                    var name = '';
                                                    for (var i = 0; i < this.files.length; i++) {
                                                        name += this.files[i].name;
                                                        name += ',';
                                                    }
                                                    document.getElementById("uploadFile").value = name;
                                                };
                                            </script>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <label>About Product</label>
                                            <textarea rows="5" name="description" class="form-control border-input"
                                                      placeholder="Product Description"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-info btn-fill btn-wd">Add Product</button>
                                </div>
                                <div class="clearfix"></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        jQuery(window).on("load", function(){
            $('#gender').change();
        });
        $(document).ready(function () {
            var elm ="{{json_encode($clothesSizes)}}";
            elm = JSON.parse(elm.replace(/&quot;/g,'"'));
            $('#gender').change(function () {
                $('#category').empty();
                var gender = $('#gender').val();
                var genderCategroies = elm[$('#gender').val()];
                $.each(genderCategroies,function (key) {
                    $('#category').append($("<option></option>").attr("value", key).text(key));
                });
                $('#category').change();
            });
            $('#category').change(function () {
                $('[name="size[]"]').empty();
                var Sizes = elm[$('#gender').val()][$('#category').val()];
                $.each(Sizes, function(key,value) {
                    $('[name="size[]"]').append($("<option></option>").attr("value", value).text(value));
                });
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            var i = 1;
            var elm ="{{json_encode($clothesSizes)}}";
            elm = JSON.parse(elm.replace(/&quot;/g,'"'));
            $('#add').click(function () {
                i++;
                $('#dynamic_field')
                    .append
                    ('<tr id="row' + i + '" style="background-color: transparent">' +
                        '<td><select class="form-control border-input" name="size[]"></select></td>' +
                        '<td><input type="number" name="quantity[]" placeholder="Quantity" min="0" class="form-control border-input" required/>' +
                        '</td>' +
                        '<td><button type="button" name="remove" id="' + i + '" class="btn btn-danger btn-fill btn_remove">Remove</button></td></tr>'
                    );
                $('#category').change();
            });
            $(document).on('click', '.btn_remove', function () {
                var button_id = $(this).attr("id");
                $('#row' + button_id + '').remove();
            });
        });
    </script>
@stop