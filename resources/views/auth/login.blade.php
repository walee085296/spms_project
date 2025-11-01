<x-guest-layout>
    <x-auth-card>
        <!-- شعار الموقع -->
        <x-slot name="logo">
            <a href="/">
                <x-application-logo class="w-40 h-40 fill-current text-gray-400" />
            </a>
        </x-slot>

        <!-- عرض حالة الجلسة (مثلاً بعد إعادة تعيين كلمة المرور) -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- عرض أخطاء التحقق من الإدخال -->
        <x-flash-message class="mb-4" :errors="$errors" />

        <!-- نموذج تسجيل الدخول -->
        <form method="POST" action="{{ route('login') }}">
            @csrf <!-- حماية CSRF -->

            <!-- البريد الإلكتروني -->
            <div>
                <x-label for="email" :value="__('البريد الإلكتروني')" />

                <x-input id="email" class="block mt-1 w-full" type="email" name="email"
                    :value="old('email')" placeholder="أدخل بريدك الإلكتروني" required autofocus />
            </div>

            <!-- كلمة المرور مع زر إظهار/إخفاء -->
            <div x-data="{ show: true }" class="mt-4">
                <x-label for="password" :value="__('كلمة المرور')" />
                <div class="flex">
                    <!-- حقل كلمة المرور -->
                    <x-input id="password" class="mt-1 w-full rounded-r-none"
                        x-bind:type="show ? 'password' : 'text'" name="password"
                        placeholder="أدخل كلمة المرور" required autocomplete="current-password" />

                    <!-- أيقونة الإظهار/الإخفاء -->
                    <span @click.prevent="show = !show"
                        class="rounded-r-md h-10 mt-1 w-12 bg-gray-800 flex justify-center items-center text-gray-100">
                        <i class="fa" x-bind:class="show ? 'fa-eye' : 'fa-eye-slash'"></i>
                    </span>
                </div>
            </div>

            <!-- خيار تذكرني -->
            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox"
                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        name="remember">
                    <span class="ml-2 text-sm text-gray-600">تذكرني</span>
                </label>
            </div>

            <!-- رابط نسيت كلمة المرور -->
            <div class="flex items-center justify-end mt-4 space-x-2">
                @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900"
                    href="{{ route('password.request') }}">
                    نسيت كلمة المرور؟
                </a>
                @endif
            </div>

            <!-- أزرار تسجيل الدخول -->
            <div class="block space-y-2 mt-2">
                <!-- زر تسجيل الدخول التقليدي -->
                <x-button class="block w-full justify-center">
                    تسجيل الدخول <i class="fas fa-sign-in-alt ml-2"></i>
                </x-button>

                <!-- زر تسجيل الدخول باستخدام GitHub -->
                <a class="flex justify-center items-center w-full py-2 bg-gray-800 border border-transparent rounded-md font-bold text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 text-sm"
                    href="{{ url('auth/github') }}">
                    تسجيل الدخول باستخدام GitHub <i class="fab fa-github ml-2"></i>
                </a>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
