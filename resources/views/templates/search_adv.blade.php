
<!-- daterange picker -->
<link rel="stylesheet" href="/admin-lte/plugins/daterangepicker/daterangepicker.css">

<div class="col-md-2">
    <div class="form-group">
        <div class="input-group">
            <button type="button" class="btn btn-default" id="daterange-btn">
                    <span>
                      <i class="fa fa-calendar"></i> Davr oraliq
                    </span>
                <i class="fa fa-caret-down"></i>
            </button>
        </div>
    </div>
</div>
<input name="s_start" id="s_start" value="" hidden>
<input name="s_end" id="s_end" value="" hidden>
<div class="col-md-2">
    <div class="form-group">
        <select name="f" id="search_f" class="form-control select2" style="width: 100%;">
            <option selected="selected" value="">@lang('blade.branch')</option>
            @foreach($filials as $key => $value)
                <option value="{{$value->branch_code}}">{{$value->branch_code}} - {{ $value->title }}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="col-md-2">
    <div class="form-group">
        <select name="u" id="search_u" class="form-control select2" style="width: 100%;">
            <option selected="selected" value="">@lang('blade.from_whom')</option>
            @foreach($users as $key => $value)
                <option value="{{$value->id??''}}">{{$value->lname??''}} {{$value->fname??''}}</option>
            @endforeach
        </select>
    </div>
</div>

<!-- date-range-picker -->
<script src="/admin-lte/plugins/daterangepicker/moment.min.js"></script>

<script src="/admin-lte/plugins/daterangepicker/daterangepicker.js"></script>
<!-- bootstrap datepicker -->
<script src="/admin-lte/plugins/datepicker/bootstrap-datepicker.js"></script>
<script>

    $(function () {
        //Initialize Select2 Elements
        $(".select2").select2();

        //Date range as a button
        $('#daterange-btn').daterangepicker(
            {
                ranges: {
                    'Bugun': [moment(), moment()],
                    'Kecha': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Ohirgi 7 kun': [moment().subtract(6, 'days'), moment()],
                    'Ohirgi 30 kun': [moment().subtract(29, 'days'), moment()],
                    'Bu oyda': [moment().startOf('month'), moment().endOf('month')],
                    'O`tgan oyda': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                startDate: moment().subtract(29, 'days'),
                endDate: moment()
            },
            function (start, end) {
                var s_start = start.format('YYYY-MM-DD');

                var s_end = end.format('YYYY-MM-DD');

                $('#s_start').val(s_start);
                $('#s_end').val(s_end);

                $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            }
        );

        //Date picker
        $('#datepicker').datepicker({
            autoclose: true
        });

    });

    $(".getFiles").click(function () {

        let id = $(this).val();

        $.ajax({
            url: '/fe/getBlade',
            type: 'GET',
            data: {id: id, type: 'files'},
            dataType: 'json',
            beforeSend: function(){
                $("#loading").show();
            },
            success: function(res){
                $('#blade_append').html(res.blade);
                $('#filesModal').modal('show');

            },
            complete:function(res){
                $("#loading").hide();
            }

        });

    });

</script>
