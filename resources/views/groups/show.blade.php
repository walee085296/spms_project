<x-app-layout>
    {{-- ================= رأس الصفحة ================= --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{-- عنوان الصفحة: رقم المجموعة --}}
            مجموعة رقم #{{ $group->id }}
        </h2>
 @if ($group->developers->contains(auth()->user()) )
                
        <a href="{{ route('tasks.index') }}">
            <x-button class="text-xs" type="button">
                {{ __('viwe tasks') }}
            </x-button>
        </a>
        <a href="{{ route('projects.create') }}">
            <x-button class="text-xs" type="button">
                {{ __('create project') }}
            </x-button>
        </a>
       
            @endif
  
    </x-slot>

    {{-- ================= رسائل النجاح أو الأخطاء ================= --}}
    <div class="max-w-7xl mx-auto">
        <x-flash-message />
    </div>

    {{-- ================= القسم الرئيسي ================= --}}
    <div class="py-12 flex flex-col md:flex-row justify-center mx-auto gap-6">

        {{-- ================ أعضاء المجموعة ================ --}}
        <div class="max-w-7xl">
            <div class="bg-white overflow-hidden shadow-lg rounded-3xl">
                <div class="p-8 bg-white border-b border-gray-200">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-4">
                        أعضاء المجموعة
                    </h2>

                    {{-- عرض كل الأعضاء --}}
                    @foreach ($group->developers as $user)
                        <div class="mt-2 bg-gray-50 px-2 py-2 md:w-72 rounded-lg border border-gray-300 hover:bg-gray-100 mx-auto">
                            <div class="flex items-center">
                                {{-- صورة العضو --}}
                                <div class="flex-shrink-0 h-10 w-10">
                                    <a href="{{ route('users.show',$user->id) }}">
                                        <img class="h-10 w-10 rounded-full border border-gray-300"
                                            src="/uploads/avatars/{{ $user->avatar }}" alt="صورة العضو">
                                    </a>
                                </div>
                                {{-- اسم البريد الالكتروني --}}
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        <a href="{{ route('users.show',$user->id) }}">{{ $user->name }}</a>
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        <a href="{{ route('users.show',$user->id) }}">{{ $user->email }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    {{-- ========== زر طلب الانضمام للمجموعة ========== --}}
                    @if(!$group->developers->contains(auth()->user()))
                        @if (count($requested) == 0)
                            @can('create', App\Models\GroupRequest::class)
                                <a href="{{ route('requests.store',$group->id) }}"
                                    class="mt-2 py-2 bg-gray-50 px-2 flex justify-center rounded-lg font-semibold text-blue-700 border border-gray-300">
                                    إرسال طلب انضمام
                                </a>
                            @else
                                <span
                                    class="mt-2 py-2 bg-gray-50 px-2 flex justify-center rounded-lg font-semibold text-gray-500 hover:text-gray-700 cursor-pointer border border-gray-300">
                                    إرسال طلب انضمام
                                </span>
                            @endcan
                        @else
                            {{-- زر إلغاء طلب الانضمام --}}
                            <form method="POST" action="{{ route('requests.destroy',$group->id) }}">
                                @csrf
                                @method('DELETE')
                                <button
                                    class="mt-2 px-2 py-2 w-full focus:outline-none bg-gray-50 flex justify-center rounded-lg font-semibold text-blue-700 border border-gray-300">
                                    إلغاء طلب الانضمام
                                </button>
                            </form>
                        @endif
                   
                    @endif
                </div>
            </div>
        </div>

        {{-- ================ طلبات الانضمام (فقط إذا كنت عضو) ================ --}}
        @if($group->developers->contains(auth()->user()))
            <div class="flex flex-col items-center">
                <div class="max-w-7xl w-full">
                    <div class="bg-white overflow-hidden shadow-lg rounded-3xl">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <h2 class="font-semibold text-xl text-gray-800 leading-tight mt-2 mb-4 flex">
                               حاله المجموعه
                            </h2>

                          
                           
                                {{-- رسالة عند عدم وجود طلبات --}}
                                <div class="mt-2 bg-gray-50 flex justify-center py-4 md:w-72 rounded-lg border border-gray-300 hover:bg-gray-100">
                                    <div class="flex items-center text-gray-600">
                                        @if($group->state->name === 'Recruiting')
                                            لا توجد طلبات انضمام حالياً
                                        @else
                                            حالة المجموعة هي <span class="ml-1 capitalize text-green-500">{{ $group->state->value }}</span>
                                        @endif
                                    </div>
                                </div>
                          

                        </div>
                    </div>
                </div>
            </div>
        @endif

    </div>

    
</x-app-layout>
