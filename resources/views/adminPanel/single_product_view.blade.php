@extends('layouts.layout')
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="gallery">
                @foreach($product->colors as $color)
                    @if($color->colorID == $colour->colorID)
                        <div class="previews">
                            @foreach($color->images as $image)
                                <a name="imageView"
                                   data-content1="{{route('setMain',['colorID'=>encrypt($colour->colorID),'imageID'=>encrypt($image->imageID)])}}"
                                   data-content2="{{route('removeImage',['colorID'=>encrypt($colour->colorID),'imageID'=>encrypt($image->imageID)])}}"
                                   class="{{($mainImage != null && $image->imageID == $mainImage->imageID)?'selected':''}}"
                                   data-full="{{asset('assets/images/products/'.$image->image)}}">
                                    <img src="{{asset('assets/images/products/'.$image->image)}}" width="20px"
                                         height="30px"/>
                                </a>
                            @endforeach
                        </div>
                    @endif
                @endforeach
                <div class="full">
                    <div class="image">
                        <img style="border-radius: 5px;box-shadow:  0 0 5px #888888;"
                             src="{{($mainImage != null)?asset('assets/images/products/'.$mainImage->image):'no main defined'}}"
                             width="350" height="80"/>
                    </div>
                    <div class="caption">
                        <br>
                        <a id="mainBtn"
                           href="{{($mainImage != null)?route('setMain',['colorID'=>encrypt($colour->colorID),'imageID'=>encrypt($mainImage->imageID)]):''}}"
                           style="margin-left: 40px;" class="btn btn-primary btn-fill">Set Main</a>
                        <a id="removeBtn"
                           href="{{($mainImage != null)?route('removeImage',['colorID'=>encrypt($colour->colorID),'imageID'=>encrypt($mainImage->imageID)]):''}}"
                           style="margin-left: 65px;" class="btn btn-danger btn-fill">Remove</a>
                    </div>
                </div>
            </div>
            <div class="row">
                <form method="post" action="{{route('updateProduct')}}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="productID" value="{{$product->productID}}">
                    <input type="hidden" name="colorID" value="{{$colour->colorID}}">

                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Company</label>
                            <input type="text" style="color:red;" class="form-control border-input"
                                   disabled placeholder="Company" value="{{auth()->user()->name}}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Product name</label>
                            <input type="text" name="productName" class="form-control border-input"
                                   placeholder="Product name"
                                   value="{{$product->productName}}"
                                   required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Product Status</label>
                            <input type="text" name="productName" class="form-control border-input"
                                   value="{{($colour->productStatus=='0')?'Waiting':(($colour->productStatus=='1')?'Approved':'Rejected')}}"
                                   disabled>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Gender</label>
                            <select id="gender"
                                    class="form-control border-input"
                                    name="gender">
                                <option hidden>{{$product->gender}}</option>
                                <option class="form-control">Men</option>
                                <option class="form-control">Women</option>
                                <option class="form-control">Girls</option>
                                <option class="form-control">Boys</option>
                                <option class="form-control">Baby</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Brand</label>
                            <input type="text" name="brand"
                                   class="form-control border-input"
                                   placeholder="Brand" value="{{$product->brand}}"
                                   required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Price</label>
                            <input type="number" name="price"
                                   class="form-control border-input"
                                   placeholder="Price" min="0" value="{{$product->price}}"
                                   required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Category</label>
                            <select id="category"
                                    class="form-control border-input"
                                    name="category">
                                <option hidden>{{$product->category->categoryName}}</option>
                                @foreach($categories as $category)
                                    <option class="form-control"> {{$category->categoryName}} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group"{{ $errors->has('colorcode') ? ' has-error' : '' }}>
                            <label>Color</label>
                            <input name="colorcode"
                                   type="color" class="form-control border-input"
                                   id="productColor" value="{{$colour->colorcode}}"
                                   required>

                        </div>
                        @if ($errors->has('colorcode'))
                            <span class="help-block">
                                <strong style="color: red;">{{ $errors->first('colorcode') }}</strong>
                            </span>
                        @endif
                    </div>
                    @if(count($otherColors)!= 0)
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Other Colors</label>
                                <div class="form-control" style="background: transparent">
                                    @foreach($otherColors as $color)
                                        <a name="otherColors"
                                           href="{{route('product',['productID'=>encrypt($product->productID),'colorID'=>encrypt($color->colorID)])}}"><span
                                                    class="colorSquare"
                                                    style="background: {{$color->colorcode}}"></span></a>
                                    @endforeach
                                </div>
                                {{--<script>--}}
                                {{--$(document).ready(function () {--}}
                                {{--$('[name="otherColors"]').click(function () {--}}
                                {{--//   console.log($(this).attr('data-href'));--}}
                                {{--window.location= $(this).attr('data-href');--}}
                                {{--});--}}
                                {{--});--}}

                                {{--</script>--}}
                            </div>

                        </div>
                    @endif
                    <div class="col-md-5">
                        <div class="form-group">
                            <label>Sizes</label>
                            <table style="margin-top:-15px" class="table-striped" id="dynamic_field">
                                @for($i = 0 ; $i < count($product->colors[0]->sizes) ; $i++)
                                    <tr id="row{{$i}}" style="background-color: transparent">
                                        <td>
                                            <select
                                                    class="form-control border-input"
                                                    name="size[]">
                                                <option hidden>{{$product->colors[0]->sizes[$i]->size}}</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input
                                                    type="number" name="quantity[]" min="0"
                                                    placeholder="Quantity" class="form-control border-input"
                                                    value="{{$product->colors[0]->sizes[$i]->pivot->availableUnits}}"
                                                    required/></td>
                                        @if(auth()->user()->type != 'admin')

                                            <td>
                                                <button onclick="disable()" type="button" name="remove" id="{{$i}}"
                                                        class="btn btn-danger btn-fill btn_remove">
                                                    Remove
                                                </button>
                                            </td>
                                        @endif
                                    </tr>
                                @endfor
                                @if(auth()->user()->type != 'admin')
                                    <tr style="background-color: transparent">
                                        <td>
                                            <button onclick="disable()" type="button" name="add" id="add"
                                                    class="btn btn-success btn-fill">
                                                Add Size
                                            </button>
                                        </td>
                                    </tr>
                                @endif
                            </table>
                            <script>
                                function disable() {
                                    document.getElementById('applyBtn').disabled = false;
                                }
                            </script>
                        </div>
                    </div>
                    {{--<div class="col-md-5" style="margin-left: 30px;">--}}
                    {{--<br>--}}
                    {{--<br>--}}
                    {{--<div class="form-group">--}}
                    {{--<label>About Product</label>--}}
                    {{--<textarea rows="5" name="description" class="form-control border-input"--}}
                    {{--placeholder="Product description">{{$product->description}}</textarea>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    @if(auth()->user()->type != 'admin')
                        <div class="row" style="margin-left: 40px;">
                            <div class="col-md-5 form-group">
                                <br>
                                <label>Product Images</label>
                                <input id="uploadFile" name="imageName" type="text"
                                       class="form-control border-input" width="20px" value="Upload More Images"
                                       disabled="disabled"/>
                                <div class="fileUpload btn btn-primary">
                                    <span>Browse</span>
                                    <input type="file" name="imageFile[]" id="uploadBtn" class="upload"
                                           accept="image/*" multiple/>
                                </div>
                                <script>
                                    document.getElementById("uploadBtn").onchange = function () {
                                        var name = '';
                                        for (var i = 0; i < this.files.length; i++) {
                                            name += this.files[i].name;
                                            name += ',';
                                        }
                                        document.getElementById('applyBtn').disabled = (name == '');
                                        document.getElementById("uploadFile").value = name;
                                    };
                                </script>
                            </div>
                        </div>
                    @endif
                    <div class="row" style="text-align: center">

                        <div class="col-md-12 form-group">
                            <br>
                            <br>
                            <br>
                            <button type="submit" id="applyBtn" class="btn btn-info btn-fill btn-wd">Update
                            </button>
                            @if(auth()->user()->type != 'admin')
                                <a href="{{route('newColor',['productID'=>encrypt($product->productID)])}}"
                                   class="btn btn-primary btn-fill btn-wd">
                                    <span class="fa fa-plus-circle"></span>
                                    Add New Color
                                </a>
                            @endif
                            <button id="button1" class="btn btn-danger btn-fill btn-wd" title="Delete"
                                    data-href="{{route('removeProduct',['productID'=>encrypt($product->productID),'colorID'=>encrypt($colour->colorID)])}}"
                                    data-toggle="modal" data-target="#confirm-delete">
                                <span class="fa fa-trash"></span>
                                Delete
                            </button>
                        </div>
                    </div>
                    @if(auth()->user()->type == 'admin')
                        <div class="row" style="text-align: center">
                            <br>
                            <br>
                            <a href="{{($colour->productStatus == '2')?'#':route('reject',['colorID'=>encrypt($colour->colorID)])}}"
                               class="btn btn-danger btn-fill btn-wd" {{($colour->productStatus == '2')?'disabled':''}}>
                                <span class="fa fa-times"></span>
                                Reject
                            </a>
                            <a href="{{($colour->productStatus == '1')?'#':route('approve',['colorID'=>encrypt($colour->colorID)])}}"
                               class="btn btn-success btn-fill btn-wd" {{($colour->productStatus == '1')?'disabled':''}}>
                                <span class="fa fa-check"></span>
                                Approve
                            </a>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        jQuery(window).on("load", function () {
            var elm = "{{json_encode($clothesSizes)}}";
            elm = JSON.parse(elm.replace(/&quot;/g, '"'));
            var Sizes = elm[$('#gender').val()][$('#category').val()];
            $('[name="size[]"]').each(function () {
                var elm = $(this);
                $.each(Sizes, function (key, value) {
                    elm.append($("<option></option>").attr("value", value).text(value));
                });
            });
        });
        $(document).ready(function () {
            var i = 0;
            var elm = "{{json_encode($clothesSizes)}}";
            elm = JSON.parse(elm.replace(/&quot;/g, '"'));
            $('#gender').change(function () {
                $('#category').empty();
                var gender = $('#gender').val();
                var genderCategroies = elm[$('#gender').val()];
                $.each(genderCategroies, function (key) {
                    $('#category').append($("<option></option>").attr("value", key).text(key));
                });
                if (i > 0)
                    $('#category').change();
                i++;
            });
            $('#category').change(function () {
                $('[name="size[]"]').empty();
                var Sizes = elm[$('#gender').val()][$('#category').val()];
                $.each(Sizes, function (key, value) {
                    $('[name="size[]"]').append($("<option></option>").attr("value", value).text(value));
                });
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            var i = 1;
            var elm = "{{json_encode($clothesSizes)}}";
            elm = JSON.parse(elm.replace(/&quot;/g, '"'));
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
                var cnt = 0;
                var Sizes = elm[$('#gender').val()][$('#category').val()];
                $('[name="size[]"]').each(function () {
                    var elm = $(this);
                    if (elm.children().length == 0) {
                        $.each(Sizes, function (key, value) {
                            elm.append($("<option></option>").attr("value", value).text(value));
                        });
                    }
                });

                document.getElementById('applyBtn').disabled = false;
            });
            $(document).on('click', '.btn_remove', function () {
                var button_id = $(this).attr("id");
                $('#row' + button_id + '').remove();
            });
        });
    </script>
@endsection