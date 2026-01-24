<x-app-layout>

@section('content')
<div class="container">
    <h2 class="mb-4">التنبيهات العامة</h2>

    @foreach($notifications as $note)
        <div style="padding:15px; margin-bottom:10px; border:1px solid #ccc; border-radius:7px;">
            <h4>{{ $note->title }}</h4>
            <p>{{ $note->message }}</p>
            <small>تاريخ: {{ $note->created_at->format('Y-m-d') }}</small>
        </div>
    @endforeach

</div>
@endsection
</x-app-layout>