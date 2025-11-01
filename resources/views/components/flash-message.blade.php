{{-- التحقق من وجود أخطاء في النموذج أو رسالة خطأ من الجلسة --}}
@if ($errors->any() || session('error')) 

<div class="alert flex flex-row items-center bg-red-200 border-red-700 border p-5 rounded-lg my-5 mb-4"
    x-data="{ show: false }" {{-- إنشاء متغير Alpine.js للتحكم بعرض الإشعار --}}
    @keydown.escape="show = false" {{-- إخفاء الإشعار عند الضغط على زر Escape --}}
    x-init="() => {
        setTimeout(() => show = true, 500); {{-- إظهار الإشعار بعد نصف ثانية --}}
        setTimeout(() => show = false, 50000); {{-- إخفاء الإشعار بعد 50 ثانية --}}
    }" 
    x-show="show" {{-- شرط عرض الإشعار --}}
    x-description="Notification panel, show/hide based on alert state." 
    x-transition:enter="transition ease-out duration-300" {{-- تأثير دخول الإشعار --}}
    x-transition:enter-start="opacity-0 transform scale-90" {{-- البداية: شفاف وصغير --}}
    x-transition:enter-end="opacity-100 transform scale-100" {{-- النهاية: مرئي وكامل الحجم --}}
    x-transition:leave="transition ease-in duration-300" {{-- تأثير خروج الإشعار --}}
    x-transition:leave-start="opacity-100 transform scale-100" 
    x-transition:leave-end="opacity-0 transform scale-90">

    {{-- أيقونة الإشعار --}}
    <div class="alert-icon flex items-center bg-red-100 border-2 border-red-500 justify-center h-10 w-10 flex-shrink-0 rounded-full">
        <span class="text-red-500">
            <svg fill="currentColor" viewBox="0 0 20 20" class="h-6 w-6">
                <path fill-rule="evenodd"
                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                    clip-rule="evenodd"></path>
            </svg>
        </span>
    </div>

    {{-- محتوى الإشعار --}}
    <div class="alert-content ml-4">
        <div class="alert-title font-semibold text-lg text-red-800">
            {{ __('Whoops, something went wrong') }} {{-- عنوان الخطأ --}}
        </div>
        <div class="
