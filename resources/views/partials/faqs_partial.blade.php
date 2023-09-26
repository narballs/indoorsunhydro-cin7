<div class="row text-center faq-page-div">
    <h3>
        Frequently asked questions
    </h3>
    <p class="text-muted">
        Everything you need to know about the Indoorsun Hydro.
    </p>
</div>
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="accordion" id="accordionExample">
            @php
                $faqs = NavHelper::getFaqs();
            @endphp
            @if (count($faqs) > 0)
            @foreach($faqs as $faq)
            <div class="accordion-item">
                <h6 class="accordion-header" id="head{{$faq->id}}">
                    <div class="row">
                        <div class="col-md-10 d-flex align-items-center question_div_faq">
                            <div>
                                <h6 class="ml-2 mb-0">
                                    {{$faq->question}}
                                </h6>
                            </div>
                        </div>
                        <div class="col-md-2 button_div_faq" id="button_div{{$faq->id}}">
                            <button class="accordion-button text-success plus_minus_faqs d-block text-right p-3" onclick="show_answer('{{$faq->id}}')" type="button" data-bs-toggle="collapse" data-bs-target="#body{{$faq->id}}" aria-expanded="true" aria-controls="body{{$faq->id}}">
                                <i class="font-size-icon fa fa-plus-circle"></i>
                            </button>
                        </div>
                    </div>
                </h6>
                <div id="body{{$faq->id}}" class="accordion-collapse collapse" aria-labelledby="head{{$faq->id}}" data-bs-parent="#accordionExample">
                    <div class="accordion-body pt-0">
                        {!! $faq->answer !!}
                    </div>
                </div>
            </div>
            @endforeach
            @else
            <div class="row">
                <div class="col-md-12">
                    <h6 class="text-center">
                        No faqs found
                    </h6>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    .accordion-button:not(.collapsed)::after {
        background-image: none;

    }
    .accordion-button::after {
        background-image: none;

    }
    .accordion-button:not(.collapsed) {
        background-color: white;
        color: #212529;
        box-shadow:none !important;
    }
    .accordion-button:focus {
        box-shadow: none !important;
        border-color: none !important;
    }
    .font-size-icon {
        font-size: 20px !important;
    }
    
</style>
<script>
    function show_answer(id) {
        $('#button_div'+id).html('');
        $('#button_div'+id).html('<button class="accordion-button text-success plus_minus_faqs d-block text-right p-3" onclick="hide_answer('+id+')" type="button" data-bs-toggle="collapse" data-bs-target="#body'+id+'" aria-expanded="true" aria-controls="body'+id+'"><i class="font-size-icon fa fa-minus-circle"></i></button>');
    }
    function hide_answer(id) {
        $('#button_div'+id).html('');
        $('#button_div'+id).html('<button class="accordion-button text-success plus_minus_faqs d-block text-right p-3" onclick="show_answer('+id+')" type="button" data-bs-toggle="collapse" data-bs-target="#body'+id+'" aria-expanded="true" aria-controls="body'+id+'"><i class="font-size-icon fa fa-plus-circle"></i></button>');
    }
</script>