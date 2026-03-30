<x-app-layout>
  
      <x-slot name="header">
        <!-- رأس الصفحة -->
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tasks') }} <!-- عنوان الصفحة -->
        </h2>

        <!-- زر إنشاء مشروع جديد يظهر فقط إذا كان للمستخدم صلاحية create -->
        @can('project-approve')
        <a href="{{ route('tasks.create') }}">
            <x-button class="text-xs" type="button">
                {{ __('Create New task') }}
            </x-button>
        </a>
        @endcan
    </x-slot>



       <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex flex-col">
            <div class="-my-2 overflow-x-auto ...">
                <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                    <x-flash-message /> <!-- رسائل نجاح / أخطاء -->

                    <div class="shadow-lg overflow-hidden border border-gray-300 sm:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <!-- رؤوس الأعمدة -->
                                     @can('project-create')
                                    <th>Task ID </th>
                                    @endcan
                                     @can('project-approve')
                                    <th>Project ID</th>
                                    @endcan
                                    <th>Project Title</th>
                                     <th>Created At</th>
                                    <th>Task Status</th>
                                </tr>
                            </thead>

                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($tasks as $task)
                                    <tr>
                                        <td> 
                                               @can('project-create')
                                         <a href="{{ route('tasks.show', $task->id) }}">{{  $task->id }}</a></td> @endcan
                                          @can('project-approve')
                                         <a href="{{ route('tasks.show', $task->id) }}">{{ $task->project->id }}</a>@endcan
                                        <td>{{ $task->project->title ?? 'N/A' }}</td>
                                        <td>{{ $task->created_at }}</td>
                                        <td>
                                            
                                                
                                            
                                            <form method="POST" action="{{ route('tasks.state',$task->id) }}">
@csrf
<input 
type="checkbox"
name="state"
value= "1"


disabled

{{ $task->state ? 'checked' : '' }}
class="form-checkbox h-5 w-5 text-green-600"
>

</form>

                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>

              </div>
            </div>
        </div>





</x-app-layout>    