<x-app-layout>
    <!-- رأس الصفحة -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            لوحة التحكم
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
           
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                
                <!-- المحتوى الرئيسي للوحة التحكم -->
                <div class="flex-1 p-8 bg-gray-100">
                    <!-- عرض رسائل النجاح أو التنبيهات -->
                    <x-flash-message />

                    <!-- بطاقة المحتوى -->
                    <div class="bg-white shadow rounded-lg p-6">
                        <!-- عنوان البطاقة -->
                        <h3 class="text-xl font-semibold mb-4">
                            مرحبًا بك في لوحة تحكم المتطورة
                        </h3>

                        <!-- يمكن إضافة محتوى إضافي هنا مثل إحصائيات أو روابط سريعة -->
                    </div>
                </div>

                <!-- شعار أو صورة على اللوحة -->
                <div class="p-6 flex justify-center">
                    <!-- ملاحظة: يفضل استخدام رابط نسبي أو asset بدل المسار المحلي D:\... -->
                    <img src="{{ asset('images/Logo.png') }}" alt="شعار المتطورة" class="h-32 w-auto"/>
                </div>
                
            </div>
        </div>
    </div>
</x-app-layout>
