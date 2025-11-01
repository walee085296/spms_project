<x-app-layout>
    <!-- رأس الصفحة -->
    <x-slot name="header">
        <div class="container mx-auto flex flex-row items-center justify-between">
            <!-- عنوان الصفحة -->
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                ملفي الشخصي
            </h2>

            <!-- زر تعديل الملف الشخصي -->
            <div class="mt-2">
                <a href="{{ route('profile.edit') }}">
                    <x-button type="button">
                        تعديل الملف الشخصي
                    </x-button>
                </a>
            </div>
        </div>
    </x-slot>

    <!-- رسائل النجاح والأخطاء -->
    <div class="max-w-7xl mx-auto">
        <x-flash-message class="mb-4" :errors="$errors" />
    </div>

    <!-- المحتوى الرئيسي -->
    <div class="py-12 flex container justify-center gap-6">

        <!-- قسم بيانات المستخدم -->
        <div class="max-w-7xl">
            <div class="bg-white overflow-hidden shadow-lg rounded-3xl">
                <div class="p-6 bg-white border-b border-gray-200">

                    <!-- صورة الملف الشخصي -->
                    <img src="/uploads/avatars/{{ $user->avatar }}"
                        class="w-full md:h-96 md:w-96 rounded-full border-2 border-gray-300" alt="صورة المستخدم">

                    <!-- معلومات أساسية حول المستخدم -->
                    <div class="flex flex-col md:flex-row container justify-between border-b border-gray-300 py-4">
                        <!-- اسم المستخدم -->
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight mt-2 flex items-center">
                            {{ $user->name }}
                        </h2>

                        <!-- آخر تسجيل دخول وعنوان IP -->
                        <div class="text-xs text-gray-500 mt-3 md:ml-4">
                            <div class="container flex flex-row justify-between md:w-36">
                                <div>آخر تسجيل دخول:</div>
                                <div>
                                    @if($user->last_login_at)
                                        {{ $user->last_login_at->diffForHumans() }}
                                    @else
                                        لم يتم تسجيل الدخول بعد
                                    @endif
                                </div>
                            </div>
                            <div class="container flex flex-row justify-between md:w-36">
                                <div>من عنوان IP:</div>
                                <div>{{ $user->last_login_ip ?? 'غير متوفر' }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- البريد الإلكتروني والرقم التسلسلي -->
                    <div class="grid grid-cols-2 grid-rows-1 border-b border-gray-300 w-full py-4 items-center">
                        <div class="text-xs text-gray-800">{{ $user->email }}</div>
                        <div class="text-xs text-gray-800 flex justify-end">{{ $user->stdsn }}</div>
                    </div>

                    <!-- حساب GitHub -->
                    <div class="grid grid-cols-2 grid-rows-1 border-gray-500 w-full items-center mt-3">
                        <div class="text-xs text-gray-800">حساب GitHub</div>
                        <div class="text-xs mt-3 flex justify-end">
                            @if (!$git)
                                <span class="text-gray-800">
                                    قم بربط حساب GitHub الخاص بك 
                                    <a href="{{ route('auth.git') }}" class="text-blue-600">من هنا</a>
                                </span>
                            @else
                                <a class="text-blue-600" href="https://github.com/{{ $git->nickname }}" target="_blank">
                                    {{ '@'.$git->nickname }}
                                </a>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- قسم المجموعات والمشاريع الخاصة بالمستخدم -->
        <div class="flex flex-col items-center justify-start">

            <!-- المجموعات -->
            <div class="max-w-7xl w-full md:w-96">
                <div class="bg-white overflow-hidden shadow-lg rounded-3xl">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight mt-2 flex items-center">
                            مجموعاتي
                        </h2>

                        <!-- المجموعة الحالية -->
                        <div class="flex flex-col md:flex-row justify-between py-4 border-b border-gray-300">
                            <div class="text-sm text-gray-800">المجموعة الحالية</div>
                            <div class="text-sm text-gray-500">
                                @if($user->group)
                                    <a class="text-indigo-500 hover:text-indigo-700" href="{{ route('groups.show',$user->group) }}">
                                        #{{ $user->group->id }}
                                    </a>
                                @else
                                    لا يوجد
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- المشاريع -->
            <div class="max-w-7xl w-full md:w-96 mt-4">
                <div class="bg-white overflow-hidden shadow-lg rounded-3xl">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight mt-2 flex items-center">
                            مشاريعي
                        </h2>

                        <!-- المشروع الحالي -->
                        <div class="flex flex-col md:flex-row justify-between py-4 border-b border-gray-300">
                            <div class="text-sm text-gray-800">المشروع الحالي</div>
                            <div class="text-sm text-gray-500">
                                @if($user->group && $user->project)
                                    <a class="text-indigo-500 hover:text-indigo-700" href="{{ route('projects.show',$user->project) }}">
                                        {{ $user->project->title }}
                                    </a>
                                @else
                                    لا يوجد
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
