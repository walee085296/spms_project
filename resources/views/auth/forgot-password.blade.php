<x-guest-layout>
    <x-auth-card>
        <!-- شعار الموقع -->
        <x-slot name="logo">
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </x-slot>

        <!-- نص توضيحي لصفحة إعادة تعيين كلمة المرور -->
        <div class="mb-4 text-sm text-gray-600">
            <!-- هذا النص يشرح للمستخدم ماذا يفعل في حالة نسيان كلمة المرور -->
            نسيت كلمة المرور؟ لا تقلق. فقط قم بإدخال بريدك الإلكتروني وسنرسل لك رابطًا لإعادة تعيين كلمة المرور يمكنك من اختيار كلمة مرور جديدة.
        </div>

        <!-- عرض حالة الجلسة (مثلاً بعد إرسال رابط إعادة التعيين) -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- عرض أخطاء التحقق من الإدخال -->
        <x-flash-message class="mb-4" :errors="$errors" />

        <!-- نموذج إرسال البريد الإلكتروني لإعادة تعيين كلمة المرور -->
        <form method="POST" action="{{ route('password.email') }}">
            @csrf <!-- حماية CSRF -->

            <!-- حقل البريد الإلكتروني -->
            <div>
                <x-label for="email" :value="__('البريد الإلكتروني')" />

                <x-input id="email" class="block mt-1 w-full" type="email" name="email"
                    :value="old('email')" placeholder="أدخل بريدك الإلكتروني" required autofocus />
            </div>

            <!-- زر إرسال رابط إعادة التعيين -->
            <div class="flex items-center justify-end mt-4">
                <x-button>
                    إرسال رابط إعادة تعيين كلمة المرور
                </x-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
