<x-app-layout>

    {{-- ================= رأس الصفحة ================= --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{-- عنوان الصفحة --}}
            {{ __('مجموعة جديدة') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 ">
            {{-- ================= صندوق المحتوى ================= --}}
            <div class="bg-white shadow-lg sm:rounded-lg">
                <div class="rounded-lg p-6 bg-white border-b border-gray-200">
                    
                    {{-- رسائل النجاح أو الأخطاء --}}
                    <x-flash-message class="mb-4" :errors="$errors" />

                    {{-- ================= نموذج إنشاء مجموعة ================= --}}
                    <form method="POST" action="{{ route('groups.store') }}">
                        @csrf {{-- حماية ضد CSRF --}}
                        <div>
                            <div class="grid grid-row-3 gap-6 mt-4">
                                <div class="grid grid-cols-2 gap-6">

                                    {{-- حالة المجموعة --}}
                                    <div>
                                        <x-label for="state" :value="__('حالة المجموعة')" />
                                        <x-select id="state" name="state" class="capitalize mt-1 block w-full">
                                            @foreach ($states as $state)
                                                <option class="capitalize" value="{{ $state->value }}">
                                                    {{ $state->value }}
                                                </option>
                                            @endforeach
                                        </x-select>
                                    </div>

                                    {{-- تخصص المجموعة --}}
                                    <div>
                                        <x-label for="spec" :value="__('تخصص المجموعة')" />
                                        <x-select id="spec" name="spec" class="capitalize mt-1 block w-full">
                                            @foreach($specs as $spec)
                                                <option class="capitalize" value="{{ $spec->value }}" @selected($spec->value == old('spec'))>
                                                    {{ $spec->value }}
                                                </option>
                                            @endforeach
                                        </x-select>
                                    </div>

                                    {{-- دعوة أعضاء للمجموعة --}}
                                    <div>
                                        <x-label for="invited" :value="__('دعوة أعضاء')" />
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
                                        <x-select id="project_type" name="project_type" class="capitalize mt-1 block w-full">
                                            @foreach ($project_types as $type)
                                                <option class="capitalize" value="{{ $type->value }}">
                                                    {{ $type->value }}
                                                </option>
                                            @endforeach
                                        </x-select>
                                    </div>

                                </div>
                            </div>

                            {{-- زر إرسال النموذج --}}
                            <div class="flex items-center justify-end mt-4">
                                <x-button type="submit">
                                    {{ __('إنشاء') }}
                                </x-button>
                            </div>

                        </div>
                    </form>
                    {{-- ================= نهاية النموذج ================= --}}
                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
