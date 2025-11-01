<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    {{-- تحديد الترميز --}}
    <meta charset="utf-8">
    
    {{-- ضبط عرض الصفحة على الأجهزة المختلفة --}}
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    {{-- توكين CSRF لحماية النماذج --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    {{-- أيقونة الموقع --}}
    <link rel="icon" href="{!! asset('images/favicon.ico') !!}" />
    
    {{-- عنوان الموقع --}}
    <title>{{ config('app.name', 'SPMS') }}</title>

    {{-- تحميل ملفات CSS الأساسية --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    
    {{-- إخفاء العناصر المميزة بـ x-cloak (Alpine.js) --}}
    <style>
        [x-cloak] {
            display: none;
        }
    </style>

    {{-- تحميل الخطوط --}}
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    {{-- أيقونات FontAwesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />

    {{-- تحميل ملفات JS الأساسية --}}
    <script src="{{ asset('js/app.js') }}" defer></script>
</head>

<body class="font-sans text-gray-900 antialiased">
    {{-- محتوى الصفحة الرئيسي (slot) --}}
    {{ $slot }}
</body>
</html>
