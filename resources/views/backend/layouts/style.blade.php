@php
    $favIcon = App\Models\SiteSetting::first()->favicon ?? 'uploads/site/default-favicon.png';
@endphp

<link rel="icon" type="image/x-icon" href="{{ asset($favIcon) }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/mixitup@3.3.1/dist/mixitup.min.css">
<link rel="stylesheet" href="{{ asset('backend/assets/vendors/choices.js/choices.min.css') }}" />
<link rel="stylesheet" href="{{ asset('backend/assets/vendors/simple-datatables/style.css') }}" />
<link rel="stylesheet" href="{{ asset('backend/assets/vendors/sweetalert2/sweetalert2.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/assets/vendors/toastify/toastify.css') }}">
<link rel="stylesheet" href="{{ asset('backend/assets/css/bootstrap.css') }}">
<link rel="stylesheet" href="{{ asset('backend/assets/vendors/iconly/bold.css') }}">
<link rel="stylesheet" href="{{ asset('backend/assets/vendors/perfect-scrollbar/perfect-scrollbar.css') }}">
<link rel="stylesheet" href="{{ asset('backend/assets/vendors/bootstrap-icons/bootstrap-icons.css') }}">
<link rel="stylesheet" href="{{ asset('backend/assets/vendors/fontawesome/all.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/assets/css/app.css') }}">
<link rel="stylesheet" href="{{ asset('backend/assets/css/custom.css') }}">

@yield('style')
