@php
   $enable_newsletter = \App\Models\AdminSetting::where('option_name', 'enable_newsletter')->first(); 
@endphp
@if ((!empty($enable_newsletter) && $enable_newsletter->option_value == 'Yes'))
    <div class="row justify-content-center p-5" style="background-color:#F7F7F7">
        <div class="col-md-8">
            <h2 class="text-center newsletter_title">Subscribe to our Newsletter</h2>
        </div>
        <div class="col-md-8">
        <p class="text-center newsletter_para">Get the latest updates and offers directly in your inbox.</p>
        </div>
        <div class="col-md-8">
            <form action="{{route('subscribe_newsletter')}}" method="post" class="" id="newsletter-subscribe-form">
                @csrf
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="form-group mb-0">
                            <div class="input-group">
                                <input type="email" name="email" required class="form-control newsletter_email border-0" placeholder="Enter your email address">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-newsletter">Subscribe</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endif
<footer class="text-white text-lg-start footer-container-product-footer h-900 mt-5 desktop-view">
    <div class="container-fluid p-0">
        <div class="container">
            <div class="row">
                <div
                    class="col-xl-3 col-lg-3 col-md-0 col-sm-0 col-xs-0  main-page-footer-section footer-section-product">
                    <h5 class="mb-4 contact-us-footer">CONTACT US </h5>
                    <span>
                        <img src="/theme/img/map-pin.png" class="img-fluid">
                    </span>
                    <span class="ms-2 pra-footer fw-semibold">{!! \App\Helpers\SettingHelper::getSetting('store_address_line_1') !!}
                    </span>
                    <p class="ms-4 mt-0 pra-footer">&nbsp;
                        {!! \App\Helpers\SettingHelper::getSetting('store_address_line_2') !!}
                    </p>
                    <span>
                        <img src="/theme/img/phone-call.png" class="img-fluid">
                    </span>
                    <span class="ms-2 pra-footer fw-semibold">{{ \App\Helpers\SettingHelper::getSetting('store_phone_number') }}
                    </span>
                    <p class="ms-4 ms-4-footer mt-0">&nbsp;
                        <span class="text-success sm-4-footer">
                            {!! \App\Helpers\SettingHelper::getSetting('timings_part1') !!}
                        </span> {!! \App\Helpers\SettingHelper::getSetting('timings_part2') !!}
                    </p>
                    <p>
                        <?php 
                            $yelp_link = \App\Helpers\SettingHelper::getSetting('yelp_link'); 
                            $instagram_link = \App\Helpers\SettingHelper::getSetting('instagram_link');
                            $facebook_link = \App\Helpers\SettingHelper::getSetting('facebook_link');
                        ?>
                        @if (!empty($yelp_link))
                            <a target="_blank" href="{{ \App\Helpers\SettingHelper::getSetting('yelp_link') }}"
                                class="text-light pra-footer" style="text-decoration: none">
                                <img src="/theme/img/footer-yami-icon.png" alt="" class="img-fluid" style="width: 32px;" />
                            </a>
                        @endif

                        @if (!empty($facebook_link))
                            <a target="_blank" href="{{ \App\Helpers\SettingHelper::getSetting('facebook_link') }}"
                                class="text-light pra-footer" style="text-decoration: none">
                                <img src="/theme/img/fb-icon.png" alt="" class="img-fluid" style="width: 24px; margin-left: 5px;" />
                            </a>
                        @endif
                        
                        @if (!empty($instagram_link))
                            <a target="_blank" href="{{ $instagram_link }}" class="text-light pra-footer"
                                style="text-decoration: none">
                                <img src="/theme/img/footer-instagram-icon.png" alt="" class="ps-1" style="width: 40px;margin-top:-5px;" />
                            </a>
                        @endif
                    </p>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-0 col-sm-0 col-xs-0 mb-4  mb-md-0 main-page-footer-section footer-section-product">
                    <h5 class="text-uppercase mb-4 contact-us-footer ">Customer Service</h5>
                    {{-- <p class="card-text  sm-4-footer ms-5 mb-1">
                        <a href="{{url('/page/blogs')}}" class="text-white">Blogs</a>
                    </p>
                    <p class="card-text  sm-4-footer ms-5 mb-1">
                        <a href="{{url('/page/terms')}}" class="text-white">Terms & Conditions</a>
                    </p>
                    <p class="card-text  sm-4-footer ms-5 mb-1">
                        <a href="{{url('/page/privacy-policy')}}" class="text-white">Privacy Policy</a>
                    </p>
                    <p class="card-text  sm-4-footer ms-5 mb-1">
                        <a href="{{url('/page/returns')}}" class="text-white">Return Policy</a>
                    </p>
                    <p class="card-text  sm-4-footer ms-5 mb-1">
                        <a href="{{url('/page/blogs')}}" class="text-white">Blogs</a>
                    </p> --}}
                    <p class="card-text  sm-4-footer  mb-2">
                        <a href="{{ url('contact-us') }}" class="text-white">Technical Support</a>
                    </p>
                    <p class="card-text  sm-4-footer  mb-2">
                        <a href="{{ url('contact-us') }}" class="text-white">Customer Support</a>
                    </p>
                    <p class="card-text  sm-4-footer  mb-2">
                        {{-- @php
                            $enable_wholesale_registration = App\Models\AdminSetting::where('option_name', 'enable_wholesale_registration')->first();
                        @endphp
                        @if (auth()->user())
                            @php
                            $wholesale_application_status = App\Models\WholesaleApplicationInformation::where('email' , Auth::user()->email)->first();
                            @endphp 
                            @if(strtolower($enable_wholesale_registration->option_value) == 'yes')
                                @if (!empty($wholesale_application_status) && ($wholesale_application_status->status == 0)) 
                                    <a href="{{route('create_wholesale_account')}}" class="text-white" title="Continue Wholesale Application" >Continue Wholesale Application</a>
                                @elseif (!empty($wholesale_application_status) && ($wholesale_application_status->status == 1))
                                    <a href="{{route('view_wholesale_account' , $wholesale_application_status->id)}}" class="text-white" title="View Wholesale Application" >View Wholesale Application</a>
                                @elseif (empty($wholesale_application_status))
                                    <a href="{{route('create_wholesale_account')}}" class="text-white" title="Apply for Wholesale Account" >Apply for Wholesale Account</a>
                                @endif
                            @endif
                        @else
                            @if(strtolower($enable_wholesale_registration->option_value) == 'yes')
                                <a href="{{route('create_wholesale_account')}}" class="text-white" >Apply for Wholesale Account</a>
                            @endif
                        @endif --}}
                        <a href="{{url('contact-us')}}" class="text-white" >Apply for Wholesale Account</a>
                    </p>
                    {{-- <p class="card-text  sm-4-footer ms-5">
                        
                        <a href="{{url('/page/hydro-guide-and-tips')}}" class="text-white">Hydro Guide and Tips</a>
                    </p> --}}
                </div>
                <div class="col-xl-3 col-lg-3 col-md-0 col-sm-0 col-xs-0 mb-4  mb-md-0 main-page-footer-section footer-section-product">
                    <h5 class="text-uppercase mb-4 contact-us-footer ">MY ACCOUNTS</h5>
                    <p class="card-text  sm-4-footer ">
                        <a href="{{route('my_account')}}" class="text-white">My Orders</a>
                    </p>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-0 col-sm-0 col-xs-0 mb-4  mb-md-0 main-page-footer-section footer-section-product">
                    <h5 class="text-uppercase mb-4 contact-us-footer ">Company</h5>
                    <p class="card-text  sm-4-footer  mb-2">
                        <a href="{{url('/page/blogs')}}" class="text-white">Blogs</a>
                    </p>
                    <p class="card-text  sm-4-footer  mb-2">
                        <a href="{{url('/page/terms')}}" class="text-white">Terms & Conditions</a>
                    </p>
                    <p class="card-text  sm-4-footer  mb-2">
                        <a href="{{url('/page/privacy-policy')}}" class="text-white">Privacy Policy</a>
                    </p>
                    <p class="card-text  sm-4-footer  mb-2">
                        <a href="{{url('/page/returns')}}" class="text-white">Return Policy</a>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 footer-end mt-5">
            <p class="pt-4">
                Copyright @ {{ \App\Helpers\SettingHelper::getSetting('website_name') }}. All right reserved
            </p>
        </div>
    </div>
</footer>

<footer class="text-white text-lg-start h-90 mobile-view mobile_footer_set" style="background: #212121">
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="main-page-footer-section footer-section-product mb-5">
                    <h5 class="foooter-main-head-mbl contact-us-footer mt-5 mb-4 text-uppercase">CONTACT US</h5>
                    <p><span>
                        <img src="/theme/img/map-pin.png" class="img-fluid">
                    </span>
                    <span class="p-footer-mbl ms-4-footer mb-2">{!! \App\Helpers\SettingHelper::getSetting('store_address_line_1') !!} {!! \App\Helpers\SettingHelper::getSetting('store_address_line_2') !!}</span></p>
                    <span>
                        <img src="/theme/img/phone-call.png" class="img-fluid">
                    </span>
                    <span class="footer_head_mbl pra-footer">{{ \App\Helpers\SettingHelper::getSetting('store_phone_number') }}</span>
                    <p class="p-footer-mbl ms-4-footer mx-1 ps-4"><span class="day-color-mbl">{!! \App\Helpers\SettingHelper::getSetting('timings_part1') !!}</span> {!! \App\Helpers\SettingHelper::getSetting('timings_part2') !!}</p>

                    <h5 class="foooter-main-head-mbl contact-us-footer mt-5 mb-4 text-uppercase">Customer Service</h5>
                    <p class="p-footer-mbl ms-4-footer mx-1"><a href="{{url('/contact-us')}}" class="text-white">Technical Support</a></p>
                    <p class="p-footer-mbl ms-4-footer mx-1"><a href="{{ url('contact-us') }}" class="text-white">Customer Support</a></p>
                    <p class="p-footer-mbl ms-4-footer mx-1"><a href="{{ url('contact-us') }}" class="text-white">Apply for Wholesale Account</a></p>
                    {{-- <p class="p-footer-mbl ms-4-footer mx-1"><a href="{{url('/page/hydro-guide-and-tips')}}" class="text-white">Hydro Guide and Tips</a></p> --}}

                    <h5 class="foooter-main-head-mbl contact-us-footer mt-5 mb-4 text-uppercase">MY ACCOUNT</h5>
                    <p class="p-footer-mbl ms-4-footer mx-1"><a href="{{route('my_account')}}" class="text-white">My orders</a></p>
                    

                    <h5 class="foooter-main-head-mbl contact-us-footer mt-5 mb-4 text-uppercase">Company</h5>
                    <p class="p-footer-mbl ms-4-footer mx-1"><a href="{{url('/page/blogs')}}" class="text-white">Blogs</a></p>
                    <p class="p-footer-mbl ms-4-footer mx-1">
                        <a href="{{url('/page/terms')}}" class="text-white">Terms & Conditions</a>
                    </p>
                    <p class="p-footer-mbl ms-4-footer mx-1">
                        <a href="{{url('/page/returns')}}" class="text-white">Return Policy</a>
                    </p>
                   
                    <p class="p-footer-mbl ms-4-footer mx-1">
                        <a href="{{url('/page/privacy-policy')}}" class="text-white">Privacy Policy</a>
                    </p>

                    <h5 class="foooter-main-head-mbl contact-us-footer mt-5 mb-4 text-uppercase">PAYMENT METHOD</h5>
                    <div class="d-flex w-100 justify-content-between">
                        <div class="w-25"><img class="img-fluid" style="" src="/theme/paymentsImg/paypal.png"></div>
                        <div class="w-25"><img class="img-fluid" style="" src="/theme/paymentsImg/discover.png"></div>
                        <div class="w-25"><img class="img-fluid" style="" src="/theme/paymentsImg/cm.png"></div>
                        <div class="w-25"><img class="img-fluid" style="" src="/theme/paymentsImg/visa.png"></div>
                        
                    </div>

                    <div class="d-flex mt-5 ">
                        <img class="img-fluid" src="/theme/paymentsImg/free-shipping.png" alt="">
                    </div>
                </div>
            </div>
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 footer-end ">
                <p class="">
                    Copyright @ {{ \App\Helpers\SettingHelper::getSetting('website_name') }}. All right reserved
                </p>
            </div>
        </div>
    </div>
</footer>

<footer class="text-white text-lg-start bg-dark h-900 mt-5 ipad-view">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="main-page-footer-section footer-section-product">
                    <h5 class="contact-us-footer mt-3 ps-3">CONTACT US</h5>
                    <span>
                        <img src="/theme/img/map-pin.png" class="img-fluid ps-3">
                    </span>
                    <span class="ms-2 pra-footer">{!! \App\Helpers\SettingHelper::getSetting('store_address_line_1') !!}
                        {!! \App\Helpers\SettingHelper::getSetting('store_address_line_2') !!}<br />
                    </span>
                    <span>
                        <img src="/theme/img/phone-call.png" class="img-fluid ps-3">
                    </span>
                    <span class="ms-2 pra-footer">{{ \App\Helpers\SettingHelper::getSetting('store_phone_number') }}
                    </span>
                    <p class="ms-4 ms-4-footer">&nbsp;
                        <span class="text-success sm-4-footer">
                            {!! \App\Helpers\SettingHelper::getSetting('timings_part1') !!}
                        </span> {!! \App\Helpers\SettingHelper::getSetting('timings_part2') !!}
                    </p>
                </div>
                <div class="col-md-12 mt-3 main-page-footer-section footer-section-product">
                    <div class="d-flex">
                        <div class="justify-content-center aling-items-center">
                            <h5 class="text-uppercase contact-us-footer mt-3">CUSTOMER SERVICE</h5>
                            <p class="card-text justify-content-end sm-4-footer"><a href="{{ url('/contact-us') }}" class="text-white">Technical Support</a></p>
                            <p class="card-text justify-content-end sm-4-footer"><a href="{{ url('/contact-us') }}" class="text-white">Customer Support</a></p>
                            <p class="card-text justify-content-end sm-4-footer"><a href="{{ url('/contact-us') }}" class="text-white">Apply for Wholesale Account</a></p>
                           
                            {{-- <p class="card-text justify-content-end sm-4-footer"><a href="{{url('/page/hydro-guide-and-tips')}}" class="text-white">Hydro Guide and Tips</a></p> --}}
                            <h5 class="text-uppercase  contact-us-footer mt-4">MY ACCOUNTS</h5>
                            <p class="card-text justify-content-end sm-4-footer"><a href="{{route('my_account')}}" class="text-white">My Orders</a></p>

                            <h5 class="text-uppercase  contact-us-footer mt-4">COMPANY</h5>
                            <p class="card-text justify-content-end sm-4-footer"><a href="{{url('/page/blogs')}}" class="text-white">Blogs</a></p>
                            <p class="card-text justify-content-end sm-4-footer">
                                <a href="{{url('/page/terms')}}" class="text-white">Terms & Conditions</a>
                            </p>
                            <p class="card-text justify-content-end sm-4-footer">
                                <a href="{{url('/page/privacy-policy')}}" class="text-white">Privacy Policy</a>
                            </p>
                            <p class="card-text justify-content-end sm-4-footer">
                                <a href="{{url('/page/returns')}}" class="text-white">Return Policy</a>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 main-page-footer-section footer-section-product mt-5">
                    <h5 class=" text-uppercase mb-4 contact-us-footer"> PAYMENT METHOD</h5>
                    <img class="img-fluid" src="/theme/img/paypal.png">&nbsp; &nbsp;&nbsp; &nbsp;
                    <img class="img-fluid" src="/theme/img/discover.png">&nbsp; &nbsp;&nbsp; &nbsp;
                    <img class="img-fluid" src="/theme/img/curus.png">&nbsp; &nbsp;&nbsp; &nbsp;
                    <img class="img-fluid" src="/theme/img/visa.png">&nbsp; &nbsp;&nbsp; &nbsp;
                </div>
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 footer-end ">
                    <p class="mt-4">
                        Copyright @ {{ \App\Helpers\SettingHelper::getSetting('website_name') }}. All right reserved
                    </p>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="last_button_clicked" value="" />
    <input type="hidden" id="body_flag" value="0" />
</footer>