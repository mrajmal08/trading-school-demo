@component('mail::message')
# Registration
Dear {{ $name }}<br>
<p>
    This is to inform you that your Trading School account has been successfully created. We are thrilled to have you as a member of 
    our online trading community and look forward to helping you achieve your financial goals.
</p>

Your login credentials are as follows:

Username: {{ $username }}<br>
Password: {{ $password }}


@component('mail::button', ['url' => $url])
Login
@endcomponent

Do not divulge this information to anyone, and please keep it safe and secure. If you have any trouble logging into your account or accessing any of our resources, please contact our customer support team for assistance.

As a Trading School member, you will have access to a wide range of resources, including online courses, trading guides, and educational materials. We are dedicated to giving you the best experience possible, and our team of knowledgeable traders and educators will be there to support you at every stage of your trading journey.

If you have any questions or concerns, please do not hesitate to contact us. We are always pleased to assist.

We appreciate you selecting Trading School as your source for trading education. We're eager to assist you in reaching your monetary grail.


Thanks,<br>
{{ config('app.name') }}
@endcomponent
