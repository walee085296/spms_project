<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('New Project') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <x-flash-message class="mb-4" :errors="$errors" />

                    <form method="POST" action="{{ route('projects.store') }}">
                        @csrf
                        <div class="grid grid-row-2 gap-6 mt-4 border-b border-gray-300 pb-8">
                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <x-label for="title" :value="__('عنوان المشروع')" />
                                    <x-input error="title" class="block mt-1 w-full" type="text" name="title"
                                        placeholder="عنوان المشروع" :value="old('title')" autofocus />
                                </div>
                                <div>
                                    <x-label for="repo" :value="__('ريبو المشروع')" />
                                    <select name="repo"
                                        class="block mt-1 text-sm text-gray-800 rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 w-full"
                                        id="repo">
                                        <option selected value="">انشاء جديد</option>
                                        @foreach ($repos as $repo)
                                        <option class="capitalize" @selected($repo['url']==old('repo')) value="{{
                                            $repo['url'] }}">{{ $repo['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @can('project-create')
                                <div>
                                    <x-label for="type" :value="__('نوع المشروع')" />
                                    <select name="type"
                                        class="block mt-1 text-sm text-gray-800 rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 w-full capitalize"
                                        id="type">
                                        <option selected disabled>اختار نوع المشروع </option>
                                        @foreach ($types as $type)
                                        <option class="capitalize" @selected($type->value == old('type')) value="{{
                                            $type->value }}">{{ $type->value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                 <div>
                                    <x-label for="spec" :value="__('مشرف المشروع ')" />
                                    <select name="specc"
                                        class="block mt-1 text-sm text-gray-800 rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 w-full capitalize"
                                        id="specc">
                                        <option selected disabled>اختار تخصص</option>
                                        @foreach ($specs as $spec)
                                        <option class="capitalize" @selected($spec->value == old('spec')) value="{{
                                            $spec->value }}">{{ $spec->value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                               
                                <div>
                                    <x-label for="spec" :value="__('تخصص المشروع')" />
                                    <select name="spec"
                                        class="block mt-1 text-sm text-gray-800 rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 w-full capitalize"
                                        id="spec">
                                        <option selected disabled>اختار تخصص</option>
                                        @foreach ($specs as $spec)
                                        <option class="capitalize" @selected($spec->value == old('spec')) value="{{
                                            $spec->value }}">{{ $spec->value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endcan
                                @can('project-approve')
                                <div>
                                    <x-label for="state" :value="__('حاله المشروع')" />
                                    <select name="state"
                                        class="block mt-1 text-sm text-gray-800 rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 w-full capitalize"
                                        id="state">
                                        <option selected disabled>حاله المشروع</option>
                                        @foreach ($states as $state)
                                        <option class="capitalize" @selected($state->value == old('state'))
                                            value="{{
                                            $state->value }}">{{ $state->value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endcan
                            </div>
                        </div>
                        <section x-data="handler()">
                            <div class="py-8 border-b border-gray-300">
                                <x-label class="mb-2" for="aims" :value="__('الأهداف الأساسية للمشروع')" />
                                <template x-for="(aim, index) in aims" :key="index" x-init="initAims()">
                                    <div class="flex justify-center items-center space-x-2">
                                        <span x-text="index+1"></span>
                                        <x-input class="block mt-1 w-full" type="text" x-model="aims[index]"
                                            name="aims[]" placeholder="Project's Aim" error="aims.*" required />
                                        <span title="add aim"
                                            class="w-5 flex justify-center items-center cursor-pointer"
                                            @click="addAimField()"><i class="fa fa-plus"></i></span>
                                        <span title="delete aim"
                                            class="w-5 flex justify-center items-center cursor-pointer"
                                            @click="removeAimField(index)"><i
                                                class="fa fa-plus transform rotate-45"></i></span>
                                    </div>
                                </template>
                            </div>
                            <div class="py-8 border-b border-gray-300">
                                <x-label class="mb-2" for="objective" :value="__('هداف المشروع التفصيلية')" />
                                <template x-for="(objective, index) in objectives" :key="index" x-init="initObj()">
                                    <div class="flex justify-center items-center space-x-2">
                                        <span x-text="index+1"></span>
                                        <x-input class="block mt-1 w-full" type="text" x-model="objectives[index]"
                                            name="objectives[]" id="objectives" placeholder="هداف المشروع التفصيلية"
                                            error="objectives.*" required />
                                        <span title="add objective"
                                            class="w-5 flex justify-center items-center cursor-pointer"
                                            @click="addObjField()"><i class="fa fa-plus"></i></span>
                                        <span title="delete objective"
                                            class="w-5 flex justify-center items-center cursor-pointer"
                                            @click="removeObjField(index)"><i
                                                class="fa fa-plus transform rotate-45"></i></span>
                                    </div>
                                </template>
                            </div>
                            <div class="py-8 border-b border-gray-300">
                                <x-label class="mb-2" for="task" :value="__('مهام المشروع')" />
                                <template x-for="(task, index) in tasks" :key="index" x-init="initTasks()">
                                    <div class="flex justify-center items-center space-x-2">
                                        <span x-text="index+1"></span>
                                        <x-input class="block mt-1 w-full" type="text" x-model="tasks[index]"
                                            name="tasks[]" placeholder="مهام المشروع" error="tasks.*" required />
                                        <span title="add task"
                                            class="w-5 flex justify-center items-center cursor-pointer"
                                            @click="addTaskField()"><i class="fa fa-plus"></i></span>
                                        <span title="delete task"
                                            class="w-5 flex justify-center items-center cursor-pointer"
                                            @click="removeTaskField(index)"><i
                                                class="fa fa-plus transform rotate-45"></i></span>
                                    </div>
                                </template>
                            </div>
                        </section>
                        <div class="pt-8 flex @can('project-supervise'){ justify-between }@else{ justify-end }@endcan">
                            @can('project-supervise')
                            <label class="inline-flex items-center">
                                <input id="supervise" type="checkbox" name="supervise" value="{{ Auth::user()->id }}"
                                    checked
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-600">
                                     الاشراف على المشروع
                                </span>
                            </label>
                            @endcan
                            <x-button>
                                {{ __('انشاء') }}
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
        aims: [],
        objectives: [],
        tasks: [],
        addAimField() {
            this.aims.push('');
        },
        removeAimField(index) {
            if(this.aims.length == 1){
                this.addAimField();
            }
            this.aims.splice(index, 1);
        },
        initAims(){
            var oldAims = @json(old('aims')) ?? [];
            this.aims = this.aims.concat(oldAims);
            if (oldAims.length == 0){
                this.addAimField();
            }
        },
        addObjField() {
            this.objectives.push('');
        },
        removeObjField(index) {
            if(this.objectives.length == 1){
                this.addObjField();
            }
            this.objectives.splice(index, 1);
        },
        initObj(){
            var oldObj = @json(old('objectives')) ?? [];
            this.objectives = this.objectives.concat(oldObj);
            if (oldObj.length == 0){
                this.addObjField();
            }
        },
        addTaskField() {
            this.tasks.push(['']);
        },
        removeTaskField(index) {
            if(this.tasks.length == 1){
                this.addTaskField();
            }
            this.tasks.splice(index, 1);
        },
        initTasks(){
            var oldTasks = @json(old('tasks')) ?? [];
            this.tasks = this.tasks.concat(oldTasks);
            if (oldTasks.length == 0){
                this.addTaskField();
            }
        }
      }
 }
</script>