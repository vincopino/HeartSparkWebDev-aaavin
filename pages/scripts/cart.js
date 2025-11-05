
// AJAX for adding to cart
$(document).ready(function() {
    console.log('jQuery loaded and ready');
    $('.btn-add').click(function() {
        console.log('Button clicked');
        const button = $(this);
        const productId = button.data('product-id');
        console.log('Product ID:', productId);
        const productName = button.data('name');


        // AJAX request to add to cart
        $.ajax({
            url: 'server/add_to_cart.php',
            method: 'POST',
            data: {
                product_id: productId
            },
            dataType: 'json',
            success: function(response) {
                console.log('Ajax response:', response);
                if (response.success) {
                    // Show success message
                    $('#cart-message').html(
                        '<div class="alert alert-success alert-dismissible fade show">' +
                        productName + ' added to cart! ' +
                        '<a href="cart.php" class="alert-link">View Cart</a>' +
                        '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
                        '</div>'
                    );

                } else {
                    $('#cart-message').html(
                        '<div class="alert alert-danger alert-dismissible fade show">' +
                        response.message +
                        '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
                        '</div>'
                    );
                }
            },
            error: function(xhr, status, error) {
                console.log('Ajax error:', status, error);
                console.log('Response:', xhr.responseText);
                $('#cart-message').html(
                    '<div class="alert alert-danger alert-dismissible fade show">' +
                    'An error occurred. Please try again.' +
                    '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
                    '</div>'
                );
            }
        });
    });
});


