@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">إنشاء تنبيه جديد</h2>

    @if (session('success'))
        <div style="padding: 10px; background:#d4edda; color:#155724; border-radius:5px;">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('lecturer.notifications.store') }}" method="POST">
        @csrf

        <label>عنوان التنبيه:</label>
        <input type="text" name="title" class="form-control" required>

        <label class="mt-3">محتوى التنبيه:</label>
        <textarea name="message" class="form-control" rows="5" required></textarea>

        <button class="btn btn-primary mt-3">نشر التنبيه</button>
    </form>
</div>
@endsection

