<script>
export default {
    props: {
        cart: {
            type: Boolean,
            default: false,
        },
    },
}
</script>

<template>
    <button :class="{
        'bg-violet-700 hover:bg-violet-600': !cart,
        'bg-red-700 hover:bg-red-600': cart
    }" class="w-60 h-10 rounded-lg text-xl cursor-pointer transition-all duration-300"
        @click.prevent="cart ? $emit('remove') : $emit('add')">

        <span v-if="cart">{{ $t('removeFromCart') }}</span>
        <span v-else>{{ $t('addToCart') }}</span>
        <i :class="{
            'fas fa-plus': !cart,
            'fas fa-x': cart,
        }" class="ml-2 text-lg"></i>

    </button>
</template>