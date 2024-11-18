@component('mail::message')
# Hello, {{ $name }}!

**We have an important notification for you:**

{{ $content }}

@component('mail::button', ['url' => 'http://127.0.0.1:8000'])
Access the system
@endcomponent

Thank you for reading this notification!

Best regards,<br>
{{ config('app.name') }}

---

<div style="text-align: center; margin-top: 20px;">
    <img src="{{ env('APP_URL') }}/images/seal.png" alt="Certification Seal" style="width: 120px; height: auto; margin-top: 20px;">
</div>

@endcomponent
