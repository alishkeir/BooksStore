@component('mail::message')
# Új üzenet érkezett a(z) {{ \App\Helpers\StoreHelper::currentStoreName() }} weboldalról

@component('mail::panel')
<b>Tárgy:</b> {{ $prospect['subject'] }} <br>
<b>Név:</b> {{ $prospect['name'] }} <br>
<b>Email:</b> {{ $prospect['email'] }} <br>
<b>Message:</b> <br>
{{ $prospect['message'] }}
@endcomponent

Üdv,<br>
{{ config('app.name') }}
@endcomponent
