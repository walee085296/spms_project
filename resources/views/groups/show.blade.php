<x-app-layout>
    {{-- ================= رأس الصفحة ================= --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{-- عنوان الصفحة: رقم المجموعة --}}
            مجموعة رقم #{{ $group->id }}
        </h2>
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
                    @else
                        {{-- زر مغادرة المجموعة --}}
                        <a href="{{ route('groups.leave',$group->id) }}">
                            <x-modal action="{{ __('مغادرة') }}" type="{{ __('button') }}">
                                <x-slot name="trigger">
                                    <button @click.prevent="showModal = true"
                                        class="mt-2 px-2 py-2 w-full bg-red-50 flex justify-center rounded-lg font-semibold text-red-700 border border-red-700 hover:border-red-500 hover:text-red-500 focus:outline-none">
                                        مغادرة المجموعة
                                    </button>
                                </x-slot>
                                <x-slot name="title">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                        تأكيد المغادرة
                                    </h3>
                                </x-slot>
                                <x-slot name="content">
                                    <p class="text-sm text-gray-500">
                                        هل أنت متأكد من مغادرة هذه المجموعة؟ لن تتمكن من التراجع عن هذا الإجراء.
                                    </p>
                                </x-slot>
                            </x-modal>
                        </a>
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
                                طلبات الانضمام
                            </h2>

                            @forelse($groupRequests as $groupRequest)
                                <div class="flex mt-2 bg-gray-50 px-2 py-2 md:w-80 rounded-lg border border-gray-300 hover:bg-gray-100 justify-between items-center">

                                    {{-- معلومات المرسل --}}
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <a href="{{ route('users.show',$groupRequest->sender->id) }}">
                                                <img class="h-10 w-10 rounded-full border border-gray-300"
                                                    src="/uploads/avatars/{{ $groupRequest->sender->avatar }}" alt="صورة العضو">
                                            </a>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                <a href="{{ route('users.show',$groupRequest->sender->id) }}">
                                                    {{ $groupRequest->sender->first_name }} {{ $groupRequest->sender->last_name }}
                                                </a>
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                <a href="{{ route('users.show',$groupRequest->sender->id) }}">
                                                    {{ $groupRequest->sender->email }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- قائمة قبول أو رفض الطلب --}}
                                    <div x-data="{ requestMenu:false }" @click="requestMenu = !requestMenu"
                                         @keydown.escape="requestMenu = false" @click.away="requestMenu = false">
                                        <button class="text-gray-400 bg-gray-200 focus:outline-none hover:bg-gray-300 rounded-full py-3 px-3">
                                            <svg fill="currentColor" width="24" height="6">
                                                <path d="M2.97.061A2.969 2.969 0 000 3.031 2.968 2.968 0 002.97 6a2.97 2.97 0 100-5.94zm9.184 0a2.97 2.97 0 100 5.939 2.97 2.97 0 100-5.939zm8.877 0a2.97 2.97 0 10-.003 5.94A2.97 2.97 0 0021.03.06z"></path>
                                            </svg>
                                        </button>

                                        <div x-show="requestMenu" class="absolute z-50 mt-2 bg-white rounded-lg shadow-lg w-52">
                                            <a href="{{ route('requests.accept',$groupRequest->id) }}"
                                               class="block px-4 py-2 text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100">
                                               قبول الطلب
                                            </a>
                                            <a href="{{ route('requests.reject',$groupRequest->id) }}"
                                               class="block px-4 py-2 text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100">
                                               رفض الطلب
                                            </a>
                                        </div>
                                    </div>

                                </div>
                            @empty
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
                            @endforelse

                        </div>
                    </div>
                </div>
            </div>
        @endif

    </div>
</x-app-layout>
