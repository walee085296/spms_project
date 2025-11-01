@if ($errors->any() || session('error'))
<div class="alert flex flex-row items-center bg-red-200 border-red-700 border p-5 rounded-lg my-5 mb-4"
    x-data="{ show: false }" @keydown.escape="show = false" x-init="() => {
    setTimeout(() => show = true, 500);
    setTimeout(() => show = false, 50000);
  }" x-show="show" x-description="Notification panel, show/hide based on alert state."
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-90"
    x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-90">
    <div
        class="alert-icon flex items-center bg-red-100 border-2 border-red-500 justify-center h-10 w-10 flex-shrink-0 rounded-full">
        <span class="text-red-500">
            <svg fill="currentColor" viewBox="0 0 20 20" class="h-6 w-6">
                <path fill-rule="evenodd"
                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                    clip-rule="evenodd"></path>
            </svg>
        </span>
    </div>
    <div class="alert-content ml-4">
        <div class="alert-title font-semibold text-lg text-red-800">
            {{ __('Whoops, something went wrong') }}
        </div>
        <div class="alert-description text-sm text-red-600">
            {{ session('error') }}
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </div>
    </div>
</div>
@endif
@if (session('success'))
<div class="alert flex flex-row items-center bg-green-200 border-green-500 border p-5 rounded-lg my-5 mb-4"
    x-data="{ show: false }" x-init="() => {
      setTimeout(() => show = true, 500);
      setTimeout(() => show = false, 5000);
    }" x-show="show" x-description="Notification panel, show/hide based on alert state." @keydown.escape="show = false"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-90"
    x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-90">
    <div
        class="alert-icon flex items-center bg-green-100 border-2 border-green-500 justify-center h-10 w-10 flex-shrink-0 rounded-full">
        <span class="text-green-500">
            <svg fill="currentColor" viewBox="0 0 20 20" class="h-6 w-6">
                <path fill-rule="evenodd"
                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                    clip-rule="evenodd"></path>
            </svg>
        </span>
    </div>
    <div class="alert-content ml-4">
        <div class="alert-title font-semibold text-lg text-green-800">
            {{ __('Success') }}
        </div>
        <div class="alert-description text-sm text-green-600">
            {{ session('success') }}
        </div>
    </div>
</div>
@endif