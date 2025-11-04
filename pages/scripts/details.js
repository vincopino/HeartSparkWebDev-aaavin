
(function ($) {
    $(document).ready(function () {
        // Show product details in modal
        function showDetails(name, desc, price) {
            $('#productDetailsModalLabel').text(name);
            $('#productDetailsBody').html(`
        <p class="text-light"><strong>Description:</strong> ${desc}</p>
        <p class="text-light"><strong>Price:</strong> ${price}</p>
        `);
            console.log('test');

            var modalEl = document.getElementById('productDetailsModal');
            var modal = new bootstrap.Modal(modalEl);
            modal.show()
        }

        $('.btn-view').on('click', function () {
            const name = $(this).data('name');
            const desc = $(this).data('desc');
            const price = $(this).data('price');
            showDetails(name, desc, price);
            console.log('clicked');
        });
    });
})(jQuery);
