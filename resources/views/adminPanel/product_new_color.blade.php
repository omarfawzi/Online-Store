@extends('layouts.layout')
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="header">
                            <h4 class="title">New Color</h4>
                        </div>
                        <div class="content">
                            <form method="post" action="{{route('addProductColor')}}" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <input type="hidden" name="productID" value="{{$product->productID}}">
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
                                        <div class="form-group">
                                            <label>Product Name</label>
                                            <input type="text" name="productName" class="form-control border-input"
                                                   placeholder="Product name" value="{{$product->productName}}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Gender</label>
                                            <input id="gender" type="text" name="gender" class="form-control border-input"
                                                   value="{{$product->gender}}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Category</label>
                                            <input id="category" type="text" name="category" class="form-control border-input"
                                                   placeholder="Category" value="{{$product->category->categoryName}}" disabled>
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group"{{ $errors->has('colorcode') ? ' has-error' : '' }}>
                                            <label>Color</label>
                                            <input type="color" name="colorcode" class="form-control border-input"
                                                   id="productColor" value="{{old('colorcode')}}" required>
                                        </div>
                                        @if ($errors->has('colorcode'))
                                            <span class="help-block">
                                                <strong style="color: red;">{{ $errors->first('colorcode') }}</strong>
                                            </span>
                                        @endif
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
                                                    </td>
                                                    <td><input type="number" name="quantity[]" min="0" placeholder="Quantity" class="form-control border-input" required/></td>
                                                    <td><button type="button" name="add" id="add" class="btn btn-success">Add More</button></td>
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
                                            <div class="fileUpload btn btn-primary">
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
                                <div class="text-center">
                                    <button type="submit" class="btn btn-danger btn-fill btn-wd">Add Color</button>
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