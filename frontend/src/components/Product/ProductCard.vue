<script>
import ProductButton from './ProductButton.vue';

export default {
    components : {ProductButton},
    props : {
        id: {
            type: Number,
            default: 0
        },
        title: {
            type: String,
            default: "Lorem ipsum"
        },
        image: {
            type: String,
            default: "https://picsum.photos/200"
        },
        description: {
            type: String,
            default: "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. "
        },
        price: {
            type: Number,
            default: 100
        },
        remove: {
            type: Boolean,
            default: false
        }
    },
}
</script>

<template>
   <div class="w-70 min-h-120 rounded-3xl border-2 border-stone-600 overflow-hidden bg-gradient-to-t from-neutral-800 to-stone-700">
        <div class="w-full flex-col items-center text-violet-100 p-4">
            <div class="w-full flex justify-center mt-1 overflow-hidden">
                <img :src="image" alt="Product image" class="w-60 rounded-lg border-2 border-stone-600">
            </div>
            <div class="flex flex-col h-full items-center justify-between mt-4 space-y-4">
                <h2 class="text-2xl">{{ title }}</h2>
                <h3 class="text-xs mx-5">{{ description }}</h3>
                <h2 class="text-3xl text-violet-300">${{ price }}</h2>
                <form>
                    <ProductButton :remove="remove"/>
                    <input type="hidden" name="id" :value="id"/>
                    
                    <select name="" id="quantity">

                    </select>
                </form>
            </div>
        </div>
    </div>

</template>