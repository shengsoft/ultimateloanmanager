@extends('layouts.master')
@section('title')
    {{trans_choice('general.edit',1)}} {{trans_choice('general.charge',1)}}
@endsection
@section('content')
    <div class="panel panel-white">
        <div class="panel-heading">
            <h6 class="panel-title">{{trans_choice('general.edit',1)}} {{trans_choice('general.charge',1)}}</h6>

            <div class="heading-elements">

            </div>
        </div>
        {!! Form::open(array('url' => url('charge/'.$charge->id.'/update'), 'method' => 'post', 'class' => 'form-horizontal')) !!}
        <div class="panel-body">
            <div class="form-group">
                {!! Form::label('name',trans_choice('general.name',1)." *",array('class'=>'col-sm-2 control-label')) !!}
                <div class="col-sm-10">
                    {!! Form::text('name',$charge->name, array('class' => 'form-control', 'placeholder'=>"",'required'=>'required')) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('product',trans_choice('general.product',1)." *",array('class'=>'col-sm-2 control-label')) !!}
                <div class="col-sm-10">
                    {!! Form::text('product',$charge->product, array('class' => 'form-control', 'id'=>"product",'required'=>'required','readonly'=>'readonly')) !!}
                </div>
            </div>
            <div class="form-group" id="loanChargeTypeDiv">
                {!! Form::label('loan_charge_type',trans_choice('general.charge',1)." ".trans_choice('general.type',1),array('class'=>'col-sm-2 control-label')) !!}
                <div class="col-sm-10">
                    {!! Form::select('loan_charge_type',['disbursement'=>trans_choice('general.disbursement',1),'specified_due_date'=>trans_choice('general.specified_due_date',2),'installment_fee'=>trans_choice('general.installment_fee',2),'overdue_installment_fee'=>trans_choice('general.overdue_installment_fee',2),'loan_rescheduling_fee'=>trans_choice('general.loan_rescheduling_fee',2),'overdue_maturity'=>trans_choice('general.overdue_maturity',2)],$charge->charge_type, array('class' => 'form-control', 'id'=>"loan_charge_type",'required'=>'required')) !!}
                </div>
            </div>
            <div class="form-group" id="savingsChargeTypeDiv">
                {!! Form::label('savings_charge_type',trans_choice('general.charge',1)." ".trans_choice('general.type',1),array('class'=>'col-sm-2 control-label')) !!}
                <div class="col-sm-10">
                    {!! Form::select('savings_charge_type',['specified_due_date'=>trans_choice('general.specified_due_date',2),'savings_activation'=>trans_choice('general.savings_activation',2),'withdrawal_fee'=>trans_choice('general.withdrawal_fee',2),'annual_fee'=>trans_choice('general.annual_fee',2),'monthly_fee'=>trans_choice('general.monthly_fee',2)],$charge->charge_type, array('class' => 'form-control', 'id'=>"savings_charge_type",'required'=>'required')) !!}
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12">
                    <div class="row">
                        {!! Form::label('amount',trans_choice('general.amount',1)." *",array('class'=>'col-sm-2 control-label')) !!}
                        <div class="col-sm-4">
                            {!! Form::text('amount',$charge->amount, array('class' => 'form-control touchspin', 'id'=>"amount",'required'=>'required')) !!}
                        </div>
                        <div class="col-sm-3">
                            {!! Form::label('penalty_period',trans_choice('general.penalty',1).' '.trans_choice('general.period',1)." *",array('class'=>'col-sm-5 control-label text-right')) !!}
                        </div>
                        <div class="col-sm-3">
                            {!! Form::select('penalty_period',array('day'=>trans_choice('general.day',1),'week'=>trans_choice('general.week',1),'month'=>trans_choice('general.month',1),'year'=>trans_choice('general.year',1)),$charge->penalty, array('class' => 'form-control', 'placeholder'=>"",'required'=>'required')) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group" id="loanChargeOptionDiv">
                {!! Form::label('loan_charge_option',trans_choice('general.charge',1)." ".trans_choice('general.option',1),array('class'=>'col-sm-2 control-label')) !!}
                <div class="col-sm-10">
                    {!! Form::select('loan_charge_option',['fixed'=>trans_choice('general.fixed',1),'principal_due'=>trans_choice('general.principal',1).' '.trans_choice('general.due',1),'principal_interest'=>trans_choice('general.principal',1).' + '.trans_choice('general.interest',1).' '.trans_choice('general.due',1),'interest_due'=>trans_choice('general.interest',1).' '.trans_choice('general.due',1),'total_due'=>trans_choice('general.total',1).' '.trans_choice('general.due',1),'original_principal'=>trans_choice('general.original',2).' '.trans_choice('general.principal',1)],$charge->charge_option, array('class' => 'form-control', 'id'=>"loan_charge_option",'required'=>'required')) !!}
                </div>
            </div>
            <div class="form-group" id="savingsChargeOptionDiv">
                {!! Form::label('savings_charge_option',trans_choice('general.charge',1)." ".trans_choice('general.option',1),array('class'=>'col-sm-2 control-label')) !!}
                <div class="col-sm-10">
                    {!! Form::select('savings_charge_option',['fixed'=>trans_choice('general.fixed',1),'percentage'=>trans_choice('general.percentage',1)],$charge->charge_option, array('class' => 'form-control', 'id'=>"savings_charge_option",'required'=>'required')) !!}
                </div>
            </div>

            <div class="form-group" id="penaltyDiv">
                {!! Form::label('penalty',trans_choice('general.penalty',1),array('class'=>'col-sm-2 control-label')) !!}
                <div class="col-sm-10">
                    {!! Form::select('penalty',['1'=>trans_choice('general.yes',1),'0'=>trans_choice('general.no',2)],$charge->penalty, array('class' => 'form-control', 'id'=>"penalty",'required'=>'required')) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('active',trans_choice('general.active',1),array('class'=>'col-sm-2 control-label')) !!}
                <div class="col-sm-10">
                    {!! Form::select('active',['1'=>trans_choice('general.yes',1),'0'=>trans_choice('general.no',2)],$charge->active, array('class' => 'form-control', 'id'=>"active",'required'=>'required')) !!}
                </div>
            </div>
            <div class="form-group" id="overrideDiv">
                {!! Form::label('override',trans_choice('general.override',1),array('class'=>'col-sm-2 control-label')) !!}
                <div class="col-sm-10">
                    {!! Form::select('override',['1'=>trans_choice('general.yes',1),'0'=>trans_choice('general.no',2)],$charge->override, array('class' => 'form-control', 'id'=>"override",'required'=>'required')) !!}
                </div>
            </div>
        </div>
        <!-- /.panel-body -->
        <div class="panel-footer">
            <div class="heading-elements">
                <button type="submit" class="btn btn-primary pull-right"> {{trans_choice('general.save',1)}}</button>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
    <!-- /.box -->
@endsection
@section('footer-scripts')
    <script>
        $(document).ready(function (e) {
            if ($('#product').val() === "loan") {
                $('#loanChargeTypeDiv').show();
                $('#loanChargeOptionDiv').show();
                $('#penaltyDiv').show();
                $('#overrideDiv').show();
                $('#savingsChargeTypeDiv').hide();
                $('#savingsChargeOptionDiv').hide();
            } else {
                $('#savingsChargeTypeDiv').show();
                $('#savingsChargeOptionDiv').show();
                $('#loanChargeTypeDiv').hide();
                $('#loanChargeOptionDiv').hide();
                $('#penaltyDiv').hide();
                $('#overrideDiv').hide();

            }
            $('#product').change(function () {
                if ($('#product').val() === "loan") {
                    $('#loanChargeTypeDiv').show();
                    $('#loanChargeOptionDiv').show();
                    $('#penaltyDiv').show();
                    $('#overrideDiv').show();
                    $('#savingsChargeTypeDiv').hide();
                    $('#savingsChargeOptionDiv').hide();
                } else {
                    $('#savingsChargeTypeDiv').show();
                    $('#savingsChargeOptionDiv').show();
                    $('#loanChargeTypeDiv').hide();
                    $('#loanChargeOptionDiv').hide();
                    $('#penaltyDiv').hide();
                    $('#overrideDiv').hide();

                }
            })
        })
    </script>
@endsection