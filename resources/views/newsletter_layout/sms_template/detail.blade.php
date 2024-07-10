@extends('newsletter_layout.dashboard')
@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Sms Template Detail</h3>
                <a href="{{route('newsletter-templates.index')}}" class="btn btn-primary float-right">Back</a>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <h5>
                    <strong>Subject:</strong> {{!empty($smsTemplate->name) ?   $smsTemplate->name  : ''}}
                </h5>

                <div class="mt-4">
                    @if (!empty($smsTemplate->description))
                        {{-- {!! $smsTemplate->description !!} --}}
                        {!! $smsTemplate->description !!}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection