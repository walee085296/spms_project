<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Task Details') }}
        </h2>
    </x-slot>  

    <div class="bg-white overflow-hidden shadow-lg rounded-3xl">
        <div class="bg-white border-b border-gray-200">
            <div class="p-8 bg-white text-gray-800">
                <div class="space-y-4 p-2">
                    {{-- عرض الأهداف (Aims) --}}
                    <div class="border-b border-gray-300 pb-4">
                        <h1 class="font-semibold text-base text-gray-800 leading-tight pb-2">التاسك</h1>

                        {{-- @php
                            $aims = $task->desc ;
                        @endphp --}}

                        <ol class="list-disc list-inside">
                           
                                <li class="flex justify-between items-center text-sm py-0.5">
                                    <span class="{{ $task->desc }}">
                                        {{ $task->desc }}
                                    </span>
                           <form method="POST" action="{{ route('tasks.state',$task->id) }}">
@csrf
                          <input 
type="checkbox"
name="state"
value= "1"
@can('project-approve')
onchange="this.form.submit()"@endcan
@can('project-create')
disabled
    
@endcan
{{ $task->state ? 'checked' : '' }}
class="form-checkbox h-5 w-5 text-green-600"
> </form>
                                </li>
                          
                        </ol>
                    </div>

                    {{-- رابط التاسك --}}
                    <div class="border-b border-gray-300 pb-4">
                        <h1 class="font-semibold text-base text-gray-800 leading-tight pb-2">الرابط</h1>
                        <p class="text-sm text-gray-700 col-span-2 pt-2">
                            {{ $task->url ?? 'لا يوجد رابط بعد' }}
                        </p>
                        @if($task->url)
                            <x-nav-link href="{{ $task->url }}" target="_blank" class="text-sm text-blue-600">
                                {{ __('Open Link') }}
                            </x-nav-link>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
 @can('project-create')
    
 
    {{-- Form لإضافة / تعديل رابط التاسك --}}
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <x-flash-message class="mb-4" :errors="$errors" />

                    <form method="POST" action="{{ route('tasks.addtask', $task->id) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="py-8 border-b border-gray-300">
                            <x-label class="mb-2" for="url" :value="__('Add / Edit Task Link')" />
                            <x-input 
                                type="text"
                                name="url"
                                id="url"
                                placeholder="Task URL"
                                value="{{ $task->url ?? '' }}"
                                class="w-full"
                            />
                        </div>

                        <div class="pt-8 flex @can('project-create'){ justify-between }@else{ justify-end }@endcan">
                            <x-button>
                                {{ __('حفظ') }}
                            </x-button>
                        </div>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
    @endcan
</x-app-layout>