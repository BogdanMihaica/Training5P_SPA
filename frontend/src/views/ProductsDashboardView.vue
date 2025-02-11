<script>
import axios from 'axios';
import Swal from 'sweetalert2';

export default {
    data() {
        return {
            products: []
        }
    },

    mounted() {
        this.getProducts();
    },

    methods: {
        /**
         * Async function that fetches for the products that are not in the cart
         */
        async getProducts() {

            await axios.get("/spa/products/all")
                .then(response => {
                    this.products = response.data.data;
                })
                .catch(error => {
                    console.error("There was an error fetching the products:", error);
                });
        },

        async handleDelete(id, index) {

            await axios.get('/sanctum/csrf-cookie');

            await axios.delete(`/spa/products/${id}`)
                .then(() => {
                    this.products.splice(index, 1)

                    Swal.fire({
                        title: "Success",
                        text: "Succesfully deleted item with id " + id,
                        icon: "success"
                    });
                })
                .catch(() => {
                    Swal.fire({
                        title: "Ugh...",
                        text: "Something went wrong.",
                        icon: "error"
                    });
                })
        }
    }
}
</script>

<template>
    <h1 class="text-white text-center text-5xl mb-10">{{ $t('dashboard') }}</h1>
    <div class="w-full flex flex-col justify-center items-center text-white">
        <table class="w-[90%] rounded-lg">
            <thead>
                <tr class="bg-violet-800">
                    <th class="rounded-tl-lg">
                        {{ $t('id') }}
                    </th>
                    <th>
                        {{ $t('image') }}
                    </th>
                    <th>
                        {{ $t('title') }}
                    </th>
                    <th>
                        {{ $t('description') }}
                    </th>
                    <th>
                        {{ $t('price') }}
                    </th>
                    <th>
                        {{ $t('createdAt') }}
                    </th>
                    <th class="rounded-tr-lg">
                        {{ $t('actions') }}
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr class="bg-neutral-800" v-for="(product, i) in products" :key="product.id">
                    <td>
                        {{ product.id }}
                    </td>
                    <td>
                        <img class="h-20 rounded-lg" :src="product.image_url" alt="Product image">
                    </td>
                    <td>
                        {{ product.title }}
                    </td>
                    <td>
                        {{ product.description }}
                    </td>
                    <td>
                        {{ product.price }}
                    </td>
                    <td>
                        {{ product.created_at }}
                    </td>
                    <td>
                        <div class="flex flex-col justify-center items-center w-full gap-2">
                            <RouterLink :to="{ name: 'product', params: { id: product.id } }" class="px-4 py-1 bg-blue-500 rounded-lg cursor-pointer hover:bg-blue-600 
                                focus:ring-blue-400 focus:ring-1">
                                {{ $t('edit') }}
                            </RouterLink>


                            <button class="px-4 py-1 bg-red-500 rounded-lg cursor-pointer hover:bg-red-600
                                focus:ring-red-400 focus:ring-1" @click.prevent="handleDelete(product.id, i)">
                                {{ $t('delete') }}
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
<style scoped>
th,
td {
    padding: 1rem;
    text-align: center;
}

td {
    border-bottom: 1px solid rgb(77, 77, 77);
}
</style>