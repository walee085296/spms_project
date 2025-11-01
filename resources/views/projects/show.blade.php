<x-app-layout> {{-- يستخدم هذا المكون لتغليف الصفحة بالكامل ضمن تصميم التطبيق الأساسي (App Layout) --}}

    <x-slot name="header"> {{-- هذا القسم مخصص لرأس الصفحة --}}

        <h2 class="font-semibold text-xl text-gray-800 leading-tight px-4">
            {{ $project->title }} {{-- يعرض عنوان المشروع الحالي --}}
        </h2>

        <div class="flex space-x-2"> {{-- حاوية للأزرار (الموافقة، الرفض، إتمام المشروع) مصفوفة أفقياً مع مسافات بينهما --}}

            @can('project-approve') {{-- يتحقق من إذن المستخدم "الموافقة على المشروع" --}}
            <form method="GET" action="{{route('projects.approve', $project->id)}}">
                @csrf {{-- حماية CSRF --}}
                <x-modal action="الموافقة" type="approve"> {{-- مكون مودال للموافقة --}}
                    <x-slot name="trigger">
                        <x-button class="text-xs" type="button" @click="showModal = true" value="Click Here">
                            الموافقة على المشروع {{-- زر يفتح المودال --}}
                        </x-button>
                    </x-slot>
                    <x-slot name="title">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            الموافقة على المشروع {{-- عنوان المودال --}}
                        </h3>
                    </x-slot>
                    <x-slot name="content">
                        <p class="text-sm text-gray-500">
                            هل أنت متأكد أنك تريد الموافقة على {{ $project->title }}؟ {{-- محتوى المودال: تأكيد العملية --}}
                        </p>
                    </x-slot>
                </x-modal>
            </form>

            <form method="GET" action="{{route('projects.disapprove', $project->id)}}">
                @csrf {{-- حماية CSRF --}}
                <x-modal action="رفض المشروع"> {{-- مودال لرفض المشروع --}}
                    <x-slot name="trigger">
                        <x-button class="text-xs bg-red-700 hover:bg-red-500" type="button" @click="showModal = true"
                            value="Click Here">رفض المشروع {{-- زر لفتح المودال --}}
                        </x-button>
                    </x-slot>
                    <x-slot name="title">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            رفض المشروع {{-- عنوان المودال --}}
                        </h3>
                    </x-slot>
                    <x-slot name="content">
                        <p class="text-sm text-gray-500">
                            هل أنت متأكد أنك تريد رفض {{ $project->title }}؟ {{-- محتوى المودال --}}
                        </p>
                    </x-slot>
                </x-modal>
            </form>
            @endcan

            @can('complete',$project) {{-- يتحقق من إذن المستخدم لإتمام المشروع --}}
            <form method="GET" action="{{route('projects.complete', $project)}}">
                @csrf {{-- حماية CSRF --}}
                <x-modal action="إتمام المشروع"> {{-- مودال لإتمام المشروع --}}
                    <x-slot name="trigger">
                        <x-button class="text-xs" type="button" @click="showModal = true" value="Click Here">
                            إتمام المشروع {{-- زر المودال --}}
                        </x-button>
                    </x-slot>
                    <x-slot name="title">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            إتمام المشروع {{-- عنوان المودال --}}
                        </h3>
                    </x-slot>
                    <x-slot name="content">
                        <p class="text-sm text-gray-500">
                            هل أنت متأكد أنك تريد إتمام {{ $project->title }}؟ {{-- محتوى المودال لتأكيد العملية --}}
                        </p>
                    </x-slot>
                </x-modal>
            </form>
            @endcan

        </div> {{-- نهاية div الأزرار --}}
    </x-slot> {{-- نهاية الهيدر --}}

    <div class="max-w-7xl mx-auto">
        <x-flash-message /> {{-- عرض رسائل النجاح أو الخطأ --}}
    </div>

    <div class="mx-auto max-w-7xl py-12 flex flex-col md:flex-row container items-start justify-center gap-6">
        {{-- هذا القسم الرئيسي يحتوي على جزئين: محتوى المشروع ومعلومات جانبية --}}

        <div class="w-full md:w-3/5"> {{-- القسم الرئيسي الأكبر للبيانات الأساسية للمشروع --}}
            <div class="bg-white overflow-hidden shadow-lg rounded-3xl">
                <div class="bg-white border-b border-gray-200">
                    <div class="p-8 bg-white text-gray-800">
                        <div class="flex justify-between"> {{-- عنوان المشروع + أيقونة مزامنة --}}
                            <h2 class="font-semibold text-xl text-gray-800 leading-tight">عرض اقتراح المشروع</h2>
                            @can('sync',$project) {{-- تحقق إذن مزامنة المشروع --}}
                            <a class="text-green-600 hover:text-green-400 mr-2" href="{{ route('projects.sync',$project) }}">
                                <i class="fas fa-sync-alt"></i> {{-- أيقونة مزامنة --}}
                            </a>
                            @endcan
                        </div>

                        <div class="space-y-4 p-2">
                            {{-- عرض الأهداف (Aims) --}}
                            <div class="border-b border-gray-300 pb-4">
                                <h1 class="font-semibold text-base text-gray-800 leading-tight pb-2">الأهداف:</h1>
                                <ol class="list-disc list-inside">
                                    @foreach ( json_decode($project->aims) as $aim)
                                    <li class="flex justify-between items-center text-sm py-0.5">
                                        <span class="text-sm align-middle">&#8226;{{ ' '.$aim->name }}</span> {{-- اسم الهدف --}}
                                        <input class="rounded-md text-gray-500" disabled type="checkbox" {{ $aim->complete ? 'checked' : '' }}/> {{-- حالة الهدف --}}
                                    </li>
                                    @endforeach
                                </ol>
                            </div>

                            {{-- عرض الأهداف التفصيلية (Objectives) --}}
                            <div class="border-b border-gray-300 pb-4">
                                <h1 class="font-semibold text-base text-gray-800 leading-tight pb-2">المهام التفصيلية:</h1>
                                <ol class="list-disc list-inside">
                                    @foreach ( json_decode($project->objectives) as $objective)
                                    <li class="flex justify-between text-center text-sm py-0.5">
                                        <span class="text-sm align-middle">&#8226;{{ ' '.$objective->name }}</span> {{-- اسم الهدف --}}
                                        <input class="rounded-md text-gray-500" disabled type="checkbox" {{ $objective->complete ? 'checked' : '' }}></input> {{-- حالة الهدف --}}
                                    </li>
                                    @endforeach
                                </ol>
                            </div>

                            {{-- عرض المهام (Tasks) --}}
                            <div class="">
                                <h1 class="font-semibold text-base text-gray-800 leading-tight pb-2">المهام:</h1>
                                <ol class="list-decimal list-inside">
                                    @foreach ( json_decode($project->tasks) as $key => $task)
                                    <li class="flex justify-between items-center text-sm py-1.5">
                                        <span class="text-sm align-middle">{{ $key+1 }}{{ '. '.$task->name }}</span> {{-- رقم المهمة واسمها --}}
                                        <input class="rounded-md text-gray-500" disabled type="checkbox" {{ $task->complete ? 'checked' : '' }}></input> {{-- حالة المهمة --}}
                                    </li>
                                    @endforeach
                                </ol>
                            </div>

                        </div> {{-- نهاية القسم الداخلي --}}
                    </div>
                </div>
            </div>

            {{-- عرض ملف Readme --}}
            <div class="bg-white overflow-hidden shadow-lg rounded-3xl mt-4">
                <div class="bg-white border-b border-gray-200">
                    <div class="p-8 bg-white text-gray-800">
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight">ملف README.md</h2>
                        <div class="mt-2 text-sm text-gray-700">
                            @if (!is_array($markdown))
                            <x-readme>
                                {!! $markdown ?? 'لا يوجد محتوى بعد.'!!} {{-- عرض محتوى Markdown --}}
                            </x-readme>
                            @else
                            <p class="py-12">
                                لا يوجد ملف README حتى الآن. {{-- إذا لم يكن موجود --}}
                            </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div> {{-- نهاية القسم الرئيسي --}}

        {{-- القسم الجانبي للمعلومات الثانوية --}}
        <div class="w-full md:w-1/4 space-y-4">

            {{-- بطاقة معلومات عامة --}}
            <div class="bg-white overflow-hidden shadow-lg rounded-3xl">
                <div class="p-8 bg-white">
                    <div class="items-end">
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight">حول المشروع</h2>
                        <div class="grid grid-cols-2 gap-1 mt-2">
                            <h2 class="font-semibold text-base text-gray-800 leading-tight">النوع:</h2>
                            <span class="text-sm text-gray-700 text-right capitalize">{{ $project->type->value . ' مشروع'}}</span>
                            <h2 class="font-semibold text-base text-gray-800 leading-tight">التخصص:</h2>
                            <span class="text-sm text-gray-700 text-right capitalize">{{ $project->spec->value }}</span>
                            <h2 class="font-semibold text-base text-gray-800 leading-tight">الحالة:</h2>
                            <span class="text-sm text-gray-700 text-right capitalize">{{ $project->state->value }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- بطاقة الوصف --}}
            <div class="bg-white overflow-hidden shadow-lg rounded-3xl">
                <div class="p-6 bg-white">
                    <div class="items-end p-2">
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight">الوصف</h2>
                        <p class="text-sm text-gray-700 col-span-2 pt-2">{{ $github['description'] ?? 'لا يوجد وصف بعد'}}</p>
                    </div>
                </div>
            </div>

            {{-- بطاقة المستودع --}}
            <div class="bg-white overflow-hidden shadow-lg rounded-3xl">
                <div class="p-6 bg-white">
                    <div class="items-end p-2">
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight">المستودع</h2>
                        <div class="grid grid-cols-2 gap-1 mt-2">
                            <h2 class="font-semibold text-base text-gray-800 leading-tight">Github:</h2>
                            @if ($github)
                            <a href="{{ $github['html_url'] }}" target="_blank"
                                class="text-sm text-indigo-500 hover:text-indigo-700 text-right capitalize">{{ $github['full_name'] }}</a>
                            @else
                            <span class="text-sm text-gray-700 text-right">غير متاح</span>
                            @endif

                            <h2 class="font-semibold text-base text-gray-800 leading-tight">المشاكل المفتوحة:</h2>
                            <span class="text-sm text-gray-700 text-right capitalize">{{ $github['open_issues_count'] ?? 'غير متاح' }}</span>

                            <h2 class="font-semibold text-base text-gray-800 leading-tight col-span-2">اللغات المستخدمة:</h2>
                            @forelse ($languages as $language => $value)
                            <span class="text-sm text-gray-700 text-left capitalize">{{ $language }}:</span>
                            <span class="text-sm text-gray-700 text-right capitalize">{{ round($value/$languages->sum()*100,2).'%' }}</span>
                            @empty
                            <span class="text-sm text-gray-700 text-left capitalize">غير متاح</span>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            {{-- بطاقة مجموعة التطوير --}}
            <div class="bg-white overflow-hidden shadow-lg rounded-3xl">
                <div class="p-6 bg-white">
                    <div class="items-end p-2">
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight">مجموعة التطوير</h2>
                        <h1 class="font-semibold text-base text-gray-800 leading-tight my-2">المشرف:</h1>

                        @if ($project->supervisor_id)
                        <a href="{{ route('users.show',$project->supervisor->id) }}">
                            <div class="mt-1 bg-gray-50 px-1 py-1 rounded-lg border border-gray-300 hover:bg-gray-100">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8">
                                        <img class="h-8 w-8 rounded-full border border-gray-300"
                                            src="/uploads/avatars/{{ $project->supervisor->avatar }}" alt="profile">
                                    </div>
                                    <div class="ml-2">
                                        <div class="text-xs font-medium text-gray-900">{{ $project->supervisor->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $project->supervisor->email }}</div>
                                    </div>
                                </div>
                            </div>
                        </a>

                        {{-- زر التخلي عن المشروع --}}
                        @if ($project->supervisor_id == Auth::id())
                        <a href="{{ route('projects.abandon',$project->id) }}">
                            <x-modal action="التخلي عن المشروع" type="button">
                                <x-slot name="trigger">
                                    <button @click.prevent="showModal = true" class="mt-1 px-2 py-2 w-full bg-red-50 flex justify-center rounded-lg font-semibold text-red-700 border border-red-700 hover:border-red-500 hover:text-red-500 focus:outline-none">
                                        التخلي عن المشروع
                                    </button>
                                </x-slot>
                                <x-slot name="title">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">التخلي عن المشروع</h3>
                                </x-slot>
                                <x-slot name="content">
                                    <p class="text-sm text-gray-500">
                                        هل أنت متأكد أنك تريد التخلي عن هذا المشروع؟ هذا الإجراء سيجعل المشروع متاحًا للمشرفين الآخرين.
                                    </p>
                                </x-slot>
                            </x-modal>
                        </a>
                        @endif

                        @else {{-- إذا لا يوجد مشرف --}}
                        @can('project-supervise')
                        <a href="{{ route('projects.supervise',$project->id) }}" class="mt-1 py-2 bg-gray-50 px-2 flex justify-center rounded-lg font-semibold text-blue-700 border border-gray-300">الإشراف على المشروع</a>
                        @else
                        لا يوجد مشرف بعد
                        @endcan
                        @endif

                        <h1 class="font-semibold text-base text-gray-800 leading-tight my-2">الفريق:</h1>
                        @forelse ($project->group->developers as $developer)
                        @once
                        @if(auth()->user()->groups->contains($project->group) || $project->supervisor == auth()->user() || auth()->user()->can('project-edit'))
                        {{-- زر إلغاء تعيين المجموعة --}}
                        <a href="{{ route('projects.unassign',$project->id) }}">
                            <x-modal action="إلغاء تعيين المجموعة" type="button">
                                <x-slot name="trigger">
                                    <button @click.prevent="showModal = true" class="px-2 py-2 w-full bg-red-50 flex justify-center rounded-lg font-semibold text-red-700 border border-red-700 hover:border-red-500 hover:text-red-500 focus:outline-none">إلغاء تعيين المجموعة</button>
                                </x-slot>
                                <x-slot name="title">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">إلغاء تعيين المجموعة</h3>
                                </x-slot>
                                <x-slot name="content">
                                    <p class="text-sm text-gray-500">
                                        هل أنت متأكد أنك تريد إلغاء تعيين فريقك من هذا المشروع؟ هذا الإجراء سيجعل المشروع متاحًا للتعيينات.
                                    </p>
                                </x-slot>
                            </x-modal>
                        </a>
                        @else
                        {{-- زر إرسال طلب الانضمام --}}
                        <a href="{{ route('requests.store',$project->group->id) }}" class="py-2 bg-gray-50 px-2 flex justify-center rounded-lg font-semibold text-blue-700 border border-gray-300">إرسال طلب الانضمام</a>
                        @endif
                        @endonce

                        <a href="{{ route('users.show',$developer->id) }}">
                            <div class="mt-1 bg-gray-50 px-1 py-1 rounded-lg border border-gray-300 hover:bg-gray-100">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8">
                                        <img class="h-8 w-8 rounded-full border border-gray-300" src="/uploads/avatars/{{ $developer->avatar }}" alt="profile">
                                    </div>
                                    <div class="ml-2">
                                        <div class="text-xs font-medium text-gray-900">{{ $developer->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $developer->email }}</div>
                                    </div>
                                </div>
                            </div>
                        </a>
                        @empty
                        <a href="{{ route('projects.assign',$project->id) }}" class="py-2 bg-gray-50 px-2 flex justify-center rounded-lg font-semibold text-blue-700 border border-gray-300">تعيين المشروع</a>
                        @endforelse
                    </div>
                </div>
            </div>

        </div> {{-- نهاية القسم الجانبي --}}
    </div> {{-- نهاية flex الرئيسي --}}
</x-app-layout> {{-- نهاية Layout --}}
