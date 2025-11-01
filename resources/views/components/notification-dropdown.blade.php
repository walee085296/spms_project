<div x-data="{ dropdownOpen: false }" class="my-32 mr-4">
    {{-- زر الإشعارات --}}
    <button @click="dropdownOpen = !dropdownOpen" @click.away="dropdownOpen = false"
        class="relative z-10 block rounded-full focus:bg-gray-200 p-2 focus:outline-none text-gray-200 hover:bg-gray-600 focus:text-gray-800 transition duration-200 ease-in-out group">
        
        {{-- أيقونة الجرس --}}
        <svg class="h-7 w-7" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path
                d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z" />
        </svg>

        {{-- عدد الإشعارات غير المقروءة --}}
        @if(auth()->user()->unreadNotifications()->count() > 0)
            <span
                class="absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-1 text-xxs font-bold leading-none text-red-100 transform translate-x-1/4 -translate-y-1/4 bg-red-600 rounded-full animate-pulse animate-bounce group-focus:animate-none">
                {{ auth()->user()->unreadNotifications()->count() }}
            </span>
        @endif
    </button>

    {{-- قائمة الإشعارات المنسدلة --}}
    <div x-show="dropdownOpen"
        class="absolute right-0 mt-2 bg-white border border-gray-200 rounded-lg overflow-hidden shadow-xl z-20 w-screen md:w-2/6 md:mr-24"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        style="display: none;">

        {{-- رأس القائمة: عنوان + رابط "تحديد الكل كمقروء" --}}
        <div class="flex justify-between items-center py-3 px-4 border-b border-gray-200">
            <div class="text-gray-700 font-semibold">الاشعارات</div>
            <a class="text-xs text-blue-700 font-bold" href="{{ route('markAllRead') }}">Mark all as read</a>
        </div>

        {{-- محتوى الإشعارات --}}
        <div class="max-h-72 overflow-x-hidden overflow-y-auto scrollbar-thin scrollbar-track-transparent scrollbar-thumb-gray-300 scrollbar-thumb-rounded">
            
            {{-- عرض كل إشعار --}}
            @forelse (auth()->user()->notifications as $notification)
                <div class="py-2 pl-1 border-b border-gray-300 hover:bg-gray-200 @if(!$notification->read_at) bg-gray-100 font-bold @endif">
                    <div class="flex justify-between items-center py-3">
                        
                        {{-- صورة المستخدم المرسل للإشعار --}}
                        <a href="{{ route('users.show',$notification->data['user']['id']) }}">
                            <img class="h-10 w-10 rounded-full object-cover mx-1 border border-gray-200"
                                src="/uploads/avatars/{{ $notification->data['user']['avatar'] }}" alt="profile">
                        </a>

                        {{-- نص الإشعار --}}
                        <p class="text-gray-600 text-sm mx-2 md:flex-none">
                            <span class="font-bold text-blue-700">{{ $notification->data['user']['first_name'] }} {{ $notification->data['user']['last_name'] }}</span>
                            <a href="{{ route('notifications.show', $notification->id) }}">
                                {{ $notification->data['notify'] }}
                                <span class="font-bold text-blue-700"> {{ $notification->data['action'] }} </span>
                                <div class="text-xxs text-gray-500 font-semibold">
                                    {{ $notification->created_at->diffForHumans() }}
                                </div>
                            </a>
                        </p>
                    </div>
                </div>
            @empty
                {{-- إذا لم يكن هناك إشعارات --}}
                <div class="flex flex-row items-start justify-center text-gray-500 py-4">لا توجد اشعارات </div>
            @endforelse
        </div>

        {{-- رابط أسفل القائمة: عرض كل الإشعارات --}}
        <a href="#"
            class="block bg-gray-800 hover:bg-gray-600 text-gray-200 text-center font-semibold py-3 rounded-b-xl">
          عرض جميع الاشعارات
        </a>
    </div>
</div>
