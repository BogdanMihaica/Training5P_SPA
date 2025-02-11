<script>
export default {}
</script>

<template>
    <main class="h-150 w-full flex flex-col justify-center items-center">
        <h1 class="text-9xl font-extrabold text-white tracking-widest">404</h1>
        <div class="bg-violet-600 px-2 text-sm rounded rotate-12 absolute">
            {{ $t('notFound') }}
        </div>
        <button class="mt-5 cursor-pointer">
            <a
                class="relative inline-block text-sm font-medium text-violet-500 group active:text-violet-500 focus:outline-none focus:ring">
                <span
                    class="absolute inset-0 transition-transform translate-x-0.5 translate-y-0.5 bg-violet-500 group-hover:translate-y-0 group-hover:translate-x-0">
                </span>

                <span class="relative block px-8 py-3 bg-[#1A2238] border border-current">
                    <router-link to="/">{{ $t('goHome') }}</router-link>
                </span>
            </a>
        </button>
    </main>
</template>