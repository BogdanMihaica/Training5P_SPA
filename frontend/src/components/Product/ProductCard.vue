<script>
import axios from 'axios';
import ProductButton from './ProductButton.vue';

export default {
    components : {ProductButton},

    data () {
        return {
            quantity: 1
        }
    },

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

    methods : {
        addToCart (id,quantity) {
                axios.post(`http://localhost:8000/api/cart/${id}`, { quantity: quantity })
                    .then(response => {
                        console.log("Product added to cart:", response.data);
                    })
                    .catch(error => {
                        console.error("There was an error adding the product to the cart:", error);
                    });
        },
        removeFromCart (id) {
            alert(`Removing product with id ${id}`)
        }
    }
}
</script>

<template>
   <div class="w-70 min-h-120 rounded-3xl border-2 border-stone-600 overflow-hidden bg-gradient-to-t from-neutral-800 to-stone-700">
        <div class="w-full flex-col items-center text-violet-100 p-4 h-full">
            <div class="flex flex-col h-full auto items-center justify-between space-y-4">
                <div class="w-full flex justify-center mt-1 overflow-hidden">
                    <img :src="image" alt="Product image" class="w-60 rounded-lg border-2 border-stone-600">
                </div>
                <h2 class="text-2xl">{{ title }}</h2>
                <h3 class="text-xs mx-5">{{ description }}</h3>
                <h2 class="text-3xl text-violet-300">${{ price }}</h2>

                <div class="flex justify-center items-center mt-1 flex-col">
                    <ProductButton :remove="remove" @add="addToCart(id,quantity)" @remove="removeFromCart(id)"/>
                
                    <label :for="`quantity-${id}`">Select Quantity</label>

                    <select :id="`quantity-${id}`" v-model="quantity">
                        <option v-for="i in 10" :key="i" class="text-black">
                            {{ i }}
                        </option>
                    </select>
                </div>
               
            </div>
        </div>
    </div>

</template>