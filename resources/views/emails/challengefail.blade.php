@component('mail::message')
# Introduction
Dear {{ $name }},<br>

We regret to inform you that your purchase of our trading challenge plan has not been successful. We understand how disappointing this must be for you, and we would like to offer our sincerest apologies. 

Due to exceeding the limite of your daily quota for the challenge plan, it has failed. Try not to exceed the daily limits and youll be able to avoid such encounters. 

We hope you will be able to start trading from tomorrow again. Happy Trading! 


Thanks,<br>
{{ config('app.name') }}
@endcomponent
