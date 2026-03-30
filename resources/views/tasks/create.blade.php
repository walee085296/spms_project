<x-app-layout>

<x-slot name="header">
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
{{ __('Create Task') }}
</h2>
</x-slot>

<div class="py-12">
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

<div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">

<div class="p-6 bg-white border-b border-gray-200">

<x-flash-message class="mb-4" :errors="$errors" />

<form method="POST" action="{{ route('tasks.store') }}">
@csrf

<div class="space-y-6">

<!-- ارسال لكل المشاريع -->
<div>
<label class="flex items-center space-x-2">
<input type="checkbox" name="all_projects" value="1">
<span>ارسال التاسك لكل المشاريع</span>
</label>
</div>

<!-- project id -->
<div>
<x-label for="project_id" :value="__('Project ID')" />
<select name="project_id" class="w-full border rounded">
@foreach($projects as $project)
<option value="{{ $project->id }}">
{{ $project->title}}
</option>
@endforeach
</select>
</div>

<!-- وصف التاسك -->
<div>
<x-label for="desc" :value="__('وصف التاسك')" />

<textarea
name="desc"
class="w-full border-gray-300 rounded-md shadow-sm"
rows="4"
placeholder="اكتب وصف التاسك هنا"
></textarea>

</div>

</div>

<!-- زر الحفظ -->
<div class="pt-8 flex justify-end">

<x-button>
{{ __('إنشاء التاسك') }}
</x-button>

</div>

</form>

</div>
</div>
</div>
</div>

</x-app-layout>