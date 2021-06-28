<div id="myModal" class="modal fade">
    <div class="modal-dialog modal-confirm">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title"><i class="fa fa-user text-green"></i>
                    {{ $model->user->lname??''}} {{ $model->user->fname??'' }}
                    <i class="fa fa-reply"> @lang('blade.reply')</i>
                </h4>
            </div>

            <form role="form" method="POST" action="{{ route('post-fe-forward') }}" enctype="multipart/form-data">
                {{csrf_field()}}

                <div class="modal-body">

                    <input name="status" value="reply" hidden />

                    <input name="message_id" value="{{ $model->id }}" hidden />

                    <input name="to_users[]" value="{{$model->user_id}}" hidden />

                    <div class="form-group">
                        <label>@lang('blade.subject') <span class="text-red">*</span></label>

                        <input type="text" name="subject" class="form-control" placeholder="@lang('blade.subject')" required>

                    </div>

                    <div class="form-group">
                        <label>@lang('blade.text'):</label>
                        <textarea name="text" id="editor1" rows="14" cols="110">
                                        <br>
                                        <br>
                                        <i class="text-muted">
                                        @lang('blade.with_respect'),
                                        <br>{{ Auth::user()->fname??'' }} {{ Auth::user()->lname??'' }}
                                        </i>
                                    </textarea>

                    </div>

                    <div class="form-group">
                        <strong>@lang('blade.upload_file'):</strong>
                        <div class="input-group control-group increment">
                            <input type="file" name="mes_files[]" class="form-control" multiple>
                            <div class="input-group-btn">
                                <button class="btn btn-success" type="button">
                                    <i class="glyphicon glyphicon-paperclip"></i>
                                </button>
                            </div>
                        </div>
                        <div class="clone hide">
                            <div class="control-group input-group" style="margin-top:10px">
                                <input type="file" name="mes_files[]" class="form-control" multiple>
                                <div class="input-group-btn">
                                    <button class="btn btn-danger" type="button">
                                        <i class="glyphicon glyphicon-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <div class="pull-right">
                        <a href="" class="btn btn-default" data-dismiss="modal"><i class="fa fa-remove"></i> @lang('blade.cancel')
                        </a>

                        <button type="submit" class="btn btn-primary"><i class="fa fa-envelope-o"></i>
                            @lang('blade.send')
                        </button>
                    </div>
                </div>

            </form>

        </div>
    </div>
</div>

<!-- ck editor -->
<script src="{{ asset ("/ckeditor/ckeditor.js") }}"></script>

<script src="{{ asset ("/ckeditor/samples/js/sample.js") }}"></script>

<script type="text/javascript">

    CKEDITOR.replace("editor1");

</script>
