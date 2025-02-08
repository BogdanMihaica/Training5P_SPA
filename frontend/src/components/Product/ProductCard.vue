<script>
import axios from 'axios';
import ProductButton from './ProductButton.vue';
import { getCookie } from '@/common/helpers';
import Swal from 'sweetalert2';

export default {
    components: { ProductButton },

    data() {
        return {
            quantity: 1
        }
    },

    props: {
        id: Number,
        title: String,
        image: String,
        description: String,
        price: Number,
        cart: {
            type: Boolean,
            default: false
        },
        boughtQuantity: Number
    },

    methods: {
        /**
         * performs a request to add an item to the cart
         * 
         * @param id 
         * @param quantity 
         */
        async addToCart(id, quantity) {
            const body = {
                quantity: quantity,
            };

            await axios.get('/sanctum/csrf-cookie');

            await axios.post(`/spa/cart/${id}`, body, {
                headers: {
                    'accept': 'application/json',
                },
            })
                .then(() => {
                    this.$emit('added');
                    Swal.fire({
                        title: "Success!",
                        text: "Successfully added product to the cart!",
                        icon: "success"
                    });
                })
                .catch(error => {
                    Swal.fire({
                        title: "Ugh...",
                        text: "Something wrong happened.",
                        icon: "error"
                    });
                    console.error("There was an error adding the product to the cart:", error);
                });
        },

        /**
         * Performs a request to remove an item from the cart
         * 
         * @param id 
         */
        async removeFromCart(id) {
            await axios.get('/sanctum/csrf-cookie');

            let xsrfToken = getCookie('XSRF-TOKEN');

            await axios.delete(`/spa/cart/${id}`, {
                headers: {
                    'accept': 'application/json',
                    'X-XSRF-TOKEN': xsrfToken
                },
            })
                .then(() => {
                    this.$emit('removed');
                    Swal.fire({
                        title: "Success!",
                        text: "Successfully removed product from the cart!",
                        icon: "success"
                    });
                })
                .catch(error => {
                    Swal.fire({
                        title: "Ugh...",
                        text: "Something wrong happened.",
                        icon: "error"
                    });
                    console.error("There was an error removing the product from the cart:", error);
                });
        }
    }
}
</script>

<template>
    <div class="w-70 min-h-120 rounded-3xl border-2 border-neutral-600 overflow-hidden 
        bg-gradient-to-t from-neutral-800 to-neutral-700">
        <div class="w-full flex-col items-center text-violet-100 p-4 h-full">
            <div class="flex flex-col h-full auto items-center justify-between space-y-4">

                <div class="w-full flex justify-center mt-1 overflow-hidden">
                    <img :src="image ?? 'https://placehold.co/200'" alt="Product image"
                        class="w-60 rounded-lg border-2 border-neutral-600">
                </div>

                <h2 class="text-2xl">{{ title }}</h2>
                <h3 class="text-xs mx-5">{{ description }}</h3>
                <h2 class="text-2xl" v-show="cart">Quantity: {{ boughtQuantity }}</h2>
                <h2 class="text-3xl text-violet-300">${{ price }}</h2>

                <div class="flex justify-center items-center mt-1 flex-col">
                    <ProductButton :cart="cart" @add="addToCart(id, quantity)" @remove="removeFromCart(id)" />

                    <label :for="`quantity-${id}`" v-show="!cart">Select Quantity</label>

                    <select :id="`quantity-${id}`" v-model="quantity" v-show="!cart">
                        <option v-for="i in 10" :key="i" class="text-black">
                            {{ i }}
                        </option>
                    </select>
                </div>

            </div>
        </div>
    </div>

</template>