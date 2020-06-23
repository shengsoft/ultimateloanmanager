@extends('layouts.master')
@section('title')
    {{ trans('general.dashboard') }}
@endsection
<style>
  .modal-header, h4, .close {
    background-color: #5cb85c;
    color:white !important;
    text-align: center;
  }
  .modal-footer {
    background-color: #f9f9f9;
  }
  </style>
@section('content')
    @if($message = Session::get('success'))
        <div class="row">
            <div class="col-sm-12">
                <div class="alert bg-success">{{ $message }}</div>
            </div>
        </div>
    @endif
    @if($message = Session::get('warning'))
        <div class="row">
            <div class="col-sm-12">
                <div class="alert bg-danger">{{ $message }}</div>
            </div>
        </div>
    @endif
    <div class="row">
        @if(Sentinel::hasAccess('dashboard.registered_borrowers'))
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="panel panel-body bg-blue-400 has-bg-image">
                    <div class="media no-margin">
                        <div class="media-body">
                            <h3 class="no-margin">{{ \App\Models\Borrower::count() }}</h3>
                            <span class="text-uppercase text-size-mini">{{ trans_choice('general.total',1) }} {{ trans_choice('general.borrower',2) }}</span>
                        </div>

                        <div class="media-right media-middle">
                            <i class="icon-users4 icon-3x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if(Sentinel::hasAccess('dashboard.total_loans_released'))
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="panel panel-body bg-indigo-400 has-bg-image">
                    <div class="media no-margin">
                        <div class="media-body">
                            @if(\App\Models\Setting::where('setting_key', 'currency_position')->first()->setting_value=='left')
                                <h3 class="no-margin"> {{ \App\Models\Setting::where('setting_key', 'currency_symbol')->first()->setting_value }}{{ number_format(\App\Helpers\GeneralHelper::loans_total_principal(),2) }} </h3>
                            @else
                                <h3 class="no-margin"> {{ number_format(\App\Helpers\GeneralHelper::loans_total_principal(),2) }}  {{ \App\Models\Setting::where('setting_key', 'currency_symbol')->first()->setting_value}}</h3>
                            @endif
                            <span class="text-uppercase text-size-mini">{{ trans_choice('general.loan',2) }} {{ trans_choice('general.released',1) }}</span>
                        </div>
                        <div class="media-right media-middle">
                            <i class="icon-drawer-out icon-3x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if(Sentinel::hasAccess('dashboard.total_collections'))
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="panel panel-body bg-success-400 has-bg-image">
                    <div class="media no-margin">
                        <div class="media-body">
                            @if(\App\Models\Setting::where('setting_key', 'currency_position')->first()->setting_value=='left')
                                <h3 class="no-margin"> {{ \App\Models\Setting::where('setting_key', 'currency_symbol')->first()->setting_value }}{{ number_format(\App\Helpers\GeneralHelper::loans_total_paid(),2) }} </h3>
                            @else
                                <h3 class="no-margin"> {{ number_format(\App\Helpers\GeneralHelper::loans_total_paid(),2) }}  {{ \App\Models\Setting::where('setting_key', 'currency_symbol')->first()->setting_value}}</h3>
                            @endif
                            <span class="text-uppercase text-size-mini">{{ trans_choice('general.payment',2) }}</span>
                        </div>
                        <div class="media-right media-middle">
                            <i class="icon-enter6 icon-3x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if(Sentinel::hasAccess('dashboard.loans_disbursed'))
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="panel panel-body bg-danger-400 has-bg-image">
                    <div class="media no-margin">
                        <div class="media-body">
                            @if(\App\Models\Setting::where('setting_key', 'currency_position')->first()->setting_value=='left')
                                <h3 class="no-margin"> {{ \App\Models\Setting::where('setting_key', 'currency_symbol')->first()->setting_value }}{{ number_format(\App\Helpers\GeneralHelper::loans_total_due(),2) }} </h3>
                            @else
                                <h3 class="no-margin"> {{ number_format(\App\Helpers\GeneralHelper::loans_total_due(),2) }}  {{ \App\Models\Setting::where('setting_key', 'currency_symbol')->first()->setting_value}}</h3>
                            @endif
                            <span class="text-uppercase text-size-mini">{{ trans_choice('general.due',1) }} {{ trans_choice('general.amount',1) }}</span>
                        </div>
                        <div class="media-right media-middle">
                            <i class="icon-pen-minus icon-3x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
    
    <div class="row">
        @if(Sentinel::hasAccess('dashboard.loans_disbursed'))
            <div class="col-md-4">
                <div class="panel panel-flat">
                    <div class="panel-body">

                        <canvas id="loan_status_pie" height="300"></canvas>
                        <div class="list-group no-border no-padding-top">
                            @foreach(json_decode($loan_statuses) as $key)
                                <a href="{{$key->link}}" class="list-group-item">
                                    <span class="badge bg-{{$key->class}} pull-right">{{$key->value}}</span>
                                    {{$key->label}}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <div class="col-md-8">
        @if(Sentinel::hasAccess('dashboard.loans_disbursed'))
            <!-- Sales stats -->
                <div class="panel panel-flat">
                    <div class="panel-heading">
                        <h6 class="panel-title">{{ trans_choice('general.collection',1) }} {{ trans_choice('general.statistic',2) }}</h6>
                        <div class="heading-elements">
                        </div>
                    </div>
                    <div class="panel-body">
                        <?php
                        $target = 0;
                        foreach (\App\Models\LoanSchedule::where('year', date("Y"))->where('month',
                            date("m"))->get() as $key) {
                            $target = $target + $key->principal + $key->interest + $key->fees + $key->penalty;
                        }
                        $paid_this_month = \App\Models\LoanTransaction::where('transaction_type',
                            'repayment')->where('reversed', 0)->where('year', date("Y"))->where('month',
                            date("m"))->sum('credit');
                        if ($target > 0) {
                            $percent = round(($paid_this_month / $target) * 100);
                        } else {
                            $percent = 0;
                        }

                        ?>
                        <div class="container-fluid">
                            <div class="row text-center">
                                <div class="col-md-4">
                                    <div class="content-group">
                                        @if(\App\Models\Setting::where('setting_key', 'currency_position')->first()->setting_value=='left')
                                            <h5 class="text-semibold no-margin">{{ \App\Models\Setting::where('setting_key', 'currency_symbol')->first()->setting_value }}{{ number_format(\App\Models\LoanTransaction::where('transaction_type',
                    'repayment')->where('reversed', 0)->where('date',date("Y-m-d"))->sum('credit'),2) }}  </h5>
                                        @else
                                            <h5 class="text-semibold no-margin">{{ number_format(\App\Models\LoanTransaction::where('transaction_type',
                    'repayment')->where('reversed', 0)->where('date',date("Y-m-d"))->sum('credit'),2) }}  {{ \App\Models\Setting::where('setting_key', 'currency_symbol')->first()->setting_value}}</h5>
                                        @endif

                                        <span class="text-muted text-size-small">{{ trans_choice('general.today',1) }}</span>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="content-group">
                                        @if(\App\Models\Setting::where('setting_key', 'currency_position')->first()->setting_value=='left')
                                            <h5 class="text-semibold no-margin">{{ \App\Models\Setting::where('setting_key', 'currency_symbol')->first()->setting_value }}{{ number_format(\App\Models\LoanTransaction::where('transaction_type',
                    'repayment')->where('reversed', 0)->whereBetween('date',array('date_sub(now(),INTERVAL 1 WEEK)','now()'))->sum('credit'),2) }} </h5>
                                        @else
                                            <h5 class="text-semibold no-margin">{{ number_format(\App\Models\LoanTransaction::where('transaction_type',
                    'repayment')->where('reversed', 0)->whereBetween('date',array('date_sub(now(),INTERVAL 1 WEEK)','now()'))->sum('credit'),2) }}  {{ \App\Models\Setting::where('setting_key', 'currency_symbol')->first()->setting_value}}</h5>
                                        @endif
                                        <span class="text-muted text-size-small">{{ trans_choice('general.last',1) }} {{ trans_choice('general.week',1) }}</span>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="content-group">
                                        @if(\App\Models\Setting::where('setting_key', 'currency_position')->first()->setting_value=='left')
                                            <h5 class="text-semibold no-margin">{{ \App\Models\Setting::where('setting_key', 'currency_symbol')->first()->setting_value }}{{ number_format($paid_this_month,2) }} </h5>
                                        @else
                                            <h5 class="text-semibold no-margin">{{ number_format($paid_this_month,2) }}  {{ \App\Models\Setting::where('setting_key', 'currency_symbol')->first()->setting_value}}</h5>
                                        @endif
                                        <span class="text-muted text-size-small">{{ trans_choice('general.this',1) }} {{ trans_choice('general.month',1) }}</span>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="text-center">
                                        <h6 class="no-margin text-semibold">{{ trans_choice('general.monthly',1) }} {{ trans_choice('general.target',1) }}</h6>
                                    </div>
                                    <div class="progress" data-toggle="tooltip"
                                         title="Target:{{number_format($target,2)}}">

                                        <div class="progress-bar bg-teal progress-bar-striped active"
                                             style="width: {{$percent}}%">
                                            <span>{{$percent}}% {{ trans_choice('general.complete',1) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if(Sentinel::hasAccess('dashboard.loans_collected_monthly_graph'))
                <div class="panel panel-flat">
                    <div class="panel-heading">
                        <h5 class="panel-title"> {{trans('general.overdue_invoices_bills')}} </h5>
                    </div>
                    <div class="panel-body">
                        <table id="data-table" class="table table-striped table-condensed table-hover">
                            <thead style="font-size: 13px;">
                            <tr>
                                <th>{{trans_choice('general.loan',1)}}</th>
                                <th>{{trans_choice('general.borrower',1)}}</th>
                                <th>{{trans_choice('general.amount',1)}} {{ trans_choice('general.due',1) }}</th>
                                <th>{{ trans_choice('general.action',1) }}</th>
                            </tr>
                            </thead>
                            <tbody style="font-size: 13px;">
                            @foreach($borrowers as $key)
                                @php
                                    $borrowerTotalLoansDue = round(\App\Helpers\GeneralHelper::borrower_loans_total_due($key->borrower_id), 2);
                                    $borrowerTotalLoansBalance = round((\App\Helpers\GeneralHelper::borrower_loans_total_due($key->borrower_id) - \App\Helpers\GeneralHelper::borrower_loans_total_paid($key->borrower_id)), 2);
                                    $borrowerTotalLoansPaid = \App\Helpers\GeneralHelper::borrower_loans_total_paid($key->borrower_id);
                                    $due_date = date('M j, Y', strtotime(\App\Models\LoanSchedule::where('borrower_id', $key->borrower_id)->min('due_date')));
                                    $paid_date = date('M j, Y', strtotime(\App\Models\LoanSchedule::where('borrower_id', $key->borrower_id)->max('due_date')));
                                    $message = trans('general.reminder_message');
                                    $message = str_replace('{borrowerTitle}', $key->borrower->title, $message);
                                    $message = str_replace('{due_date}', $due_date, $message);
                                    $message = str_replace('{paid_date}', $paid_date, $message);
                                    $message = str_replace('{borrowerFirstName}', $key->borrower->first_name, $message);
                                    $message = str_replace('{borrowerLastName}', $key->borrower->last_name, $message);
                                    $message = str_replace('{borrowerAddress}', $key->borrower->address, $message);
                                    $message = str_replace('{borrowerUniqueNumber}', $key->borrower->unique_number, $message);
                                    $message = str_replace('{borrowerMobile}', $key->borrower->mobile, $message);
                                    $message = str_replace('{borrowerPhone}', $key->borrower->phone, $message);
                                    $message = str_replace('{borrowerEmail}', $key->borrower->email, $message);
                                    $message = str_replace('{company_name}', $company_name, $message);
                                    $message = str_replace('{borrowerTotalLoansDue}', $borrowerTotalLoansDue, $message);
                                    $message = str_replace('{borrowerTotalLoansBalance}', $borrowerTotalLoansBalance, $message);
                                    $message = str_replace('{borrowerTotalLoansPaid}', $borrowerTotalLoansPaid, $message);
                                    $message = str_replace('{currency}', '$', $message);
                                @endphp
                                <tr>
                                    <td>{{ $key->id }}</td>
                                    <td>
                                        @if(!empty($key->borrower))
                                            <a href="{{url('borrower/'.$key->borrower_id.'/show')}}">{{$key->borrower->first_name}} {{$key->borrower->last_name}}</a>
                                        @else
                                            <span class="label label-danger">{{trans_choice('general.broken',1)}} <i
                                                        class="fa fa-exclamation-triangle"></i> </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(\App\Models\Setting::where('setting_key', 'currency_position')->first()->setting_value=='left')
                                            {{ \App\Models\Setting::where('setting_key', 'currency_symbol')->first()->setting_value }} {{number_format(\App\Helpers\GeneralHelper::loan_total_balance($key->id),2)}}
                                        @else
                                            {{number_format(\App\Helpers\GeneralHelper::loan_total_balance($key->id),2)}} {{ \App\Models\Setting::where('setting_key', 'currency_symbol')->first()->setting_value}}
                                        @endif
                                    </td>
                                    <td>
                                        <ul class="icons-list">
                                            <li class="dropdown">
                                                <span>
                                                    <a href="javascript:sendReminder({{$key->borrower_id}})"> {{trans('general.send_a_reminder')}} </a>
                                                    <!-- Modal -->
                                                    <div class="modal fade" id="send_reminder_dialogue_{{$key->borrower_id}}" role="dialog">
                                                        <div class="modal-dialog">
                                                        <!-- Modal content-->
                                                            {!! Form::open(array('url' => 'loan/borrower/reminder/'.$key->borrower_id.'/send')) !!}
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                    <h1> {{trans('general.send_payment_reminder')}} </h1>
                                                                </div>
                                                                <div class="modal-body" style="padding:40px 50px;">
                                                                    <div class="col-md-2">
                                                                        <div class="form-group">
                                                                            {!! Form::label('send_from', trans_choice('general.from',1).' :') !!}
                                                                        </div>
                                                                        <div class="form-group">
                                                                            {!! Form::label('send_to', trans_choice('general.to',1).' :') !!}
                                                                        </div>
                                                                        <div class="form-group">
                                                                            {!! Form::label('subject', trans_choice('general.subject',1).' :', array('class' => 'form-control')) !!}
                                                                        </div>
                                                                        <div class="form-group">
                                                                            {!! Form::label('message', trans_choice('general.message',1).' :', array('class' => 'form-control')) !!}
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-10">
                                                                        <div class="form-group">
                                                                            {!! Form::label('send_from', $company_email) !!}
                                                                        </div>
                                                                        <div class="form-group">
                                                                            {!! Form::label('send_to', $key->borrower->email) !!}
                                                                        </div>
                                                                        <div class="form-group">
                                                                            {!! Form::text('subject', trans('general.reminder_from_moniready'), array('class' => 'form-control', 'required'=>"")) !!}
                                                                        </div>
                                                                        <div class="form-group">
                                                                            {!! Form::textarea('message', $message, array('class' => 'form-control tinymce', 'rows' => 15)) !!}
                                                                        </div>
                                                                        {!! Form::hidden('send_from', $company_email) !!}
                                                                        {!! Form::hidden('send_to', $key->borrower->email) !!}
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="submit" class="btn btn-success btn-default pull-right ml-5"><span class="glyphicon glyphicon-send"></span> {{trans('general.send_reminder')}}</button>
                                                                    <!-- <a href="{{ url('loan/reminder_id/show') }}" class="btn btn-primary btn-default pull-right"><span class="glyphicon glyphicon-eye-open"></span> {{trans('general.preview')}}</a> -->
                                                                    <button class="btn btn-danger btn-default pull-right" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> {{trans('general.cancel')}}</button>
                                                                </div>
                                                            </div>
                                                            {{!! Form::close() !!}}
                                                        </div>
                                                    </div> 
                                                </span>
                                                <span class="dropdown-toggle text-center ml-5" data-toggle="dropdown">
                                                    <a class="text-center" style="border: 1px solid; padding: 5px 10px; border-radius: 50%;">
                                                    <i class="fa fa-caret-down"></i>
                                                    </a>
                                                </span>
                                                <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                                    <!-- <li><a href="{{ url('loan/borrower/reminder/'.$key->borrower_id.'/show') }}"><i class="fa fa-eye"></i> {{ trans_choice('general.view',2) }} </a></li> -->
                                                    <!-- <li><a href="{{ url('loan/borrower/reminder/'.$key->borrower_id.'/edit') }}"> <i class="fa fa-edit"></i> {{ trans('general.edit') }} </a></li> -->
                                                    <li><a href="{{ url('loan/'.$key->id. '/repayment/create') }}"> <i class="fa fa-calendar-check-o"></i> {{ trans('general.record_payment') }} </a></li>
                                                    <li><a href="{{ url('loan/borrower/reminder/'.$key->borrower_id.'/pdf') }}"> <i class="fa fa-file-pdf-o"></i> {{ trans('general.export_as_pdf') }} </a></li>
                                                    <li><a href="{{ url('loan/borrower/reminder/'.$key->borrower_id.'/print') }}"> <i class="fa fa-print"></i> {{ trans('general.print') }} </a></li>
                                                    <li><a href="{{ url('loan/borrower/reminder/'.$key->id.'/delete') }}"> <i class="fa fa-trash"></i> {{ trans('general.delete') }} </a></li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
            @if(Sentinel::hasAccess('dashboard.loans_collected_monthly_graph'))
                <div class="panel panel-flat">
                    <div class="panel-heading">
                        <h6 class="panel-title">{{ trans_choice('general.monthly',1) }} {{trans_choice('general.overview',1)}}</h6>
                        <div class="heading-elements">
                            <ul class="icons-list">
                                <li><a data-action="collapse"></a></li>
                                <li><a data-action="close"></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div id="monthly_actual_expected_data" class="chart" style="height: 320px;">
                        </div>
                    </div>
                </div>
            @endif
        </div>

    </div>

    <script src="{{ asset('assets/plugins/amcharts/amcharts.js') }}"
            type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/amcharts/serial.js') }}"
            type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/amcharts/pie.js') }}"
            type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/amcharts/themes/light.js') }}"
            type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/amcharts/plugins/export/export.min.js') }}"
            type="text/javascript"></script>
    <script>
        AmCharts.makeChart("monthly_actual_expected_data", {
            "type": "serial",
            "theme": "light",
            "autoMargins": true,
            "marginLeft": 30,
            "marginRight": 8,
            "marginTop": 10,
            "marginBottom": 26,
            "fontFamily": 'Open Sans',
            "color": '#888',

            "dataProvider": {!! $monthly_actual_expected_data !!},
            "valueAxes": [{
                "axisAlpha": 0,

            }],
            "startDuration": 1,
            "graphs": [{
                "balloonText": "<span style='font-size:13px;'>[[title]] in [[category]]:<b> [[value]]</b> [[additional]]</span>",
                "bullet": "round",
                "bulletSize": 8,
                "lineColor": "#370fc6",
                "lineThickness": 4,
                "negativeLineColor": "#0dd102",
                "title": "{{trans_choice('general.actual',1)}}",
                "type": "smoothedLine",
                "valueField": "actual"
            }, {
                "balloonText": "<span style='font-size:13px;'>[[title]] in [[category]]:<b> [[value]]</b> [[additional]]</span>",
                "bullet": "round",
                "bulletSize": 8,
                "lineColor": "#d1655d",
                "lineThickness": 4,
                "negativeLineColor": "#d1cf0d",
                "title": "{{trans_choice('general.expected',2)}}",
                "type": "smoothedLine",
                "valueField": "expected"
            }],
            "categoryField": "month",
            "categoryAxis": {
                "gridPosition": "start",
                "axisAlpha": 0,
                "tickLength": 0,
                "labelRotation": 30,

            }, "export": {
                "enabled": true,
                "libs": {
                    "path": "{{asset('assets/plugins/amcharts/plugins/export/libs')}}/"
                }
            }, "legend": {
                "position": "bottom",
                "marginRight": 100,
                "autoMargins": false
            },


        });

    </script>
    <script src="{{ asset('assets/plugins/chartjs/Chart.min.js') }}"
            type="text/javascript"></script>
    <script>
        var ctx3 = document.getElementById("loan_status_pie").getContext("2d");
        var data3 ={!! $loan_statuses !!};
        var myPieChart = new Chart(ctx3).Pie(data3, {
            segmentShowStroke: true,
            segmentStrokeColor: "#fff",
            segmentStrokeWidth: 0,
            animationSteps: 100,
            tooltipCornerRadius: 0,
            animationEasing: "easeOutBounce",
            animateRotate: true,
            animateScale: false,
            responsive: true,

            legend: {
                display: true,
                labels: {
                    fontColor: 'rgb(255, 99, 132)'
                }
            }
        });
        function sendReminder(id) {
            $("#send_reminder_dialogue_" + id).modal();
        }
    </script>
    <script>
        $('#data-table').DataTable({
            "order": [[2, "desc"]],
            "columnDefs": [
                {"orderable": false, "targets": []}
            ],
            "language": {
                "lengthMenu": "{{ trans('general.lengthMenu') }}",
                "zeroRecords": "{{ trans('general.zeroRecords') }}",
                "info": "{{ trans('general.info') }}",
                "infoEmpty": "{{ trans('general.infoEmpty') }}",
                "search": "{{ trans('general.search') }}",
                "infoFiltered": "{{ trans('general.infoFiltered') }}",
                "paginate": {
                    "first": "{{ trans('general.first') }}",
                    "last": "{{ trans('general.last') }}",
                    "next": "{{ trans('general.next') }}",
                    "previous": "{{ trans('general.previous') }}"
                }
            },
            responsive: false
        });
    </script>
@endsection
