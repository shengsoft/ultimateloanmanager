<style>
    .text-center {
        text-align: center;
    }

</style>


<div class="text-center">
    <h3 class="text-center">
        @if(!empty(\App\Models\Setting::where('setting_key','company_logo')->first()->setting_value))
            <img src="{{ asset('uploads/'.\App\Models\Setting::where('setting_key','company_logo')->first()->setting_value) }}"
                 class="img-responsive" width="150"/>
        @endif
    </h3>
    <h1 class="text-center"><b>{{$company_name}}</b></h1>
    <h2 class="text-center"><b>{{trans('general.send_reminder')}}</b></h2>
    <div  class="text-center" style="border-top: solid thin rgba(2, 180, 209, 0.44);border-bottom: solid thin rgba(2, 180, 209, 0.44);padding: 20px;text-transform: capitalize">
        <div class="p-5" style="font-size: 20px">
            <?php echo $message; ?>
        </div>
        <h3 class="mt-3" style="text-align: right"><b>{{trans_choice('general.date',1)}} : </b>{{date('M j, Y', strtotime(date("Y-m-d")))}}</b></h3>
    </div>
</div>