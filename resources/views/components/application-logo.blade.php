@php
    $logo = App\Models\SiteSetting::first()->site_logo ?? 'uploads/site/default-logo.png';
@endphp

<img src="{{ asset($logo) }}" alt="Logo" class="mx-auto">
