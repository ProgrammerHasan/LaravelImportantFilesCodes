@push('style')
    <link rel="stylesheet" href="/dropzone-master/dist/dropzone.css">
    <style>
        .dropzone {
            border: 1px solid rgb(233 236 239);
            border-radius: 8px;
        }
    </style>
@endpush
<div class="card-body">
    <form  id="documentsSaveForm" action="" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-row">

            <div class="col-md-12 mb-3">
                <label for="document-dropzone">Documents</label>
                <div class="needsclick dropzone" id="document-dropzone"></div>
            </div>

        </div>
        <button type="submit" class="btn btn-primary" id="saveDocumentsBtn"><i class="ti-save"></i> Save</button>
    </form>

</div>

@push('script')
    <script src="/dropzone-master/dist/dropzone.js"></script>
    <script>
        var uploadedDocumentMap = {}
        var formId = '#documentsSaveForm';
        Dropzone.options.documentDropzone = {
            url: '{{ route('document.store') }}',
            maxFilesize: 100, // MB
            addRemoveLinks: true,
            dictDefaultMessage : '<div class="drag-area"> <div class="icon" style="font-size: 35px;"><i class="fas fa-cloud-upload-alt"></i></div> <header>Drag & Drop to Upload File</header> <span>OR</span> Browse File',
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            success: function (file, response) {
                $(formId).append('<input type="hidden" name="documents[]" value="' + response.name + '">')
                uploadedDocumentMap[file.name] = response.name
            },
            removedfile: function (file) {
                file.previewElement.remove()
                var name = ''
                if (typeof file.file_name !== 'undefined') {
                    name = file.file_name
                } else {
                    name = uploadedDocumentMap[file.name]
                }
                $.ajax({
                    headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}"},
                    method: "POST",
                    url: '{{ route('document.store.remove') }}',
                    data: { file_uri:name}
                }).done(function( msg ) {
                    toastr.success("Document removed");
                });
                $(formId).find('input[name="documents[]"][value="' + name + '"]').remove()
            },
            init: function () {
                var myDropzone = this;
                $("#saveDocumentsBtn").click(function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    if (myDropzone.files.length=='0') {
                        toastr.warning("Please upload documents");
                        myDropzone.processQueue(); // upload files and submit the form
                    }else if($('#document_type_id').val()==''){
                        toastr.warning("Please select document type");
                        myDropzone.processQueue();
                    } else {
                        $(formId).submit(); // submit the form
                    }
                });
                @if(isset($project) && $project->document)
                var files =
                {!! json_encode($project->document) !!}
                    for (var i in files) {
                    var file = files[i]
                    this.options.addedfile.call(this, file)
                    file.previewElement.classList.add('dz-complete')
                    $(formId).append('<input type="hidden" name="documents[]" value="' + file.file_name + '">')
                }
                @endif
            }
        }
    </script>
    {{--    <script>--}}
    {{--        Dropzone.options.dropzone =--}}
    {{--            {--}}
    {{--                maxFilesize: 10,--}}
    {{--                renameFile: function (file) {--}}
    {{--                    var dt = new Date();--}}
    {{--                    var time = dt.getTime();--}}
    {{--                    return time + file.name;--}}
    {{--                },--}}
    {{--                acceptedFiles: ".jpeg,.jpg,.png,.gif",--}}
    {{--                addRemoveLinks: true,--}}
    {{--                timeout: 60000,--}}
    {{--                success: function (file, response) {--}}
    {{--                    console.log(response);--}}
    {{--                },--}}
    {{--                error: function (file, response) {--}}
    {{--                    return false;--}}
    {{--                }--}}
    {{--            };--}}
    {{--    </script>--}}
@endpush
