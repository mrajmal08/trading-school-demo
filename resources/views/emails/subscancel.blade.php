@component('mail::message')
# Introduction

Dear {{ $name }},<br>
Your challenge package subscription will be automatically renewed in 3 days. Please click here to cancel your subscription if you don’t want the auto renewal.

@component('mail::button', ['url' => $url])
Cancel Auto Renew
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
