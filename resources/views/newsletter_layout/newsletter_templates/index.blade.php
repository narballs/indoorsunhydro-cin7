
@extends('newsletter_layout.dashboard')
@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Newsletter Templates List</h3>
                    <a href="{{ route('newsletter-templates.create') }}" class="btn btn-primary float-right">Create New</a>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                {{ session('error') }}
                        </div>
                    @endif
                    <table id="example2" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Name</th>
                                <th>Created at</th>
                                <th>Sent date</th>
                                <th>Associated List</th>
                                <th>
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $i =1;
                            @endphp
                            @if(count($templates) > 0 )
                            @foreach ($templates as $template)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $template->name }}</td>
                                <td>{{ $template->created_at }}</td>
                                <td>{{!empty($template->sent_newsletter[0]) &&  (!empty($template->sent_newsletter[0]->sent_date))  ?  $template->sent_newsletter[0]->sent_date : ''  }}</td>
                                <td>
                                    {{-- @if (!empty($template->sent_newsletter[0]) &&  (empty($template->sent_newsletter[0]->subscriber_email_list))) --}}
                                    @if (!empty($template->sent_newsletter) && $template->sent_newsletter[0]->subscriber_email_list->name)
                                        {{ $template->sent_newsletter[0]->subscriber_email_list->name }}
                                        @if (($template->sent_newsletter[0]->subscriber_email_list->subscriberEmailList->count() == 0))
                                            <span class="text-muted" style="font-size: 14px;">(Subscribers Missing)</span>
                                            <a class="btn btn-sm btn-success ml-2" href="{{url('subscribers/list/index')}}">Add</a>
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <form action="{{ route('delete_newsletter_template', $template->id) }}" method="POST" style="display: inline-block;">
                                            <a href="{{ route('newsletter_templates_detail', $template->id) }}" class="btn btn-info">Preview</a>
                                            @if (empty($template->sent_newsletter[0]) || ($template->sent_newsletter[0]->sent == 0))
                                                <a href="{{ route('edit_newsletter_template', $template->id) }}" class="btn btn-primary">Edit</a>
                                            @endif
                                            @csrf
                                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this Template?');">Delete</button>
                                        </form>
                                        <form action="{{ route('duplicate_newsletter_template', $template->id) }}" method="POST" class="mx-1">
                                            @csrf
                                            <input type="hidden" name="subscriber_list_id" id="" value="{{ count($template->sent_newsletter) > 0 ? $template->sent_newsletter[0]->subscriber_email_list->id : '' }}">
                                            <button type="submit" class="btn btn-default" onclick="return confirm('Are you sure you want to duplicate this Template?');">Duplicate</button>
                                            
                                        </form>
                                        @if (!empty($template->sent_newsletter[0]))
                                            @if ($template->sent_newsletter[0]->sent == 0)
                                                <form action="{{ route('send_newspaper', $template->sent_newsletter[0]->id) }}" method="POST" style="display: inline-block;">
                                                    @csrf
                                                <input type="hidden" name="subscriber_email_list_id" value="{{ $template->sent_newsletter[0]->subscriber_email_list->id }}">
                                                <input type="hidden" name="template_id" value="{{ $template->id }}">
                                                <button type="submit" class="btn btn-secondary">Send Now</button>
                                            </form>
                                            @else 
                                                <button type="button" class="btn btn-success" title="Completed">Completed</button>
                                            @endif
                                        @else 
                                            <button type="button" class="btn btn-secondary" title="Send Now">List Missing</button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="3">No Newsletter Template list Found</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>

                    <div class="row">
                        <div class="col-md-12">
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