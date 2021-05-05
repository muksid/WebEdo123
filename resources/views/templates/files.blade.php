<div id="filesModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title"><i class="fa fa-file-image-o text-green"></i> @lang('blade.uploaded_files') {{ $files->count() }}</h4>
            </div>

            <div class="modal-body">
                <div class="box-comment">
                    @foreach($files as $key => $file)
                    <span class="pull-left">{{ $key = $key+1 }}. </span>

                    <div class="comment-text">
                      <span class="username">
                        {{ $file->file_name }}
                        <span class="text-muted pull-right">{{ $file->size($file->file_size) }}</span>
                      </span>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="modal-footer">
                <a class="btn btn-default" data-dismiss="modal">@lang('blade.close')</a>
            </div>

        </div>
    </div>
</div>
