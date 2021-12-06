<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('library/calendar-hello-week/hello-week/dist/css/hello.week.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('library/calendar-hello-week/hello-week/dist/css/hello.week.theme.min.css') }}" rel="stylesheet" />

    <title>{{ config('app.name') }}</title>
</head>
<body>
<div class="container-sm">
    <div class="row">
        <div class="col-md-12">
            <br>
            <h1 class="text-center">TO DO LIST</h1>
            <div class="message-box"></div>
            <form id="create-task" action="{{ route('create.task') }}" method="post">
                @csrf
                <input type="hidden" name="target_date">
                <div class="input-group mb-3">
                    <textarea name="task" class="form-control" placeholder="Notunuz..."></textarea>
                    <div class="input-group-append">
                        <span class="input-group-text" id="basic-addon2">
                            <div class="cursor-pointer margin-right-15  custom-calendar">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-calendar-week" viewBox="0 0 16 16">
                                    <path d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm-3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm-5 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1z"/>
                                    <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                                </svg>
                                <span class="custom-calendar-date font-weight-bold">Takvim</span>
                            </div>
                            <button id="save" type="submit" class="btn btn-success">Ekle</button>
                        </span>
                    </div>
                    <div class="hello-week hello-week-form display-none">
                        <div class="navigation">
                            <span class="prev">
                                <i class="fas fa-caret-left"></i>
                            </span>
                            <div class="period"></div>
                            <span class="next">
                                <i class="fas fa-caret-right"></i>
                            </span>
                        </div>
                        <div class="week"></div>
                        <div class="month"></div>
                    </div>
                </div>
            </form>

            <div class="table-container">
                @include('table')
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="taskEditModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Görevi Güncelle</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('update.task') }}" method="post">
                    @csrf
                    <div class="form-group">
                        <input type="hidden" name="modal_task_id">
                        <input class="form-control" type="text" name="modal_task_date" value="" />
                        <div class="hello-week hello-week-modal display-none">
                            <div class="navigation">
                            <span class="prev">
                                <i class="fas fa-caret-left"></i>
                            </span>
                                <div class="period"></div>
                                <span class="next">
                                <i class="fas fa-caret-right"></i>
                            </span>
                            </div>
                            <div class="week"></div>
                            <div class="month"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <textarea rows="8" name="modal_task" class="form-control" name="task" placeholder=""></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">KAPAT</button>
                <button type="button" class="btn btn-primary update-task">GÜNCELLE</button>
            </div>
        </div>
    </div>
</div>
</body>
<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/custom.js') }}"></script>
<script type="text/javascript" src="{{ asset('library/calendar-hello-week/hello-week/dist/hello.week.min.js') }}"></script>
<script>
    function refreshTable() {
        $('div.table-container').fadeOut(100);
        $('div.table-container').load( '{{ route('task.table') }}', function() {
            $('div.table-container').fadeIn(500);
        });
    }

    $('.click-task').on('click',function (){
        var this_ = $(this);
        var task_id = this_.closest('tr').attr('data-id');
        var now_status = this_.closest('tr').attr('data-status');

        $.ajax({
            url:'/change-task',
            method:'post',
            dataType:'json',
            data:{now_status:now_status,task_id:task_id,_token: '{{ csrf_token() }}'},
            success:function (response){
                this_.data('status', response.status);

                if(response.status){ // complete
                    this_.find('i').removeClass('fa-square').addClass('fa-check-square');
                    this_.closest('tr').attr('data-status',1).addClass('bg-success').addClass('color-white').find('.task_text');
                    this_.closest('tr').find('.task_text').addClass('text-decoration-line-through');
                }else{ // uncomplete
                    this_.find('i').removeClass('fa-check-square').addClass('fa-square');
                    this_.closest('tr').attr('data-status',0).removeClass('bg-success').removeClass('color-white');
                    this_.closest('tr').find('.task_text').removeClass('text-decoration-line-through');
                }

            }
        });
    });

    $('.delete-task').on('click',function (){
        var this_ = $(this);
        var task_id = this_.closest('tr').attr('data-id');
        if(confirm('Silmek istediğinize emin misiniz?')){
            $.ajax({
                url:'/delete-task',
                method:'post',
                dataType:'json',
                data:{task_id:task_id,_token: '{{ csrf_token() }}'},
                success:function (response){
                    if(response.status){
                        this_.closest('tr').fadeOut();
                    }else{
                        alert("Bir Hata Oluştu!");
                    }
                }
            });
        }
    });

    $(document).on('click','.edit-task',function (e) {
        var this_ = $(this);
        var task_id = this_.closest('tr').attr('data-id');
        $.ajax({
            url:'/edit-task',
            method:'post',
            dataType:'json',
            data:{task_id:task_id,_token: '{{ csrf_token() }}'},
            success:function (response){
                if(response.status){
                    $("#taskEditModal").find('form').trigger('reset');
                    $("#taskEditModal").modal('show');

                    $("#taskEditModal input[name=modal_task_date]").val(response.data.target_date);
                    $("#taskEditModal input[name=modal_task_id]").val(response.data.id);
                    $("#taskEditModal textarea[name=modal_task]").val(response.data.task);
                }else{
                    alert("Bir Hata Oluştu!");
                }
            }
        });
    });

    $('.update-task').on('click',function (){
        $method = $("#taskEditModal form").attr('method');
        $action = $("#taskEditModal form").attr('action');
        $data = new FormData($("#taskEditModal form")[0]);

        $.ajax({
            type: $method,
            url: $action,
            data: $data,
            contentType: false,
            processData: false,
            success:function (response){
                try {
                    if (response.status == true) {
                        $("#taskEditModal").modal('hide');
                        $("#taskEditModal").find('form').trigger('reset');

                        refreshTable();
                    } else {
                        alert("Bir Hata Oluştu!");
                    }
                } catch (err) {
                    console.log(err);
                }
            }
        });
    });

    $("form#create-task").submit(function (e) {
        $('.message-box').html('');

        $form = $(this);
        $action = $form.attr('action');
        $method = $form.attr('method');
        $data = new FormData($form[0]);

        $.ajax({
            type: $method,
            url: $action,
            data: $data,
            contentType: false,
            processData: false,
            beforeSend: function () {
                $('#save').attr('disabled', true);
                $('#save').html('Lütfen Bekleyin.. <i class="icon-hour-glass ml-2"></i>');
            },
            success: function (data) {
                try {
                    if (data.status == true) {
                        refreshTable();

                        $('textarea[name=task]').val('');
                        $('input[name=target_date]').val('');
                        $('.custom-calendar-date').html('Takvim');
                    } else {
                        var err_txt = '';
                        $.each(data.errors, function (fieldName, errors) {
                            $.each(errors, function (key, value) {
                                err_txt += '<div class="alert bg-danger text-white alert-dismissible"><button type="button" class="close" data-dismiss="alert"><span>×</span></button>';
                                err_txt += value;
                                err_txt += '</div>';
                            });
                        });
                        $('.message-box').append(err_txt);
                    }
                } catch (err) {
                    console.log(err);
                }
            },
            complete:function (){
                $('#save').html('Ekle <i class="icon-paperplane ml-2"></i>');
                $('#save').attr('disabled', false);
            }
        });

        e.preventDefault();
        return false;
    });

    $('.custom-calendar').on('click',function (){
        $('.hello-week-form').css('display','block');
    });

    $('input[name=task_date]').on('click',function (){
        $('.hello-week-modal').css('display','block');
    });

    var myCalendar = new HelloWeek({
        selector: '.hello-week-form',
        lang: 'en',
        langFolder: '{{ asset('') }}/library/calendar-hello-week/hello-week/dist/langs/',
        format: 'DD/MM/YYYY',
        weekShort: true,
        monthShort: false,
        multiplePick: false,
        defaultDate: null,
        todayHighlight: false,
        disablePastDays: false,
        disabledDaysOfWeek: null,
        disableDates: [],//["2021-11-10","2021-11-11"],["2021-11-22","2021-11-25"],["2021-11-26","2021-11-28"]
        weekStart: 1, // week start on Sunday
        daysHighlight: null,
        daysSelected: null,
        range: false,
        rtl: false,
        locked: false,
        minDate: null,
        maxDate: null,
        nav: ['<', '>'],
        onLoad: () => { /** callback function */
        },
        onChange: () => { /** callback function */
        },
        onSelect: () => {
            var selectedDateProductTimestamp = myCalendar.daysSelected[0];
            var d = new Date(selectedDateProductTimestamp);
            var date = new Date(d);
            var yr = date.getFullYear();
            var month = date.getMonth();
            var month2 = parseInt(month) + 1;
            var month2 = month2 < 10 ? '0' + month2 : month2;
            var day = date.getDate() < 10 ? '0' + date.getDate() : date.getDate();
            var newDateFormat = yr + "-" + month2 + "-" + day;

            var custom_date =  new Date(yr, month, day);
            var month_txt   = custom_date.toLocaleString('default', { month: 'long' });
            $('input[name="target_date"]').val(newDateFormat);

            $('.custom-calendar-date').html(day+" "+month_txt);

            $('.hello-week-form').css('display','none');
        },
        onClear: () => { /** callback function */ }
    });

    var myCalendarModal = new HelloWeek({
        selector: '.hello-week-modal',
        lang: 'en',
        langFolder: '{{ asset('') }}/library/calendar-hello-week/hello-week/dist/langs/',
        format: 'DD/MM/YYYY',
        weekShort: true,
        monthShort: false,
        multiplePick: false,
        defaultDate: null,
        todayHighlight: false,
        disablePastDays: false,
        disabledDaysOfWeek: null,
        disableDates: [],//["2021-11-10","2021-11-11"],["2021-11-22","2021-11-25"],["2021-11-26","2021-11-28"]
        weekStart: 1, // week start on Sunday
        daysHighlight: null,
        daysSelected: null,
        range: false,
        rtl: false,
        locked: false,
        minDate: null,
        maxDate: null,
        nav: ['<', '>'],
        onLoad: () => { /** callback function */
        },
        onChange: () => { /** callback function */
        },
        onSelect: () => {
            var selectedDateProductTimestamp = myCalendarModal.daysSelected[0];
            var d = new Date(selectedDateProductTimestamp);
            var date = new Date(d);
            var yr = date.getFullYear();
            var month = date.getMonth();
            var month2 = parseInt(month) + 1;
            var month2 = month2 < 10 ? '0' + month2 : month2;
            var day = date.getDate() < 10 ? '0' + date.getDate() : date.getDate();
            var newDateFormat = yr + "-" + month2 + "-" + day;

            var custom_date =  new Date(yr, month, day);
            var month_txt   = custom_date.toLocaleString('default', { month: 'long' });
            $('input[name="task_date"]').val(newDateFormat);

            $('.hello-week-modal').css('display','none');
        },
        onClear: () => { /** callback function */ }
    });
</script>
</html>
