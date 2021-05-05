<div class="row">
    <div class="col-md-12">
        <div class="form-group has-inputError">
            <label class="control-label text-red"><i class="fa fa-search text-red"></i> @lang('blade.search_users')</label>
            <select class="form-control select2" name="to_users[]" multiple="multiple" data-placeholder="@lang('blade.search_users') ... " style="width: 100%;">

                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->lname.' '.$user->fname }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<script>
    $(function () {

        $(".select2").select2();

    });
</script>
