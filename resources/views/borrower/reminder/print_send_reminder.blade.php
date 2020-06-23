<style>
    .text-center {
        text-align: center;
    }

</style>


<div>
    <h3 class="text-center">
        @if(!empty(\App\Models\Setting::where('setting_key','company_logo')->first()->setting_value))
            <img src="{{ asset('uploads/'.\App\Models\Setting::where('setting_key','company_logo')->first()->setting_value) }}"
                 class="img-responsive" width="150"/>

        @endif
    </h3>
    <h1 class="text-center"><b>{{$company_name}}</b></h1>
    <h2 class="text-center"><b>{{trans('general.send_reminder')}}</b></h2>
    <div style="width: 980px;height: 260px;margin-left: auto;margin-right: auto;border-top: solid thin rgba(2, 180, 209, 0.44);border-bottom: solid thin rgba(2, 180, 209, 0.44);padding: 20px;text-transform: capitalize">
        <div class="text-center p-5" style="font-size: 24px">
            <?php echo $message; ?>
        </div>
    </div>
    <div style="width: 980px;margin-top:30px;margin-left: auto;margin-right: auto;padding: 20px;text-transform: capitalize">
        <h3 class="text-center"><b>{{trans_choice('general.date',1)}} : </b>{{date('M j, Y', strtotime(date("Y-m-d")))}}</b></h3>
    </div>
</div>

<script>
    window.onload = function () {
        window.print();
    }
</script>