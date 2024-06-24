@extends('newsletter_layout.dashboard')
@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Subscribers</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example2" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Email</th>

                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $i =1;
                        @endphp
                        @if(count($newsletter_subscriptions) > 0 )
                        @foreach ($newsletter_subscriptions as $newsletter_subscription)
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>{{ $newsletter_subscription->email }}</td>

                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="3">No Subscribers Found</td>
                        </tr>
                        @endif
                    </tbody>
                </table>

                <div class="row">
                    <div class="col-md-12">
                        {{ $newsletter_subscriptions->links() }}
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.col -->
</div>
@endsection