<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>SPMS</title>
 
    <!-- Fonts -->
    {{-- <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet"> --}}

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="icon" href="{!! asset('images/favicon.ico') !!}" />

</head>

<body class="bg-gradient-to-br from-rose-50 via-white to-rose-100 min-h-screen">


    <div class="bg-gradient-to-br from-rose-50 via-white to-rose-100 min-h-screen">
        <div class="max-w-7xl mx-auto">
            <div class="relative z-10 pb-8 bg-white sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32">
                <svg class="hidden lg:block absolute right-0 inset-y-0 h-full w-48 text-white transform translate-x-1/2"
                    fill="currentColor" viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true">
                    <polygon points="50,0 100,0 50,100 0,100" />
                </svg>
                <div>
                    <div class="relative pt-6 px-4 sm:px-6 lg:px-8">
                        <nav class="relative flex items-center justify-between sm:h-10 lg:justify-start"
                            aria-label="Global">
                            <div class="flex items-center flex-grow flex-shrink-0 lg:flex-grow-0">
                                <div class="flex items-center justify-between w-full md:w-auto">
                                    <a href="{{ url('/') }}">
                                        <x-application-logo
                                            class="h-16 w-auto fill-current text-gray-500 hover:text-gray-300 transition duration-150 ease-in-out" />
                                    </a>
                                        <div class="-mr-2 md:hidden">
    <button id="menu-toggle" type="button"
        class="bg-white rounded-md p-2 inline-flex items-center justify-center text-gray-500 hover:text-indigo-600 hover:bg-gray-100 focus:outline-none transition duration-300">

        <!-- Hamburger -->
        <svg id="menu-open" class="h-6 w-6 block" xmlns="http://www.w3.org/2000/svg"
            fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round"
                stroke-width="2"
                d="M4 6h16M4 12h16M4 18h16" />
        </svg>

        <!-- Close -->
        <svg id="menu-close" class="h-6 w-6 hidden" xmlns="http://www.w3.org/2000/svg"
            fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round"
                stroke-width="2"
                d="M6 18L18 6M6 6l12 12" />
        </svg>

    </button>
</div>

<div id="mobile-menu"
     class="hidden md:hidden absolute top-16 left-0 w-full bg-white shadow-xl z-50 transition-all duration-300">

    <div class="flex flex-col divide-y divide-gray-200 text-center">

        <a href="https://advancedacademy.edu.eg/Katamia/RootPages/Default.aspx"
           class="py-4 font-semibold text-gray-700 hover:bg-gray-100 hover:text-indigo-600 transition">
           🏫 المعهد
        </a>

        <a href="https://github.com/walee085296/spms_project"
           class="py-4 font-semibold text-gray-700 hover:bg-gray-100 hover:text-indigo-600 transition">
           💻 GitHub
        </a>

        <a href="https://github.com/walee085296/spms_project/issues"
           class="py-4 font-semibold text-gray-700 hover:bg-gray-100 hover:text-indigo-600 transition">
           🐞 المشكلات
        </a>

        <a href="https://github.com/walee085296/spms_project/pulls"
           class="py-4 font-semibold text-gray-700 hover:bg-gray-100 hover:text-indigo-600 transition">
           💡 الاقتراحات
        </a>

        @auth
        <a href="{{ route('dashboard') }}"
           class="py-4 font-semibold text-white bg-indigo-600 hover:bg-indigo-700 transition">
           Dashboard
        </a>
        @else
        <a href="{{ route('login') }}"
           class="py-4 font-semibold text-white bg-indigo-600 hover:bg-indigo-700 transition">
           Login
        </a>
        @endauth

    </div>
</div>


                                </div>
                            </div>
                               <div class="hidden md:block md:ml-6 md:pr-4">
                                    <a href="https://advancedacademy.edu.eg/Katamia/RootPages/Default.aspx"
                                    class="font-bold text-gray-500 hover:text-gray-900 px-3 py-2 rounded-full hover:bg-gray-100">المعهد</a>
                                    <a href="https://github.com/walee085296/spms_project"
                                    class="font-bold text-gray-500 hover:text-gray-900 px-3 py-2 rounded-full hover:bg-gray-100">جيت هوب</a>

                                      <a href="https://github.com/walee085296/spms_project/issues"
                                    class="font-bold text-gray-500 hover:text-gray-900 px-3 py-2 rounded-full hover:bg-gray-100">المشكلاات</a>

                                     <a href="https://github.com/walee085296/spms_project/pulls"
                                    class="font-bold text-gray-500 hover:text-gray-900 px-3 py-2 rounded-full hover:bg-gray-100">الاقتراحاات</a>

                                    @auth
                                      <a href="{{ route('dashboard') }}"
                                        class="font-bold text-indigo-600 px-2 py-2 rounded-full hover:bg-gray-100 hover:text-indigo-500">Dashboard</a>
                                    @endauth
                                   @guest
                                     <a href="{{ route('login') }}"
                                      class="font-bold text-indigo-600 px-3 py-2 rounded-full hover:bg-gray-100 hover:text-indigo-500">Login</a>
                                   @endguest
                                </div>
                        </nav>
                    </div>
                    <div id="mobile-menu"
                          class="hidden absolute top-16 inset-x-0 p-4 md:hidden transition-all duration-300">

                    {{-- <div class="absolute top-0 inset-x-0 p-2 transition transform origin-top-right md:hidden"> --}}
                        <div class="rounded-lg shadow-md bg-white ring-1 ring-black ring-opacity-5 overflow-hidden">
                            <div class="px-5 pt-4 flex items-center justify-between">
                                <div>
                                    <img class="h-8 w-auto"
                                        src="https://tailwindui.com/img/logos/workflow-mark-indigo-600.svg" alt="">
                                </div>
                                <div class="-mr-2">
                                    <button type="button"
                                        class="bg-white rounded-md p-2 inline-flex items-center justify-center text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
                                        <span class="sr-only">Close main menu</span>
                                        <!-- Heroicon name: outline/x -->
                                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                          
                            @auth
                            <a href="{{ route('dashboard') }}"
                                class="block w-full px-5 py-3 text-center font-medium text-indigo-600 bg-gray-50 hover:bg-gray-100">
                                Log in
                            </a>
                            @else
                            <a href="{{ route('login') }}"
                                class="block w-full px-5 py-3 text-center font-medium text-indigo-600 bg-gray-50 hover:bg-gray-100">
                                Log in
                            </a>
                            @endauth
                        </div>
                    </div>
                </div>
                <br>
     <hr>
                <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
                    <div class="sm:text-center lg:text-left">
                     
                        <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                                   <span class="block xl:inline">أدوات تساعدك على تطوير</span>
                                   <span class="block text-indigo-600 xl:inline" > مشروع تخرجك  </span>
                                     </h1>
                                     <br>
                                      <hr>
                        <p
                              class="mt-3 text-base text-gray-500 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0 min-h-full py-11">
                                 جميع الأدوات التي تحتاجها لإنشاء أفضل المشاريع وإدارتها بسهولة !
                                                        <br />    <br />
                                        تم إنشاء هذا النظام لطلاب المعهد العاالي للدراساات المتطوره /فرع القطااميه  للمساعدة في تسهيل عملية إنشاء المشاريع وإدارتها وعرضها!
                                 </p>

                        <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
                            <div class="rounded-md shadow">
                                <a href="{{ route('login') }}"
                                    class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 md:py-4 md:text-lg md:px-10">
                                    ابداء الان 
                                </a>
                            </div>
                            <div class="mt-3 sm:mt-0 sm:ml-3">
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2">
            <img class="h-56 w-full object-cover sm:h-72 md:h-96 lg:w-full lg:h-full"
               src="{{ asset('images\photo.avif') }}"
                alt="logo">
        </div>
    </div>

    <script>
const toggleBtn = document.getElementById("menu-toggle");
const mobileMenu = document.getElementById("mobile-menu");
const openIcon = document.getElementById("menu-open");
const closeIcon = document.getElementById("menu-close");

toggleBtn.addEventListener("click", () => {
    mobileMenu.classList.toggle("hidden");
    openIcon.classList.toggle("hidden");
    closeIcon.classList.toggle("hidden");
});
</script>

    <script src="{{ asset('js/app.js') }}"></script>
<x-footer />
</body>


</html>