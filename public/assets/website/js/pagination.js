$(document).ready(function () {
    $('#loadMore').click(function (counter) {
        var url = window.location.href;
        var cnt = $('#counter').val();
        $.ajax({
            type:'GET',
            url:url,
            data:{index:cnt,sortBy:$('#popularity').val()},
            success:function(data){
                $('#counter').val(cnt+1);
                var products = data.products;
                for (var i = 0 ; i < products.length ; i++){
                    var images = "";
                    var href = 'http://'+window.location.hostname +'/product/'+products[i].productName+'/'+products[i].color;
                    for (var j = 0 ; j < 6/products[i].image.length ; j++){
                        for (jj = 0 ; jj < products[i].image.length ; jj++ ){
                            images += "<img src='"+products[i].image[jj]+"' alt=' ' class='img-responsive' />";
                        }
                    }
                    var div = "<div name='controlDiv[]' class='col-md-4 agileinfo_new_products_grid agileinfo_new_products_grid_dresses'>" +
                        " <div class='agile_ecommerce_tab_left dresses_grid'>" +
                        " <div class='hs-wrapper hs-wrapper2'>" + images+
                        "<div class='w3_hs_bottom w3_hs_bottom_sub1'> " +
                        "<ul> " +
                        "<li> " +
                        "<a href='"+href+"'><span class='glyphicon glyphicon-eye-open' aria-hidden='true'></span></a>" +
                        " </li> " +
                        "</ul> " +
                        "</div> " + "</div> " + "<br> " +
                        "<p style='color: grey;'>"+products[i].brand+"</p> " +
                        "<h5 style='display: inline;'><a href='"+href+"'>"+products[i].productName+"</a></h5> " +
                        "<div class='simpleCart_shelfItem'> <small  style='color: grey;'>EGP&nbsp;"+products[i].price+"</small> <br> " +
                        "<hr style='border-color: darkgray'> " +
                        "</div> </div> </div>";
                    $('#productsDiv').append(div);
                }
                if (data.stop||(products.length == 0)){
                    $('#loadMore').hide();
                }
            }
        });
    });
});