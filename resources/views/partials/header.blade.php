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
    <link rel="stylesheet" href="{{asset('theme/css/bootstrap-tagsinput.css')}}">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.10.8/sweetalert2.all.js" integrity="sha512-mDHahYvyhRtp6zBGslYxaLlAiINPDDEoHDD7nDsHoLtua4To71lDTHjDL1bCoAE/Wq/I+7ONeFMpgr62i5yUzw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
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
        .top_header_banner {
            padding: 0.5rem;
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
        @media screen and (max-width: 550px) and (min-width: 426px) {
            .quantity_count_circle {
                font-size: 12px;
                height: 25.19px;
            }
            .top_header_banner {
                /* padding: 0rem !important; */
                font-size: 11px !important;
            }
        }
        @media screen and (max-width: 425px) and (min-width: 280px) {
            .quantity_count_circle {
                font-size: 12px;
                height: 25.19px;
            }
            .top_header_banner {
                /* padding: 0rem !important; */
                font-size: 8px !important;
            }
        }

        .top_header_banner {
            padding: 0.5rem;
            font-size: 20px;
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
        .tips_trends {
            font-family: 'poppins';
            color:#272424;
            font-size: 20px;
            font-weight: 500;
            letter-spacing: 5%;
        }
    
        .latest_from_blogs {
            font-family: 'poppins';
            color:#000000;
            font-size: 48px;
            font-weight: 600;
            letter-spacing: 1px;
        }
    
        .blog_separate_head {
            font-family: 'poppins';
            color:#7CC633;
            font-size: 48px;
            font-weight: 600;
            letter-spacing: 1px;
        }
    
        .guide_to_growth {
            font-family: 'poppins';
            color:#000000;
            font-size: 14px;
            font-weight: 500;
            letter-spacing: 1px;
        }
    
        .border-bottom-blog-color {
            border-bottom: 1px solid #7CC633;
        }
    
        #searchBarInput {
            position: relative;
            background: transparent;
            font-size: 16px;
            font-weight: 400;
            font-family: 'poppins';
            color: #979797;
            outline: 1px  #EBEBEB;
            border: 1px solid #EBEBEB;
            background-color: #ffffff;
        }
    
    
        .position-icon {
            position: relative;
            left: 90%;
            top: -1.5rem;
            color: #7CC633;
            font-size: 12px;
        }
        .blog_date {
            font-size: 14px;
            font-weight: 600;
            color: #73BC2B;
            font-family: 'Poppins';
            line-height: 20px;
        }
        .single_blog_heading {
            font-size: 22px;
            font-weight: 600;
            color: #1A1A1A;
            font-family: 'Poppins';
            line-height: 32px;
        }
        .single_blog_description {
            font-size: 16px;
            font-weight: 400;
            color: #667085;
            font-family: 'Poppins';
            line-height: 24px;
        }
        .single_blog_detail_btn {
            background-color: #73BC2B;
            font-size: 15px;
            font-weight: 500;
            font-family: 'Poppins';
            border-radius: 0px;
            color: #FFFFFF;
        }
        .blog_div {
            border: 1px solid #EBEBEB;
        }

        .blog_image_link:hover {
            filter: brightness(0) saturate(100%) invert(72%) sepia(33%) saturate(612%) hue-rotate(48deg) brightness(101%) contrast(91%);
        }
        .blog_image_container {
            position: relative;
            display: inline-block;
        }

        .blog_image_link {
            display: block;
            width: 100%;  /* Adjust as needed */
            height: auto; /* Adjust as needed */
            transition: all 0.3s ease;
        }

        .blog_image_container::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #7CC633;  /* Green color */
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .blog_image_container:hover::after {
            opacity: 0.1;  /* Adjust the opacity to control the intensity of the green overlay */
        }
        .floating-button {
            position: fixed;
            bottom: 65px;
            right: 40px;
            width: 70px;
            height: 70px;
            border-radius: 50%;
            border-color: #7BC533;
            background-color: #7BC533;
            color: white;
            border: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            z-index: 1000;
            overflow: hidden; /* Ensure content fits within circle */
        }
        
        .average-rating {
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            width: 100%; /* Make stars fill button */
            height: 100%; /* Make stars fill button */
        }

        .star {
            font-size: 1rem; /* Adjust size of stars */
        }

        .floating-button:hover {
            background-color: #7BC533; /* Darker shade on hover */
            transform: scale(1.05);
        }

        .floating-button:focus {
            outline: none;
            background-color: #7BC533; /* Darker shade on focus */
        }
        .floating-button:active {
            outline: none;
            background-color: #7BC533; /* Darker shade on focus */
        }
        #averageRatingStars {
            font-size: 1rem;
        }
        .rating-container {
            position: relative; /* Allows positioning of stars */
            margin-top: 10px;
        }
        #default_average {
            display: block; /* Stack elements */
            font-size: 20px; /* Adjust size as necessary */
            margin-top: 5px;
        }

        #averageRatingStars {
            position: absolute; /* Position stars above average rating */
            top: -12px; /* Adjust vertical position */
            left: 50%; /* Center stars horizontally */
            transform: translateX(-50%); /* Center adjustment */
            font-size: 14px; /* Adjust size as necessary */
        }
        .selection_company_heading {
            font-family: 'poppins';
            color:#000000;
            font-size: 14px;
            font-weight: 500;
            letter-spacing: 1px;
        }
        .select_company_button_pop_up , .select_company_button_pop_up:hover , .select_company_button_pop_up:focus {
            background-color: #7CC633;
            font-size: 15px;
            font-weight: 500;
            font-family: 'Poppins';
            /* border-radius: 30px; */
            color: #FFFFFF;
            border: 1px solid #7CC633;
        }
        @media screen and (min-width: 768px) {
            .all_blogs_div_padding {
                background-color: #F8FAF8;
            }
        }

        @media screen and (max-width: 767px) {
            .guide_to_growth {
                font-size: 12px;
            }
            .latest_from_blogs {
                font-size: 18px;
            }
            .blog_separate_head {
                font-size: 18px;
            }
            .tips_trends {
                font-size: 14px;
            }
            .blog_date {
                font-size: 12px;
                font-weight: 500;
            }

            .blog_date_div {
                border-bottom: 1px solid #EBEBEB;
            }
            .single_blog_heading {
                font-size: 18px;
            }
            .single_blog_description {
                font-size: 14px;
            }
            .single_blog_detail_btn {
                font-size: 14px;
            }
            .blog_div {
                background-color: #F8FAF8;
                border: none;
                border-radius: 15px !important;
            }
            .blogs_heading_div {
                padding: 15px;
            }
            .all_blogs_div_padding {
                padding: 0px 30px;
            }
        }
        .btn-update-defaul-addresses  , .btn-update-defaul-addresses:hover , .btn-update-defaul-addresses:focus {
            background-color: #7CC633;
            font-size: 14px;
            font-weight: 400;
            font-family: 'Poppins';
            border-radius: 0px;
            color: #FFFFFF;
            border-color: #7CC633;
        }

        .stock_tooltip {
            position: relative;
            display: inline-flex;
            align-items: center;
        }

        .stock_tooltip_message {
            position: absolute;
            background: #fff;
            color: #000;
            padding: 8px;
            border-radius: 4px;
            font-size: 10px;
            transform: translateX(-50%);
            display: none;
            z-index: 1000;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        .show_message {
            cursor: pointer;
        }
    </style>
    <link rel="stylesheet" href="{{asset('theme/landing_page/landing_page_style.css')}}">

    <!-- Meta Pixel Code -->
    <script>
    !function(f,b,e,v,n,t,s)
    {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};
    if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
    n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];
    s.parentNode.insertBefore(t,s)}(window, document,'script',
    'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '1776526269774698');
    fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
    src="https://www.facebook.com/tr?id=1776526269774698&ev=PageView&noscript=1"
    /></noscript>
    <!-- End Meta Pixel Code -->
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