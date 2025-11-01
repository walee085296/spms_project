<x-app-layout>

    {{-- ================= رأس الصفحة ================= --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{-- عنوان الصفحة --}}
            {{ __('المجموعات') }}
        </h2>

        {{-- ================= زر إنشاء مجموعة جديدة ================= --}}
        <div>
            @can('create', App\Models\Group::class)
                <a href="{{ route('groups.create') }}">
                    <x-button class="text-xs" type="button">
                        {{ __('إنشاء مجموعة جديدة') }}
                    </x-button>
                </a>
            @endcan
        </div>
    </x-slot>

    {{-- ================= فلتر / بحث ================= --}}
    <x-slot name="filters">
        <span>فلتر البحث:</span>
        <div class="relative mt-2 md:mt-0">
            <x-search />
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex flex-col">
            <div class="overflow-x-auto scrollbar-none sm:-mx-6 lg:-mx-8">
                <div class="align-middle inline-block min-w-full sm:px-6 lg:px-8">

                    {{-- عرض رسائل النجاح أو الأخطاء --}}
                    <x-flash-message />

                    {{-- ================= جدول المجموعات ================= --}}
                    <div class="shadow-lg overflow-hidden sm:rounded-lg border border-gray-300">
                        <table class="min-w-full divide-y divide-gray-200">
                            {{-- رأس الجدول --}}
                            <thead class="bg-gray-100">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-small text-gray-500 uppercase tracking-wider">
                                        رقم
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-small text-gray-500 uppercase tracking-wider">
                                        مشروع المجموعة
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-small text-gray-500 uppercase tracking-wider">
                                        الأعضاء
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-small text-gray-500 uppercase tracking-wider">
                                        الحالة
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-small text-gray-500 uppercase tracking-wider">
                                        التخصص
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-small text-gray-500 uppercase tracking-wider">
                                        نوع المشروع
                                    </th>
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">تعديل</span>
                                    </th>
                                </tr>
                            </thead>

                            {{-- جسم الجدول --}}
                            <tbody class="bg-white">
                                @forelse ($groups as $group)
                                    <tr class="border-b border-gray-200 align-text-top">

                                        {{-- رقم المجموعة --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <a class="text-indigo-500 hover:text-indigo-700" href="{{ route('groups.show',$group) }}">
                                                #{{ $group->id }}
                                            </a>
                                        </td>

                                        {{-- مشروع المجموعة --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            @if($group->project_id)
                                                <a class="text-indigo-500 hover:text-indigo-700" href="{{ route('projects.show',$group->project->id) }}">
                                                    {{ $group->project->title }}
                                                </a>
                                            @else
                                                لا يوجد مشروع بعد
                                            @endif
                                        </td>

                                        {{-- أعضاء المجموعة --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-indigo-500">
                                            <div class="flex flex-col justify-start">
                                                @foreach($group->developers as $user)
                                                    <a class="hover:text-indigo-700" href="{{ route('users.show',$user->id) }}">
                                                        {{ $user->name }}
                                                    </a>
                                                @endforeach
                                            </div>
                                        </td>

                                        {{-- حالة المجموعة --}}
                                        <td class="capitalize px-6 py-4 whitespace-nowrap text-sm 
                                            @if($group->state->value === 'full') text-red-600 
                                            @elseif($group->state->value === 'looking for members') text-green-500 
                                            @endif">
                                            {{ $group->state->value }}
                                        </td>

                                        {{-- تخصص المجموعة --}}
                                        <td class="capitalize px-6 py-4 whitespace-nowrap text-sm 
                                            text-red-600 
                                            @if($group->spec->value === 'mixed' || $group->spec->name === Auth::user()->spec->name) text-green-500 @endif">
                                            {{ $group->spec->value }}
                                        </td>

                                        {{-- نوع المشروع --}}
                                        <td class="capitalize px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $group->project_type->value ?? '---' }}
                                        </td>

                                        {{-- زر تعديل المجموعة --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            @can('edit', $group)
                                                <a href="{{ route('groups.edit',$group->id) }}" class="text-indigo-600 hover:text-indigo-900">
                                                    تعديل
                                                </a>
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    {{-- رسالة عند عدم وجود نتائج --}}
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            لا توجد نتائج
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- ================= الترقيم (Pagination) ================= --}}
                    <div class="py-8">
                        {!! $groups->links() !!}
                    </div>

                </div>
            </div>
        </div>
    </div>

</x-app-layout>
