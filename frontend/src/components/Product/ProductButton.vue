<script>
export default {
    props : {
        remove : {
            type: Boolean,
            default: false
        }
    }
}
</script>

<template>
    <button :class="{
        'bg-violet-700 hover:bg-violet-600' : !remove,
        'bg-red-700 hover:bg-red-600':remove
    }" class="w-60 h-10 rounded-lg text-xl cursor-pointer transition-all duration-300">
        
        <span v-if="remove">Remove from cart</span>
        <span v-else>Add to cart</span>
        <i :class="{
                'fas fa-plus' : !remove,
                'fas fa-x' : remove,
        }" class="ml-2 text-lg"></i>
        
    </button>
</template>