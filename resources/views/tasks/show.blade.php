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
                                    @can('project-approve')
                                             
                                    <form method="POST" action="{{ route('tasks.state',$task->id) }}">
                                                @csrf
                                                @if ($task->state === 0)
                                                      <input type="hidden" name="state" value="{{ $task->state === 0 ? 1 : 1 }} "> 
                                                <x-button class="text-xs">
                                                    {{ $task->state === 0 ? __('Mark as Completed') : '' }}
                                                </x-button>
                                                 
                                                @endif

                                                 @if ($task->state === 2)
                                                      <input type="hidden" name="state" value="{{ $task->state === 0 ? 1 : 1 }} "> 
                                                <x-button class="text-xs">
                                                    {{ $task->state === 2 ? __('Mark as Completed') : '' }}
                                                </x-button>
                                                 
                                                @endif 

                                                 @if ( $task->state === 0)
                                                     <input type="hidden" name="state" value="{{ $task->state === 0? 2: 2 }}"> 
                                                <x-button class="text-xs">
                                                    {{ $task->state === 0 ? __('Mark as Rejected') : '' }}
                                                </x-button>
                                                @endif
                                    </form>
                                      

                                    @endcan
                                      @if($task->state === 0)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    {{ __('Pending') }}
                                                </span>
                                            @elseif($task->state === 1)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    {{ __('Approved') }}
                                                </span>
                                            @elseif($task->state === 2)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    {{ __('Rejected') }}
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                    {{ __('Unknown') }}
                                                </span>
                                            @endif
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
                     <div class="border-b border-gray-300 pb-4">
                        <h1 class="font-semibold text-base text-gray-800 leading-tight pb-2">الملاحظات</h1>

                    <li class="flex justify-between items-center text-sm py-0.5">
                                    <span class="{{ $task->desc }}">
                                        {{ $task->comment }}
                                    </span>
                                 </li>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
 @can('project-create')
    {{-- Form لإضافة / تعديل رابط التاسك --}}
     @if ($task->state !== 1)
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
     @endif
    @endcan

    @if ($task->state === 2)
    {{-- Form لإضافة تعليق على التاسك --}} 
    @can('project-approve')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <x-flash-message class="mb-4" :errors="$errors" />

                    <form method="POST" action="{{ route('tasks.addcomment', $task->id) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="py-8 border-b border-gray-300">
                            <x-label class="mb-2" for="comment" :value="__('Add Comment')" />
                            <textarea 
                                name="comment"
                                id="comment"
                                placeholder="Write your comment here..."
                                class="w-full border-gray-300 rounded-md shadow-sm"
                                rows="4"
                            ></textarea>

                        </div>

                        <div class="pt-8 flex justify-end">
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
    @endif
</x-app-layout>