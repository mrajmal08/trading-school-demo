@component('mail::message')
# Challenges Purchase

Dear {{ $name }},<br>
Weare happy to let you know that your purchase of our {{ $challenge }} has been a success. We appreciate your decision to entrust us with your 
education and your confidence in us to give you the information and resources you need to be successful.

Your Challenge plan details are as follows:

Challenge Plan Name : {{ $challenge }} <br>
Date of Purchase: {{ $date }}<br>
Amount Paid: {{ $price }}<br>

You will receive access to the challenge plan shortly, which includes video tutorials, interactive quizzes, and other resources to help you learn the ins
 and outs of trading. Our team of experts will be available to assist you throughout your learning journey, and you can reach out to us anytime 
 with any questions or concerns.

We believe that investing in yourself is the best investment you can make, and we are confident that our trading learning plan will 
provide you with valuable knowledge and skills to succeed in the market.

Thank you again for choosing our trading learning plan. We wish you all the best in your trading journey!



Thanks,<br>
{{ config('app.name') }}
@endcomponent
