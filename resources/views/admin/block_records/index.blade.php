@extends('adminlte::page')
@section('title', 'Blocked IPs')

@section('content_header')
    <h1>Blocked IP Addresses</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-8 mt-5">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Manage Blocked IPs</h3>
            </div>
            <div class="card-body">

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('update_block_records') }}">
                    @csrf

                    <label>IP Addresses (comma-separated)</label>
                    <input id="ip-tags"
                           name="ip_address"
                           class="form-control"
                           value="{{ old('ip_address', $block_records->pluck('ip_address')->implode(', ')) }}">

                    <button type="submit" class="btn btn-primary mt-3">Save</button>
                </form>

                {{-- @if($block_records->count())
                    <hr>
                    <h5>Currently Blocked IPs:</h5>
                    <ul>
                        @foreach($block_records as $record)
                            <li>{{ $record->ip_address }}</li>
                        @endforeach
                    </ul>
                @endif --}}

            </div>
        </div>
    </div>
</div>
@stop

@section('css')
    <link rel="stylesheet" href="/theme/css/admin_custom.css">
    <link rel="stylesheet" href="{{ asset('admin/admin_lte.css') }}">
    <!-- Tagify CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css">
@stop

@section('js')
    <!-- Tagify JS -->
    <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ipInput = document.querySelector('#ip-tags');
            new Tagify(ipInput, {
                delimiters: ",",
                pattern: /^[0-9]{1,3}(\.[0-9]{1,3}){3}$/, // simple IPv4 check
                dropdown: { enabled: 0 }
            });
        });
    </script>
@stop
