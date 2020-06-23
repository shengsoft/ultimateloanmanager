<style>
    .row {
        margin-right: -15px;
        margin-left: -15px;
        clear: both;
    }

    .col-md-6 {
        width: 50%;
        position: relative;
        min-height: 1px;
        padding-right: 15px;
        padding-left: 15px;
    }

    .well {
        min-height: 20px;
        padding: 19px;
        margin-bottom: 20px;
        background-color: #f5f5f5;
        border: 1px solid #e3e3e3;
        border-radius: 4px;
        -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .05);
        box-shadow: inset 0 1px 1px rgba(0, 0, 0, .05);
    }

    .text-left {
        text-align: left;
    }

    .text-right {
        text-align: right;
    }

    .text-center {
        text-align: center;
    }

    .text-justify {
        text-align: justify;
    }

    .pull-right {
        float: right !important;
    }
</style>


<div>
    <h3 class="text-center"><b>{{\App\Models\Setting::where('setting_key','company_name')->first()->setting_value}}</b>
    </h3>

    <h3 class="text-center">
        <b>{{trans_choice('general.account',1)}} {{trans_choice('general.payment',1)}} {{trans_choice('general.schedule',1)}}</b>
    </h3>

    <div style="width: 980px;height: 224px;margin-left: auto;margin-right: auto;border-top: solid thin rgba(2, 180, 209, 0.44);border-bottom: solid thin rgba(2, 180, 209, 0.44);padding: 20px;text-transform: capitalize">
        <div style="width: 300px;margin-right: 20px;float: left">
            <b>{{trans_choice('general.date',1)}}:</b>{{date("Y-m-d")}}<br><br>
            <b>{{$payments}}</b>
        </div>
        <div style="width: 300px;margin-right: 40px;float: left">
            <b>{{trans_choice('general.account',1)}} #</b><span class="pull-right">{{$payments}}</span><br><br>
            <b>Start Date</b><span class="pull-right">{{$payments}}</span><br><br>
            <b>End Date</b><span class="pull-right">{{$payments}}</span><br><br>
            <b>{{trans_choice('general.repayment',1)}}</b><span class="pull-right"
                                                                style="">{{$payments}}</span><br><br>
            <b>{{trans_choice('general.amount',1)}}</b><span class="pull-right"
                                                             style="">{{$payments}}</span><br><br>

        </div>
        <div style="width: 300px;float: left">
            <b>{{trans_choice('general.due',1)}}</b><span class="pull-right"
                                                          style="">{{$payments}}</span><br><br>
            <b>{{trans_choice('general.paid',1)}}</b><span class="pull-right"
                                                           style="">{{$payments}}</span><br><br>

        </div>
    </div>
    <div style="width: 980px;margin-top:30px;margin-left: auto;margin-right: auto;padding: 20px;text-transform: capitalize">
        
    </div>
</div>

<script>
    window.onload = function () {
        window.print();
    }
</script>