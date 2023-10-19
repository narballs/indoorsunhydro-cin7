@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<div class="row ">
    <div class="col-md-6">
        <h1>Edit Blog</h1>
    </div>
    <div class="col-md-6 text-right">
        <a class="btn btn-primary text-white" href="{{ route('blogs.index') }}">Back</a>
    </div>
</div>
@stop

@section('content')
    @if (count($errors) > 0)
    <div class="alert alert-danger alert-dismissible">
        <a href="#" class="close text-white" data-dismiss="alert" aria-label="close">&times;</a>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <div>
        <form method="Post" action="{{ route('blogs.update' , $blog->id) }}"  enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-md-12 card">
                    <div class="card-body">
                        <div class="row justify-conntent-between">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Title</label>
                                    <input type="text" class="form-control" id="title" aria-describedby=""
                                    name="title" value="{{$blog->title}}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name"> Image</label>
                                    <input type="file" class="form-control" id="blog_image" aria-describedby="" name="blog_image" accept="jpg,png,jpeg">
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group" id="">
                                    <label><strong>Description :</strong></label>
                                    <textarea class="form-control" name="description" id="blog_description"> {{$blog->description}}</textarea>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        
                                        <option value="1" {{$blog->status == 1 ? 'selected' : ''}}>Active</option>
                                        <option value="0" {{$blog->status == 0 ? 'selected' : ''}}>Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    @stop

    @section('css')
        <link rel="stylesheet" href="/theme/css/admin_custom.css">
        <link rel="stylesheet" href="{{ asset('admin/admin_lte.css') }}">
        <style>
            .text-successs {
                color: #7CC633 !important;
                font-family: 'Poppins', sans-serif !important;
            }

            .badge-success {
                color: #fff;
                background: rgb(186 235 137 / 20%);
                color: #319701;
                padding: 6px !important;
                font-style: normal;
                font-weight: 500;
                font-size: 11.3289px;

            }

            .badge-warning {
                background-color: #f1e8cb;
                color: #b58903 !important;
                padding: 6px !important;
                font-style: normal;
                font-weight: 500;
                font-size: 11.3289px;
            }

            .badge-danger {
                color: #fff;
                background-color: #f1eaea;
                color: #B42318;
                padding: 6px !important;
                font-style: normal;
                font-weight: 500;
                font-size: 11.3289px;

            }

            .badge-secondary {
                color: #8e8b8b !important;
                background-color: #d0dce6 !important;
                padding: 7px !important;
                border-radius: 6px;
            }

            .badge-primary {
                background-color: #339AC6;
                color: #339AC6 !important;
                padding: 5px;
            }

            /* css for switch */
            .switch {
                position: relative;
                display: inline-block;
                vertical-align: top;
                width: 70px;
                height: 30px;
                margin: 0 10px 10px 0;
                background: linear-gradient(to bottom, #eeeeee, #FFFFFF 25px);
                background-image: -webkit-linear-gradient(top, #eeeeee, #FFFFFF 25px);
                border-radius: 18px;
                box-shadow: inset 0 -1px white, inset 0 1px 1px rgba(0, 0, 0, 0.05);
                cursor: pointer;
                box-sizing: content-box;
            }
            label {
                font-weight: inherit;
            }
            input[type=checkbox], input[type=radio] {
                margin: 4px 0 0;

                line-height: normal;
                -webkit-box-sizing: border-box;
                -moz-box-sizing: border-box;
                box-sizing: border-box;
                padding: 0;
            }


            .switch-input {
                position: absolute;
                top: 0;
                left: 0;
                opacity: 0;
                box-sizing: content-box;
            }
            .switch-left-right .switch-input:checked ~ .switch-label {
                background: inherit;
            }
            .switch-input:checked ~ .switch-label {
                background: #E1B42B;
                box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.15), inset 0 0 3px rgba(0, 0, 0, 0.2);
            }
            .switch-left-right .switch-label {
                overflow: hidden;
            }
            .switch-label, .switch-handle {
                transition: All 0.3s ease;
                -webkit-transition: All 0.3s ease;
                -moz-transition: All 0.3s ease;
                -o-transition: All 0.3s ease;
            }
            .switch-label {
                position: relative;
                display: block;
                height: inherit;
                font-size: 15px;
                text-transform: uppercase;
                background: #eceeef;
                border-radius: inherit;
                box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.12), inset 0 0 2px rgba(0, 0, 0, 0.15);
                box-sizing: content-box;
            }
            .switch-left-right .switch-input:checked ~ .switch-label:before {
                opacity: 1;
                left: 100px;
            }
            .switch-input:checked ~ .switch-label:before {
                opacity: 0;
            }
            .switch-left-right .switch-label:before {
                background: #eceeef;
                text-align: left;
                padding-left: 48px!important;
            }
            .switch-left-right .switch-label:before, .switch-left-right .switch-label:after {
                width: 23px;
                height: 25px;
                top: 1px;
                left: 0;
                right: 0;
                bottom: 0;
                padding: 11px 0 0 0;
                text-indent: -12px;
                border-radius: 20px;
                box-shadow: inset 0 1px 4px rgba(0, 0, 0, 0.2), inset 0 0 3px rgba(0, 0, 0, 0.1);
            }
            .switch-label:before {
                content: attr(data-off);
                right: 11px;
                color: #aaaaaa;
                text-shadow: 0 1px rgba(255, 255, 255, 0.5);
            }

            span.switch-label:after {
                content: attr(data-on);
                left: 11px;
                color: #FFFFFF;
                text-shadow: 0 1px rgba(0, 0, 0, 0.2);
                position: absolute;
            
            }

            .switch-label:before, .switch-label:after {
                position: absolute;
                top: 50%;
                margin-top: -5px;
                line-height: 1;
                -webkit-transition: inherit;
                -moz-transition: inherit;
                -o-transition: inherit;
                transition: inherit;
                box-sizing: content-box;
            }

            .switch-left-right .switch-input:checked ~ .switch-label:after {
                left: 0!important;
                opacity: 1;
                padding-left: 20px;
            }


            .switch-input:checked ~ .switch-label:after {
                opacity: 1;
            }


            .switch-left-right .switch-label:after {
                text-align: left;
                text-indent: 9px;
                background: #28a745!important;
                left: -100px!important;
                opacity: 1;
                width: 100%!important;
            
            }
            .switch-left-right .switch-label:before, .switch-left-right .switch-label:after {
                width: 23px;
                height: 25px;
                top: 1px;
                left: 0;
                right: 0;
                bottom: 0;
                padding: 11px 0 0 0;
                text-indent: -12px;
                border-radius: 20px;
                box-shadow: inset 0 1px 4px rgba(0, 0, 0, 0.2), inset 0 0 3px rgba(0, 0, 0, 0.1);
            }
            .switch-input:checked ~ .switch-handle {
                left: 40px;
                box-shadow: -1px 1px 5px rgba(0, 0, 0, 0.2);
            }
            .switch-label, .switch-handle {
                transition: All 0.3s ease;
                -webkit-transition: All 0.3s ease;
                -moz-transition: All 0.3s ease;
                -o-transition: All 0.3s ease;
            }

            .switch-handle {
                position: absolute;
                top: 1px;
                left: 4px;
                width: 28px;
                height: 28px;
                background: linear-gradient(to bottom, #FFFFFF 40%, #f0f0f0);
                background-image: -webkit-linear-gradient(top, #FFFFFF 40%, #f0f0f0);
                border-radius: 100%;
                box-shadow: 1px 1px 5px rgba(0, 0, 0, 0.2);
            }

            .switch-handle:before {
                content: "";
                position: absolute;
                top: 50%;
                left: 50%;
                margin: -6px 0 0 -6px;
                width: 12px;
                height: 12px;
                background: linear-gradient(to bottom, #eeeeee, #FFFFFF);
                background-image: -webkit-linear-gradient(top, #eeeeee, #FFFFFF);
                border-radius: 6px;
                box-shadow: inset 0 1px rgba(0, 0, 0, 0.02);
            }
        </style>
    @stop

    @section('js')
    {{-- <script src="{{asset('admin/ck_editor.js')}}"></script> --}}
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.2/super-build/ckeditor.js"></script>
    <script>
        class MyUploadAdapter {
            constructor( loader ) {
                this.loader = loader;
            }
        
            upload() {
                return this.loader.file
                    .then( file => new Promise( ( resolve, reject ) => {
                        this._initRequest();
                        this._initListeners( resolve, reject, file );
                        this._sendRequest( file );
                    } ) );
            }
        
            abort() {
                if ( this.xhr ) {
                    this.xhr.abort();
                }
            }
        
            _initRequest() {
                const xhr = this.xhr = new XMLHttpRequest();
        
                xhr.open( 'POST', "{{route('image_upload', ['_token' => csrf_token() ])}}", true );
                xhr.responseType = 'json';
            }
        
            _initListeners( resolve, reject, file ) {
                const xhr = this.xhr;
                const loader = this.loader;
                const genericErrorText = `Couldn't upload file: ${ file.name }.`;
        
                xhr.addEventListener( 'error', () => reject( genericErrorText ) );
                xhr.addEventListener( 'abort', () => reject() );
                xhr.addEventListener( 'load', () => {
                    const response = xhr.response;
        
                    if ( !response || response.error ) {
                        return reject( response && response.error ? response.error.message : genericErrorText );
                    }
        
                    resolve( response );
                } );
        
                if ( xhr.upload ) {
                    xhr.upload.addEventListener( 'progress', evt => {
                        if ( evt.lengthComputable ) {
                            loader.uploadTotal = evt.total;
                            loader.uploaded = evt.loaded;
                        }
                    } );
                }
            }
        
            _sendRequest( file ) {
                const data = new FormData();
        
                data.append( 'upload', file );
        
                this.xhr.send( data );
            }
        }
        
        function MyCustomUploadAdapterPlugin( editor ) {
            editor.plugins.get( 'FileRepository' ).createUploadAdapter = ( loader ) => {
                return new MyUploadAdapter( loader );
            };
        }
        
    </script>
    <script>
        CKEDITOR.ClassicEditor.create(document.getElementById("blog_description"), {
            toolbar: {
                items: [
                    'exportPDF','exportWord', '|',
                    'findAndReplace', 'selectAll', '|',
                    'heading', '|',
                    'bold', 'italic', 'strikethrough', 'underline', 'code', 'subscript', 'superscript', 'removeFormat', '|',
                    'bulletedList', 'numberedList', 'todoList', '|',
                    'outdent', 'indent', '|',
                    'undo', 'redo',
                    '-',
                    'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', 'highlight', '|',
                    'alignment', '|',
                    'link', 'insertImage', 'blockQuote', 'insertTable', 'mediaEmbed', 'codeBlock', 'htmlEmbed', '|',
                    'specialCharacters', 'horizontalLine', 'pageBreak', '|',
                    'textPartLanguage', '|',
                    'sourceEditing'
                ],
                shouldNotGroupWhenFull: true
            },
            fontFamily: {
                options: [
                    'default',
                    'Arial, Helvetica, sans-serif',
                    'Courier New, Courier, monospace',
                    'Georgia, serif',
                    'Lucida Sans Unicode, Lucida Grande, sans-serif',
                    'Tahoma, Geneva, sans-serif',
                    'Times New Roman, Times, serif',
                    'Trebuchet MS, Helvetica, sans-serif',
                    'Verdana, Geneva, sans-serif'
                ],
                supportAllValues: true
            },
            fontSize: {
                options: [ 10, 12, 14, 'default', 18, 20, 22 ],
                supportAllValues: true
            },
            htmlSupport: {
                allow: [
                    {
                        name: /.*/,
                        attributes: true,
                        classes: true,
                        styles: true
                    }
                ]
            },
            htmlEmbed: {
                showPreviews: true
            },
            
            removePlugins: [
                'ExportPdf',
                'ExportWord',
                'CKBox',
                'CKFinder',
                'EasyImage',
                'RealTimeCollaborativeComments',
                'RealTimeCollaborativeTrackChanges',
                'RealTimeCollaborativeRevisionHistory',
                'PresenceList',
                'Comments',
                'TrackChanges',
                'TrackChangesData',
                'RevisionHistory',
                'Pagination',
                'WProofreader',
                'MathType',
                'SlashCommand',
                'Template',
                'DocumentOutline',
                'FormatPainter',
                'TableOfContents',
                'PasteFromOfficeEnhanced'
            ]
        });
    </script>
    @stop
