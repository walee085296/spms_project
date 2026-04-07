<x-app-layout>

<x-slot name="header">
    @can('project-approve')
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Dashboard المشرف
    </h2>
    @endcan
    @can('project-create')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Dashboard الطالب
    </h2>
    @endcan
    <!-- زر إنشاء مشروع جديد يظهر فقط إذا كان للمستخدم صلاحية create -->
 @can('project-approve')
 <a href="{{ route('tasks.create') }}">
 <x-button class="text-xs" type="button">
 {{ __('Create New task') }}
 </x-button>
 </a>
 @endcan
</x-slot>


 

@php $state = request('state'); @endphp

<div class="p-6">
     @can('project-approve')
         
    
    {{--  الإحصائيات (Clickable) --}}
    <div class="grid grid-cols-4 gap-4 mb-6">

        <a href="{{ route('tasks.index') }}"
           class="p-4 rounded block text-center {{ is_null($state) ? 'ring-2 ring-gray-500 bg-gray-200' : 'bg-gray-100' }}">
            ALL <br>{{ $stats['all'] }}
        </a>

        <a href="{{ route('tasks.index', ['state' => 0]) }}"
           class="p-4 rounded block text-center {{ $state == 0 ? 'ring-2 ring-yellow-500 bg-yellow-200' : 'bg-yellow-100' }}">
            Pending <br>{{ $stats['pending'] }}
        </a>

        <a href="{{ route('tasks.index', ['state' => 1]) }}"
           class="p-4 rounded block text-center {{ $state == 1 ? 'ring-2 ring-green-500 bg-green-200' : 'bg-green-100' }}">
            Completed <br>{{ $stats['completed'] }}
        </a>

        <a href="{{ route('tasks.index', ['state' => 2]) }}"
           class="p-4 rounded block text-center {{ $state == 2 ? 'ring-2 ring-red-500 bg-red-200' : 'bg-red-100' }}">
            Rejected <br>{{ $stats['rejected'] }}
        </a>

    </div> @endcan
    

    {{-- 📋 جدول التاسكات --}}
    <div class="shadow-lg overflow-hidden border border-gray-300 sm:rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">

            <thead class="bg-gray-100">
                <tr> <!-- رؤوس الأعمدة --> @can('project-create') <th>Task ID </th> @endcan @can('project-approve') <th>Project ID</th> @endcan <th>Project Title</th> <th>Created At</th> <th>Task Status</th> </tr>
            </thead>

            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($tasks as $task)
                    <tr class="text-center">

                        <td>
                            <a href="{{ route('tasks.show', $task->id) }}" class="text-blue-600">
                                {{ $task->id }}
                            </a>
                        </td>

                        <td>
                            {{ $task->project->title ?? 'N/A' }}
                        </td>

                        <td>
                            {{ $task->created_at }}
                        </td>

                        <td>
                            @if($task->state === 0)
                                <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-800 rounded">
                                    Pending
                                </span>
                            @elseif($task->state === 1)
                                <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded">
                                    Completed
                                </span>
                            @elseif($task->state === 2)
                                <span class="px-2 py-1 text-xs bg-red-100 text-red-800 rounded">
                                    Rejected
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs bg-gray-100 text-gray-800 rounded">
                                    Unknown
                                </span>
                            @endif
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center p-4">
                            لا توجد بيانات
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>
    </div>

</div>

</x-app-layout>