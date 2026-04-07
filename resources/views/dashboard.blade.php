<x-app-layout>

<x-slot name="header">
    <h2 class="text-xl font-semibold">Dashboard المشرف</h2>
</x-slot>

<div class="p-6">

    {{-- 📊 الإحصائيات --}}
    <div class="grid grid-cols-4 gap-4 mb-6">

        <div class="bg-gray-100 p-4 rounded">
            كل التاسكات: {{ $stats['all'] }}
        </div>

        <div class="bg-yellow-100 p-4 rounded">
            Pending: {{ $stats['pending'] }}
        </div>

        <div class="bg-green-100 p-4 rounded">
            Completed: {{ $stats['completed'] }}
        </div>

        <div class="bg-red-100 p-4 rounded">
            Rejected: {{ $stats['rejected'] }}
        </div>

    </div>

    {{-- 📋 جدول التاسكات --}}
    <table class="w-full border">
        <thead>
            <tr class="bg-gray-200">
                <th>ID</th>
                <th>Project</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            @foreach($tasks as $task)
            <tr class="border-b text-center">

                <td>{{ $task->id }}</td>

                <td>{{ $task->project->name ?? '-' }}</td>

                <td>
                    @if($task->state == 0)
                        <span class="text-yellow-600">Pending</span>
                    @elseif($task->state == 1)
                        <span class="text-green-600">Completed</span>
                    @else
                        <span class="text-red-600">Rejected</span>
                    @endif
                </td>

                <td class="flex justify-center gap-2">

                    {{-- Approve --}}
                    <form method="POST" action="{{ route('tasks.state', $task->id) }}">
                        @csrf
                        <input type="hidden" name="state" value="1">
                        <button class="bg-green-500 text-white px-2 py-1 rounded">✔</button>
                    </form>

                    {{-- Reject --}}
                    <form method="POST" action="{{ route('tasks.state', $task->id) }}">
                        @csrf
                        <input type="hidden" name="state" value="2">
                        <button class="bg-red-500 text-white px-2 py-1 rounded">✖</button>
                    </form>

                </td>

            </tr>
            @endforeach
        </tbody>
    </table>

</div>

</x-app-layout>