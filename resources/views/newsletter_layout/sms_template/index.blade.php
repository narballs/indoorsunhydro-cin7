
@extends('newsletter_layout.dashboard')
@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Sms Templates List</h3>
                    <a href="{{ route('create_sms_templates') }}" class="btn btn-primary float-right">Create New</a>
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
                                <td>{{!empty($template->sent_sms[0]) &&  (!empty($template->sent_sms[0]->sent_date))  ?  $template->sent_sms[0]->sent_date : ''  }}</td>
                                <td>
                                    <div class="d-flex">
                                        <form action="{{ route('delete_sms_templates', $template->id) }}" method="POST" style="display: inline-block;">
                                            <a href="{{ route('sms_detail', $template->id) }}" class="btn btn-info">Preview</a>
                                            @if (empty($template->sent_sms[0]) || ($template->sent_sms[0]->sent == 0))
                                                <a href="{{ route('edit_sms_templates', $template->id) }}" class="btn btn-primary">Edit</a>
                                            @endif
                                            @csrf
                                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this Template?');">Delete</button>
                                        </form>
                                        @if (count($template->sent_sms) > 0)
                                            <form action="{{ route('sms_template_duplicate', $template->id) }}" method="POST" class="mx-1">
                                                @csrf
                                                <input type="hidden" name="subscriber_list_id" id="" value="{{ count($template->sent_sms) > 0 ? $template->sent_sms[0]->mobile_number_list->id : '' }}">
                                                <button type="submit" class="btn btn-default" onclick="return confirm('Are you sure you want to duplicate this Template?');">Duplicate</button>
                                              
                                            </form>
                                        @endif
                                        @if (!empty($template->sent_sms[0]))
                                            @if ($template->sent_sms[0]->sent == 0)
                                                <form action="{{ route('send_sms', $template->sent_sms[0]->id) }}" method="POST" class="mx-2" style="display: inline-block;">
                                                    @csrf
                                                <input type="hidden" name="mobile_number_list_id" value="{{ $template->sent_sms[0]->mobile_number_list->id }}">
                                                <input type="hidden" name="sms_template_id" value="{{ $template->sent_sms[0]->sms_template->id }}">
                                                <button type="submit" class="btn btn-secondary">Send Now</button>
                                            </form>
                                            @else 
                                                <button type="button" class="btn btn-success mx-2" title="Completed">Completed</button>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="3">No Sms Template list Found</td>
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