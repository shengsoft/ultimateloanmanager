<style>
    table {
        width: 100%;
        border-collapse: collapse;
        font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
    }

    th, td {
        padding: 10px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    th {
        background-color: #4CAF50;
        color: white;
    }
</style>
<div>

    <table class="">
        <caption>
            {{trans_choice('general.collection',1)}} {{trans_choice('general.sheet',1)}}
            @if(!empty($start_date))
                for period: {{$start_date}} to {{$end_date}}
            @endif
        </caption>
        <thead>
        <tr class="">
            <th>{{trans_choice('general.loan_officer',1)}}</th>
            <th>{{trans_choice('general.borrower',1)}}</th>
            <th>{{trans_choice('general.product',1)}}</th>
            <th>{{trans_choice('general.disbursed',1)}}</th>
            <th>{{trans_choice('general.maturity',1)}} {{trans_choice('general.date',1)}}</th>
            <th>{{trans_choice('general.principal',1)}}</th>
            <th>{{trans_choice('general.interest',1)}}</th>
            <th>{{trans_choice('general.fee',2)}}</th>
            <th>{{trans_choice('general.penalty',1)}}</th>
            <th>{{trans_choice('general.total',1)}}</th>
            <th>{{trans_choice('general.payment',2)}}</th>
            <th>{{trans_choice('general.balance',1)}}</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $total_outstanding = 0;
        $total_due = 0;
        $total_payments = 0;
        $total_principal = 0;
        $total_interest = 0;
        $total_fees = 0;
        $total_penalty = 0;
        $total_amount = 0;
        ?>
        @foreach($data as $key)
            <?php
            $loan_due_items = \App\Helpers\GeneralHelper::loan_due_items($key->id);
            $loan_paid_items = \App\Helpers\GeneralHelper::loan_paid_items($key->id);
            $due = $loan_due_items["principal"] + $loan_due_items["interest"] + $loan_due_items["fees"] + $loan_due_items["penalty"];
            $payments = $loan_paid_items["principal"] + $loan_paid_items["interest"] + $loan_paid_items["fees"] + $loan_paid_items["penalty"];
            $balance = $due - $payments;
            $principal = $loan_due_items["principal"];
            $interest = $loan_due_items["interest"];
            $fees = $loan_due_items["fees"];
            $penalty = $loan_due_items["penalty"];

            $total_outstanding = $total_outstanding + $balance;
            $total_due = $total_due + $due;
            $total_principal = $total_principal + $principal;
            $total_interest = $total_interest + $interest;
            $total_fees = $total_fees + $fees;
            $total_penalty = $total_penalty + $penalty;
            $total_payments = $total_payments + $payments;



            //select appropriate schedules


            ?>

            <tr>
                <td><a href="{{url('loan/'.$key->id.'/show')}}">{{$key->id}}</a></td>
                <td>
                    @if(!empty($key->borrower))
                        <a href="{{url('borrower/'.$key->borrower_id.'/show')}}">{{$key->borrower->first_name}} {{$key->borrower->last_name}}</a>
                    @endif
                </td>
                <td>
                    @if(!empty($key->loan_product))
                        {{$key->loan_product->name}}
                    @endif
                </td>
                <td>{{$key->release_date}}</td>
                <td>{{$key->maturity_date}}</td>
                <td>{{number_format($principal,2)}}</td>
                <td>{{number_format($interest,2)}}</td>
                <td>{{number_format($fees,2)}}</td>
                <td>{{number_format($penalty,2)}}</td>
                <td>{{number_format($due,2)}}</td>
                <td>{{number_format($payments,2)}}</td>
                <td>{{number_format($balance,2)}}</td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
        <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th>{{number_format($total_principal,2)}}</th>
            <th>{{number_format($total_interest,2)}}</th>
            <th>{{number_format($total_fees,2)}}</th>
            <th>{{number_format($total_penalty,2)}}</th>
            <th>{{number_format($total_due,2)}}</th>
            <th>{{number_format($total_payments,2)}}</th>
            <th>{{number_format($total_outstanding,2)}}</th>

        </tr>
        </tfoot>
    </table>
</div>