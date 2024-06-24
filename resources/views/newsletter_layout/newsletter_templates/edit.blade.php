
@extends('newsletter_layout.dashboard')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Edit Newsletter Template</h3>
                <a href="{{route('newsletter-templates.index')}}" class="btn btn-primary float-right">Back</a>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <form action="{{ route('update_newsletter_template' , $newsletterTemplate->id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" value="{{!empty($newsletterTemplate->name) ? $newsletterTemplate->name : ''}}" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="content">Content:</label>
                        <textarea id="newsletter_content_edit" name="content" class="form-control">
                            {{!empty($newsletterTemplate->content) ? $newsletterTemplate->content : ''}}
                        </textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.col -->
</div>

<script src="https://cdn.ckeditor.com/ckeditor5/35.3.0/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create(document.querySelector('#newsletter_content_edit'), {
            toolbar: {
                items: [
                    'heading', '|',
                    'bold', 'italic', 'underline', 'strikethrough', 'blockQuote', 'link', '|',
                    'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', '|',
                    'bulletedList', 'numberedList', 'todoList', '|',
                    'alignment', 'outdent', 'indent', '|',
                    'imageUpload', 'mediaEmbed', 'insertTable', 'horizontalLine', 'pageBreak', '|',
                    'undo', 'redo'
                ]
            },
            heading: {
                options: [
                    { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                    { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                    { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                    { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' },
                    { model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4' },
                    { model: 'heading5', view: 'h5', title: 'Heading 5', class: 'ck-heading_heading5' },
                    { model: 'heading6', view: 'h6', title: 'Heading 6', class: 'ck-heading_heading6' }
                ]
            },
            ckfinder: {
                uploadUrl: '{{ route("upload_newsletterImage", ["_token" => csrf_token()]) }}',
            },
            table: {
                contentToolbar: [
                    'tableColumn', 'tableRow', 'mergeTableCells', 'tableCellProperties', 'tableProperties'
                ]
            },
            mediaEmbed: {
                previewsInData: true
            },
        })
        .catch(error => {
            console.error(error);
        });
</script>
@endsection