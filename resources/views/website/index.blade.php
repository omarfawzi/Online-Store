@extends('layouts.customer')
@section('content')
    <div class="top-brands">
        <div class="container">
            <h3>Top Brands</h3>
            <div class="sliderfig">
                <ul id="flexiselDemo1">
                    @foreach($suppliers as $supplier)
                    <li>
                        <a href="{{route('supplierProducts',['supplierName'=>$supplier->supplierName])}}">
                            <img src="{{asset('assets/admin/images/logos/'.$supplier->suppImage)}}" alt=" " class="img-responsive" />
                        </a>
                    </li>
                        @endforeach
                </ul>
            </div>
            <script type="text/javascript">
                $(window).load(function() {
                    $("#flexiselDemo1").flexisel({
                        visibleItems: 4,
                        animationSpeed: 1000,
                        autoPlay: true,
                        autoPlaySpeed: 3000,
                        pauseOnHover: true,
                        enableResponsiveBreakpoints: true,
                        responsiveBreakpoints: {
                            portrait: {
                                changePoint:480,
                                visibleItems: 1
                            },
                            landscape: {
                                changePoint:640,
                                visibleItems:2
                            },
                            tablet: {
                                changePoint:768,
                                visibleItems: 3
                            }
                        }
                    });

                });
            </script>
            <script type="text/javascript" src="{{asset('assets/website/js/jquery.flexisel.js')}}"></script>
        </div>
    </div>
@endsection