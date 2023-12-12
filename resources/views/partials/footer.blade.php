<script src="https://unpkg.com/@popperjs/core@2"></script>
<script src="{{ asset('//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('/theme/bootstrap5/js/bootstrap.js') }}"></script>
<script src="{{ asset('//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js') }}"></script>

<script src="https://kit.fontawesome.com/ec19ec29f3.js" crossorigin="anonymous"></script>
<script src="https://unpkg.com/feather-icons"></script>
<script id="ze-snippet" src="{{asset('zendesk.js?key=c226feaf-aefa-49d4-ae97-5b83a096f475')}}"></script>

<script type="text/javascript">
    var popover = new bootstrap.Popover(document.querySelector('.cart-price'), {
        container: 'body',
        html: true
    });
    feather.replace();
</script>
<script>
    // delete employee ajax request
    $(document).on('click', '.deleteIcon', function(e) {
        e.preventDefault();
        var id = $(this).attr('id');
        let csrf = '{{ csrf_token() }}';
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route('secondary_user.delete') }}',
                    method: 'delete',
                    data: {
                        id: id,
                        _token: csrf
                    },
                    success: function(response) {
                        Swal.fire(
                            'Deleted!',
                            'Your file has been deleted.',
                            'success'
                        )
                        $('#row-' + id).remove();
                    }
                });
            }
        })
    });
</script>
<script type="text/javascript">
    let dropdowns = document.querySelectorAll('.dropdown-toggle')
    dropdowns.forEach((dd) => {
        dd.addEventListener('click', function(e) {
            var el = this.nextElementSibling
            el.style.display = el.style.display === 'block' ? 'none' : 'block'
        })
    });

    $(document).ready(function() {
        $(document).on('click', '#copyUrl', function() {
            $('#custom_loader').removeClass('d-none');
            let textValue = $(this).attr('data-id');
            var temp = $("<input>");
            $("body").append(temp);
            temp.val(textValue).select();
            document.execCommand("copy");
            temp.remove();
            console.timeEnd('time1');
            $('#custom_loader').addClass('d-none');
        });
        $('.login-info-box').fadeOut();
        $('.login-show').addClass('show-log-panel');
    });
    $('.login-reg-panel input[type="radio"]').on('change', function() {
        if ($('#log-login-show').is(':checked')) {
            $('.register-info-box').fadeOut();
            $('.login-info-box').fadeIn();
            $('.white-panel').addClass('right-log');
            $('.register-show').addClass('show-log-panel');
            $('.login-show').removeClass('show-log-panel');
        } else if ($('#log-reg-show').is(':checked')) {
            $('.register-info-box').fadeIn();
            $('.login-info-box').fadeOut();
            $('.white-panel').removeClass('right-log');
            $('.login-show').addClass('show-log-panel');
            $('.register-show').removeClass('show-log-panel');
        }
    });

    //set active class on click
    var path = window.location.href;
    $('.account_navigation li a').each(function() {
        if (this.href == path) {
            $(this).parent().addClass('active');
        }
    });
</script>
</script>
<script type="text/javascript">
    $(document).ready(function() {
        // popovers initialization - on hover
        $('[data-toggle="popover-click"]').popover({
            html: true,
            trigger: 'clcik',
            placement: 'top',
            sanitize: false,
            content: function() {
                return '<img src="' + $(this).data('img') + '" />';
            }
        });

        // popovers initialization - on click
        $('[data-toggle="popover-hover"]').popover({

            html: true,
            trigger: 'hover',
            placement: 'top',
            sanitize: false,
            content: $('#popover-form').html(),
        });
        var invitation_sent = sessionStorage.getItem('invitation');
        if (invitation_sent == 1) {
            $("#additional_users").addClass("active");
            $("#additional-users").removeClass("d-none");
            $('#intro').addClass('d-none');
            $('.nav-pills #dashboard').addClass('active');
            $('#dashboard').removeClass('active');
        } else {
            $("#additional_users").removeClass("active");
        }
    });
</script>
<script>
    $(document).ready(function() {
        var input = $('.mobileFormat').html();
        var formattedInput = formatPhoneNumber(input);
        $('.mobileFormat').html(formattedInput);
    });

    function formatPhoneNumber(input) {
        // Remove all non-digit characters from the input
        var digits = input.replace(/\D/g, '');

        // Format the digits as (555) 123-4567
        var formattedInput = '';
        for (var i = 0; i < digits.length; i++) {
            if (i === 0) {
                formattedInput += '(';
            } else if (i === 3) {
                formattedInput += ') ';
            } else if (i === 6) {
                formattedInput += '-';
            }
            formattedInput += digits.charAt(i);
        }

        return formattedInput;
    }
</script>
<script>
    $(document).ready(function() {
        var list_id = $("#list_id").val();
        if (list_id == '') {
            $(".btn-add-to-cart").prop('disabled', true);
        } else {
            $(".btn-add-to-cart").prop('disabled', false);
        }
        $('body').on('click', '.btn-add-to-cart', function() {
            var id = $(this).attr('id');
            var product_id = id.replace('btn_', '');
            var row = $('#product_row_' + product_id).length;

            if (row > 0) {
                var difference = 0;
                var subtotal_before_update = parseFloat($('#subtotal_' + product_id).html());
                console.log('difference => ' + difference);
                console.log('sub total before update  => ' + subtotal_before_update);

                var retail_price = parseFloat($('#retail_price_' + product_id).html());
                var quantity = parseFloat($('#quantity_' + product_id).val());
                var subtotal = parseFloat($('#subtotal_' + product_id).html());

                quantity++;
                subtotal = retail_price * quantity;

                difference = subtotal_before_update - subtotal;

                console.log('difference => ' + difference);

                var grand_total = $('#grand_total').html();
                grand_total = parseFloat(grand_total);

                console.log('Grand Total => ' + grand_total);


                grand_total = grand_total - difference;
                $('#grand_total').html(grand_total);

                console.log('Grand Total => ' + grand_total);

                $('#quantity_' + product_id).val(quantity);
                $('#subtotal_' + product_id).html(subtotal);
                return false;
            }

            jQuery.ajax({
                url: "{{ url('admin/add-to-list') }}",
                method: 'post',
                data: {
                    "_token": "{{ csrf_token() }}",
                    product_id: product_id,
                    //option_id: option_id
                },
                success: function(response) {
                    $('#product_list').append(response);
                    console.log(response);
                    var grand_total = $('#grand_total').html();
                    grand_total = parseFloat(grand_total);

                    var retail_price = $('#btn_' + product_id).attr('data-retail-price');
                    console.log(retail_price);

                    var subtotal = retail_price * 1;

                    grand_total = grand_total + subtotal;

                    $('#grand_total').html(grand_total);
                }
            });
        });
        $('.all_items:first').addClass('active');
    });
</script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.2.1/owl.carousel.min.js"></script>
    <script>
        $('#owl-carousel-landing-page').owlCarousel({
            rtl:false,
            loop:false,
            margin:10,
            dots:false,
            nav:true,
            navText: ["<i class='fas fa-angle-left'></i>", "<i class='fas fa-angle-right'></i>"],
            responsive:{
                0:{
                    items:1,
                    nav:false
                },
                600:{
                    items:2,
                    slideBy:2,
                    nav:false
                },
                1000:{
                    items:3,
                    slideBy:3,
                },
            }
        });
        $(document).ready(function() {
            function calculateItemsToShow() {
            // Your logic to dynamically calculate the number of items
                var screenWidth = $(window).width();
                if (screenWidth > 0 && screenWidth < 600) {
                    return 1;
                } 
                if (screenWidth >= 600 && screenWidth < 1000) {
                    return 2;
                }
                if (screenWidth >= 1000 && screenWidth < 1200) {
                    return 3;
                }
                if (screenWidth >= 1200 && screenWidth < 1400) {
                    return 4;
                }
                if (screenWidth >= 1400 && screenWidth < 1800) {
                    return 6;
                }
                if (screenWidth >= 1800 && screenWidth < 2250) {
                    return 8;
                }
                if (screenWidth >= 2250) {
                    return 10;
                }
            }
            function updateOptions() {
                var totalItems = $('.similar_items_div').length;
                var itemsToShow = calculateItemsToShow();
                var centerItems = totalItems < itemsToShow;
                if (totalItems >= itemsToShow) {
                    $('.similar_products_owl_carasoul .owl-stage-outer').removeClass('d-flex');
                }
                $('.similar_products_owl_carasoul').owlCarousel({
                    rtl:false,
                    loop:false,
                    // center: centerItems,
                    margin:10,
                    nav:true,
                    navText: ["<i class='fas fa-angle-left'></i>", "<i class='fas fa-angle-right'></i>"],
                    responsive:{
                        0:{
                            items:calculateItemsToShow(),
                            nav:false,
                            slideBy:1,
                        },
                        600:{
                            items:calculateItemsToShow(),                    
                            nav:false,
                            slideBy:2,
                        },
                        1000:{
                            items:calculateItemsToShow(),                    
                            slideBy:3,
                        },
                        1200:{
                            items:calculateItemsToShow(),                    
                            slideBy:4,
                        },
                        1400: {
                            items:calculateItemsToShow(),                    
                            slideBy:6,
                        },
                        1800:{
                            items:calculateItemsToShow(),                    
                            slideBy:8,
                        },
                        2250:{
                            items:calculateItemsToShow(),
                            slideBy:10,
                        }
                    }
                });
            }
            updateOptions(); // Initial setup

            // Update options on window resize
            $(window).resize(function () {
                updateOptions();
            });

            var totalItems = $('.similar_items_div').length;
            var itemsToShow = calculateItemsToShow();
            var centerItems = totalItems < itemsToShow;
            if (totalItems >= itemsToShow) {
                $('.similar_products_owl_carasoul .owl-stage-outer').css('display', 'block');    
            }
        });
    </script>