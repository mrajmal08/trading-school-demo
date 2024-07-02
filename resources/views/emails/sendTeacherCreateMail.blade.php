@component('mail::message')
# Welcome

{{ $data['name'] }}
<p>Email: {{ $data['email'] }}</p>
<p>Password: {{ $data['password'] }}</p>

@component('mail::button', ['url' => $data['url']])
Click Here to Login
@endcomponent

Thanks,<br>
@endcomponent
