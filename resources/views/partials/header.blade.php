<html>

<head>

    {{-- <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=GT-PHG5T9K"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'GT-PHG5T9K');
    </script>
   
    <script>
        gtag('event', 'ads_conversion_PURCHASE_1', {
        });
    </script> --}}
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-5TM37Z9MWC"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        
        gtag('config', 'G-5TM37Z9MWC');
    </script>
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-WQS825W2');
    </script>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="/theme/bootstrap5/css/bootstrap.css">
    <link rel="stylesheet" href="/theme/css/style.css">
    <link rel="stylesheet" href="/theme/css/mobile.css">
    <link rel="icon" sizes="" href="{{asset('theme/img/fav_icons/fav_icon_new.jpg')}}">
    {{-- <link rel="icon" type="image/png" sizes="32x32" href="{{asset('theme/img/fav_icons/favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('theme/img/fav_icons/favicon-16x16.png')}}">
    <link rel="manifest" href="{{asset('theme/img/fav_icons/site.webmanifest')}}"> --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.33/sweetalert2.css">
    <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link href="{{ asset('//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css') }}" rel="stylesheet"
        id="bootstrap-css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link href="https://fonts.cdnfonts.com/css/regular" rel="stylesheet">

    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>

    {{-- owl carasoul --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.2.1/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.2.1/assets/owl.theme.default.min.css">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{asset('theme/bootstrap5/js/bootstrap_alpha.min.js')}}"></script>
    <script>
         var bodyClickEventActive = true;
    </script>
    <style>
        .quantity_count_circle {
            border: 2px solid #7CC633;
            background-color: #7CC633;
            color: #FFFFFF;
            display: none;
            width: 100%;
            height: 40;
            align-items: center;
            justify-content: center;
            font-family: 'poppins';
            /* visibility: hidden; */
            
        }
        .btn-added-to-cart {
            /* visibility: hidden; */
            display: none;
        }
        .added-to-cart {
            /* visibility: hidden; */
            display: none;
        }
        .carousel-indicators li {
            background-color: grey;
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }
        .carousel-indicators .active{
            background-color: #7BC533;
            width: 15px;
            height: 15px;
            border-radius: 50%;
        }
        .left-right-angle-button-slider {
            background: #ffffff;
            border: 1px solid #BDBDBD;
            border-radius: 50%;
            display: flex;
            width: 50%;
            height: 50;
            padding: 0rem;
            align-items: center;
            text-align: center;
            vertical-align: middle;
            justify-content: center;
        }
        .left-right-angle-button-slider:hover {
            text-decoration: none;
            text-underline-position: none;
        }
        .recent_view_header {
            background-color: #008BD3;
        }
    </style>
    <style>
        /* Custom styles for Owl Carousel next and prev buttons */
        .owl-prev,
        .owl-next {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            font-size: 24px;
            border: 2px solid #fff;
            padding: 10px;
            border-radius: 50%;
            cursor: pointer;
            background-color: #ffffff !important;
            color: #000000 !important;
            border: 1px solid #BDBDBD !important;
            font-size: 20px !important;
        }
    
        .owl-prev {
            left: -100px;
            width: 50px;
            height: 50px;
            vertical-align: middle;
            align-items: center;
            display: flex !important;
            justify-content: center;
            border-radius: 50px !important;
        }
    
        .owl-next {
            right: -100px;
            width: 50px;
            height: 50px;
            vertical-align: middle;
            align-items: center;
            display: flex !important;
            justify-content: center;
            border-radius: 50px !important;
        }
        .blog-card-body-height {
            min-height: 13rem!important;
            max-height:13rem !important;
        }
        .qty_font::before {
            border: none !important;
        }
        .custom-border {
            border: 1px solid #EBEBEB  !important;
        }
    
        /* Custom hover styles */
        @media screen and (max-width: 768px) {
            #similar_products_owl_carasoul .owl-nav.disabled {
                display: none !important;
            }
        }
        @media screen and (min-width: 426px) {
            .qty_customize_btn {
                padding: 0.75rem 0.75rem !important;
            }
            .custom-border {
                border: 1px solid #EBEBEB  !important;
                background: #F9F9F9  !important;
            }
        }
        @media screen and (max-width: 425px) {
            .quantity_count_circle {
                font-size: 12px;
                height: 25.19px;
            }
        }
        /* @media screen and (max-width: 425px) {
            .qty_minus_mobile{
                width: 25%;
            }
            .qty_plus_mobile{
                width: 25%;
            }
            .qty_number_mobile{
                width: 50% !important;
            }
        }
        @media screen and (max-width: 425px) and (min-width:376px) {
            .qty_minus_mobile{
                width: 25%;
            }
            .qty_plus_mobile{
                margin-right: -6px;
                margin-left: 2px;
            }
            .qty_number_mobile{
                width: 50% !important;
            }
        } */

        
        .promotional_banner_div , .promotional_banner_div_congrats {
            background-color: #FFFFB4;
        }

        .promotional_banner_text {
            color: #000;
            font-family: 'Poppins';
            font-size: 20px;
            font-style: normal;
            font-weight: 400;
            line-height: 171%;
        }
        .promotional_banner_text_congrats {
            color: #000;
            font-family: 'Poppins';
            font-size: 20px;
            font-style: normal;
            font-weight: 400;
            line-height: 171%;
        }
    </style>
    <link rel="stylesheet" href="{{asset('theme/landing_page/landing_page_style.css')}}">
</head>
<div class="spinner-border text-success hide_default" role="status" id="spinner-global">
    <span class="sr-only">Loading...</span>
</div>
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WQS825W2"
    height="0" width="0" style="display:none;visibility:hidden"></iframe>
</noscript>

@if (auth()->user())
    <input type="hidden" name="notify_user_email_input" class="notifyEmail" id="auth_user_email" value="{{auth()->user()->email}}">
    <input type="hidden" name="notify_user_email_input" class="similar_notifyEmail" id="auth_user_email" value="{{auth()->user()->email}}">
    <input type="hidden" name="notify_user_email_input" class="similar_notifyEmail_sidebar" id="auth_user_email" value="{{auth()->user()->email}}">
@endif