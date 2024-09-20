<script>

    
    
    /** Loard product modal**/
    function loadProductModal(productId){
    $.ajax({
        method: 'GET',
        url: '{{ route("load-product-modal", ":productId") }}'.replace(':productId', productId),
        beforeSend: function(){
            $('.overlay-container').removeClass('d-none');
            $('.overlay').addClass('active');
        },
        success: function(response){
            $(".load_product_modal_body").html(response);
            $('#cartModal').modal('show');
        },
        error: function(xhr, status, error){
            console.error(error);
        },
        complete: function(){
            $('.overlay').removeClass('active');
            $('.overlay-container').addClass('d-none');
        }
    })
}
    function updateSidebarCart(callback = null){
    $.ajax({
        method: 'GET',
        url: '{{ route("get-cart-products") }}',
        success: function(response){
            $('.cart_contents').html(response);
            let cartTotal = $('#cart_total').val();
            let cartCount = $('#cart_product_count').val();
            $('.cart_subtotal').text("{{ currencyPosition(':cartTotal') }}".replace(':cartTotal', cartTotal));
            $('.cart_count').text(cartCount);

            if(callback && typeof callback === 'function'){
                callback();
            }
        },
        error: function(xhr, status, error){
            console.error(error);
        }
    })
}
    
    
    </script>