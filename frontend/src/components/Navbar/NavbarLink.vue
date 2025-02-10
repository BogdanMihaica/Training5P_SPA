<script>
export default {
    props: {
        route: Object,
        icon: String
    }
}
</script>

<template>
    <div class="">
        <router-link :to="route" class="text-white rounded-lg min-w-20 px-4 py-2 bg-violet-800 transition-all duration-500 flex justify-center items-center text-lg
                    hover:bg-violet-600 ">
            <i :class="icon" class="mr-2 text-sm"></i>
            <slot></slot>
        </router-link>
    </div>
</template>
