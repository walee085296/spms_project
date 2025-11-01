<x-app-layout>
    <!-- استخدام Layout رئيسي للتطبيق -->

    <x-slot name="header">
        <!-- رأس الصفحة -->
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('New Project') }} <!-- عنوان الصفحة -->
        </h2>
    </x-slot>

    <div class="py-12">
        <!-- المسافة العمودية حول المحتوى -->
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- ضبط العرض والحشوة -->
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                <!-- صندوق محتوى أبيض مع ظل وحواف مستديرة -->
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- مساحة داخلية للمحتوى -->
                    <x-flash-message class="mb-4" :errors="$errors" />
                    <!-- عرض رسائل النجاح أو الأخطاء -->

                    <form method="POST" action="{{ route('projects.store') }}">
                        @csrf
                        <!-- إضافة توكن CSRF للحماية -->
                        <div class="grid grid-row-2 gap-6 mt-4 border-b border-gray-300 pb-8">
                            <!-- شبكة لتقسيم الحقول الرئيسية -->
                            <div class="grid grid-cols-2 gap-6">
                                <!-- عمودان لحقول المشروع -->
                                <div>
                                    <x-label for="title" :value="__('Project Title')" />
                                    <!-- تسمية حقل العنوان -->
                                    <x-input error="title" class="block mt-1 w-full" type="text" name="title"
                                        placeholder="Project Title" :value="old('title')" autofocus />
                                    <!-- حقل إدخال العنوان -->
                                </div>
                                <div>
                                    <x-label for="repo" :value="__('Project\'s Repository')" />
                                    <!-- تسمية حقل الريبو -->
                                    <select name="repo"
                                        class="block mt-1 text-sm text-gray-800 rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 w-full"
                                        id="repo">
                                        <option selected value="">Create New</option>
                                        <!-- اختيار إنشاء مشروع جديد أو استخدام مستودع موجود -->
                                        {{-- @foreach ($repos as $repo)
                                        <option class="capitalize" @selected($repo['url']==old('repo')) value="{{
                                            $repo['url'] }}">{{ $repo['name'] }}</option>
                                        @endforeach --}}
                                        <!-- كان من المفترض هنا عرض المستودعات المرتبطة بالمستخدم -->
                                    </select>
                                </div>
                                
                                @can('project-create')
                                <!-- عرض الحقول التالية فقط إذا كان للمستخدم صلاحية إنشاء المشروع -->
                                <div>
                                    <x-label for="type" :value="__('Project\'s Type')" />
                                    <select name="type" id="type"
                                        class="block mt-1 text-sm text-gray-800 rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 w-full capitalize">
                                        <option selected disabled>Select Type</option>
                                        @foreach ($types as $type)
                                        <option class="capitalize" @selected($type->value == old('type')) value="{{ $type->value }}">
                                            {{ $type->value }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <x-label for="spec" :value="__('Project\'s Specialization')" />
                                    <select name="spec" id="spec"
                                        class="block mt-1 text-sm text-gray-800 rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 w-full capitalize">
                                        <option selected disabled>Select Specialization</option>
                                        @foreach ($specs as $spec)
                                        <option class="capitalize" @selected($spec->value == old('spec')) value="{{ $spec->value }}">
                                            {{ $spec->value }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                @endcan

                                @can('project-approve')
                                <!-- عرض حالة المشروع فقط إذا كان للمستخدم صلاحية الموافقة -->
                                <div>
                                    <x-label for="state" :value="__('Project\'s State')" />
                                    <select name="state" id="state"
                                        class="block mt-1 text-sm text-gray-800 rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 w-full capitalize">
                                        <option selected disabled>Select State</option>
                                        @foreach ($states as $state)
                                        <option class="capitalize" @selected($state->value == old('state')) value="{{ $state->value }}">
                                            {{ $state->value }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                @endcan
                            </div>
                        </div>

                        <!-- قسم الأهداف والمهام -->
                        <section x-data="handler()">
                            <!-- AlpineJS handler لإضافة/حذف الحقول ديناميكياً -->
                            <div class="py-8 border-b border-gray-300">
                                <x-label class="mb-2" for="aims" :value="__('Project\'s Aims')" />
                                <template x-for="(aim, index) in aims" :key="index" x-init="initAims()">
                                    <div class="flex justify-center items-center space-x-2">
                                        <span x-text="index+1"></span> <!-- رقم الهدف -->
                                        <x-input class="block mt-1 w-full" type="text" x-model="aims[index]"
                                            name="aims[]" placeholder="Project's Aim" error="aims.*" required />
                                        <!-- أيقونة إضافة هدف -->
                                        <span title="add aim" class="w-5 flex justify-center items-center cursor-pointer"
                                            @click="addAimField()"><i class="fa fa-plus"></i></span>
                                        <!-- أيقونة حذف هدف -->
                                        <span title="delete aim" class="w-5 flex justify-center items-center cursor-pointer"
                                            @click="removeAimField(index)"><i class="fa fa-plus transform rotate-45"></i></span>
                                    </div>
                                </template>
                            </div>

                            <div class="py-8 border-b border-gray-300">
                                <x-label class="mb-2" for="objective" :value="__('Project\'s Objectives')" />
                                <template x-for="(objective, index) in objectives" :key="index" x-init="initObj()">
                                    <div class="flex justify-center items-center space-x-2">
                                        <span x-text="index+1"></span>
                                        <x-input class="block mt-1 w-full" type="text" x-model="objectives[index]"
                                            name="objectives[]" id="objectives" placeholder="Project's Objective"
                                            error="objectives.*" required />
                                        <span title="add objective" class="w-5 flex justify-center items-center cursor-pointer"
                                            @click="addObjField()"><i class="fa fa-plus"></i></span>
                                        <span title="delete objective" class="w-5 flex justify-center items-center cursor-pointer"
                                            @click="removeObjField(index)"><i class="fa fa-plus transform rotate-45"></i></span>
                                    </div>
                                </template>
                            </div>

                            <div class="py-8 border-b border-gray-300">
                                <x-label class="mb-2" for="task" :value="__('Project\'s Tasks')" />
                                <template x-for="(task, index) in tasks" :key="index" x-init="initTasks()">
                                    <div class="flex justify-center items-center space-x-2">
                                        <span x-text="index+1"></span>
                                        <x-input class="block mt-1 w-full" type="text" x-model="tasks[index]"
                                            name="tasks[]" placeholder="Project's task" error="tasks.*" required />
                                        <span title="add task" class="w-5 flex justify-center items-center cursor-pointer"
                                            @click="addTaskField()"><i class="fa fa-plus"></i></span>
                                        <span title="delete task" class="w-5 flex justify-center items-center cursor-pointer"
                                            @click="removeTaskField(index)"><i class="fa fa-plus transform rotate-45"></i></span>
                                    </div>
                                </template>
                            </div>
                        </section>

                        <!-- قسم إشراف المشروع وزر الإرسال -->
                        <div class="pt-8 flex @can('project-supervise'){ justify-between }@else{ justify-end }@endcan">
                            @can('project-supervise')
                            <label class="inline-flex items-center">
                                <input id="supervise" type="checkbox" name="supervise" value="{{ Auth::user()->id }}"
                                    checked
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-600">
                                    I will supervise this project
                                </span>
                            </label>
                            @endcan
                            <x-button>
                                {{ __('Create') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
function handler() {
    return {
        aims: [], // مصفوفة أهداف المشروع
        objectives: [], // مصفوفة الأهداف التفصيلية
        tasks: [], // مصفوفة المهام
        addAimField() { this.aims.push(''); }, // إضافة حقل هدف
        removeAimField(index) { if(this.aims.length == 1){ this.addAimField(); } this.aims.splice(index, 1); }, // حذف هدف مع التأكد من وجود حقل واحد على الأقل
        initAims(){ var oldAims = @json(old('aims')) ?? []; this.aims = this.aims.concat(oldAims); if (oldAims.length == 0){ this.addAimField(); } }, // تهيئة الأهداف القديمة
        addObjField() { this.objectives.push(''); },
        removeObjField(index) { if(this.objectives.length == 1){ this.addObjField(); } this.objectives.splice(index, 1); },
        initObj(){ var oldObj = @json(old('objectives')) ?? []; this.objectives = this.objectives.concat(oldObj); if (oldObj.length == 0){ this.addObjField(); } },
        addTaskField() { this.tasks.push(['']); },
        removeTaskField(index) { if(this.tasks.length == 1){ this.addTaskField(); } this.tasks.splice(index, 1); },
        initTasks(){ var oldTasks = @json(old('tasks')) ?? []; this.tasks = this.tasks.concat(oldTasks); if (oldTasks.length == 0){ this.addTaskField(); } }
    }
}
</script>
