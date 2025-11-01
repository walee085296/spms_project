<x-app-layout>

    {{-- ================= رأس الصفحة ================= --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{-- عنوان الصفحة مع رقم المجموعة --}}
            {{ __('تعديل المجموعة') }} #{{ $group->id }}
        </h2>

        {{-- ================= زر حذف المجموعة (مسموح للمخولين فقط) ================= --}}
        @can('destroy',$group)
            <form method="POST" action="{{route('groups.destroy', $group->id)}}">
                @csrf
                @method('DELETE')

                {{-- مكون المودال لتأكيد الحذف --}}
                <x-modal>
                    {{-- زر فتح المودال --}}
                    <x-slot name="trigger">
                        <x-button type="button" class="bg-red-600 hover:bg-red-500" @click="showModal = true" value="Click Here">
                            حذف المجموعة
                        </x-button>
                    </x-slot>

                    {{-- عنوان المودال --}}
                    <x-slot name="title">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            حذف المجموعة
                        </h3>
                    </x-slot>

                    {{-- محتوى المودال --}}
                    <x-slot name="content">
                        <p class="text-sm text-gray-500">
                            هل أنت متأكد من رغبتك في حذف المجموعة رقم #{{ $group->id }}؟
                            جميع البيانات المتعلقة بها ستتم إزالتها بشكل دائم. لا يمكن التراجع عن هذا الإجراء.
                        </p>
                    </x-slot>
                </x-modal>
            </form>
        @endcan
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- ================= صندوق المحتوى ================= --}}
            <div class="bg-white shadow-lg rounded-lg">
                <div class="rounded-lg p-6 bg-white border-b border-gray-200">

                    {{-- عرض رسائل النجاح أو الأخطاء --}}
                    <x-flash-message class="mb-4" :errors="$errors" />

                    {{-- ================= نموذج تعديل المجموعة ================= --}}
                    <form method="POST" action="{{ route('groups.update',$group->id) }}">
                        @method('PUT') {{-- لتحديث الموارد --}}
                        @csrf

                        <div>
                            <div class="grid grid-row-3 gap-6 mt-4">
                                <div class="grid grid-cols-2 gap-6">

                                    {{-- حالة المجموعة --}}
                                    <div>
                                        <x-label for="state" :value="__('حالة المجموعة')" />
                                        <select id="state" name="state"
                                            class="capitalize mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 sm:text-sm">
                                            @foreach ($states as $state)
                                                <option @selected($state == $group->state) class="capitalize" value="{{ $state->value }}">
                                                    {{ $state->value }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- تخصص المجموعة --}}
                                    <div>
                                        <x-label for="spec" :value="__('تخصص المجموعة')" />
                                        <select id="spec" name="spec"
                                            class="capitalize mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 sm:text-sm">
                                            @foreach($specs as $spec)
                                                <option @selected($spec == $group->spec) class="capitalize" value="{{ $spec->value }}">
                                                    {{ $spec->value }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- دعوة أعضاء للمجموعة --}}
                                    <div>
                                        <x-label for="invite_members" :value="__('دعوة أعضاء')" />
                                        <x-multi-select-dropdown placeholder="اختر الأعضاء" name="invited" class="p-1 mt-1">
                                            <x-slot name="options">
                                                @foreach($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                @endforeach
                                            </x-slot>
                                        </x-multi-select-dropdown>
                                    </div>

                                    {{-- نوع المشروع --}}
                                    <div>
                                        <x-label for="project_type" :value="__('نوع المشروع')" />
                                        <select id="project_type" name="project_type"
                                            class="capitalize mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 sm:text-sm">
                                            @foreach($project_types as $type)
                                                <option @selected($type == $group->project_type) class="capitalize" value="{{ $type->value }}">
                                                    {{ $type->value }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>
                            </div>

                            {{-- زر تحديث المجموعة --}}
                            <div class="flex items-center justify-end mt-4">
                                <x-button class="ml-3">
                                    {{ __('تحديث') }}
                                </x-button>
                            </div>

                        </div>
                    </form>
                    {{-- ================= نهاية نموذج التعديل ================= --}}

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
