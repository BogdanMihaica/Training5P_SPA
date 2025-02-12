<!-- eslint-disable no-unused-vars -->
<script>
import ErrorMessage from '@/components/Error/ErrorMessage.vue';
import SquaresLoader from '@/components/Loaders/SquaresLoader.vue';
import router from '@/router';
import axios from 'axios';
import Swal from 'sweetalert2';

export default {
    components: { ErrorMessage, SquaresLoader },

    data() {
        return {
            id: 0,
            title: "",
            description: "",
            price: "",
            currentImageUrl: "",
            image: null,
            edit: false,
            errors: {},
            loaded: false
        }
    },

    methods: {
        /**
         * Fetches the product from the database if there is a parameter in the url resembling a product id
         */
        async getProduct() {
            const id = this.$route.params.id;

            if (id) {
                await axios.get(`/spa/products/${id}`)
                    .then((res) => {
                        res = res.data;

                        this.id = id;
                        this.title = res.data.title;
                        this.description = res.data.description;
                        this.price = res.data.price;
                        this.currentImageUrl = res.data.image_url;
                        this.edit = true;
                    })
                    .catch(err => console.log(err));
            }
            this.loaded = true;
        },

        /**
         * Handles the process of patching or posting the active element from the database by using a FormData objejct
         * to successfully send the image to the server
         */
        async handleSubmit() {
            let formData = new FormData();

            formData.append("title", this.title);
            formData.append("description", this.description);
            formData.append("price", this.price);


            if (this.image) {
                formData.append("image", this.image);
            }

            await axios.get("/sanctum/csrf-cookie");

            if (this.edit) {
                formData.append("_method", "PUT");

                await axios.post(`/spa/products/${this.id}`, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then((res) => {
                    console.log(res);

                    Swal.fire({
                        title: "Success",
                        text: "Product updated succesfully!",
                        icon: "success"
                    });

                    router.push({ name: "products" });

                }).catch((error) => {
                    this.errors = error.response.data.errors
                });
            } else {
                await axios.post(`/spa/products`, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then((res) => {
                    Swal.fire({
                        title: "Success",
                        text: "Product uploaded succesfully!",
                        icon: "success"
                    });

                    const createdId = res.data.data.id;

                    router.push({ name: "product", params: { id: createdId } })

                }).catch((error) => {
                    this.errors = error.response.data.errors;
                });
            }
        },

        /**
         * Changes the image variable based on an event
         * 
         * @param e 
         */
        handleImageChange(e) {
            this.image = e.target.files[0];
        },

        /**
         * Resets the used data from the page. Used when the route path changes
         */
        resetData() {
            this.id = 0;
            this.title = "";
            this.description = "";
            this.price = "";
            this.currentImageUrl = "";
            this.image = null;
            this.edit = false;
            this.errors = {};
            this.loaded = false;
        }
    },

    mounted() {
        this.getProduct();
    },

    watch: {
        '$route.path'() {
            this.resetData();
            this.getProduct();
        }
    }
}
</script>

<template>
    <div class="h-200 flex justify-center items-center text-white">
        <div v-if="!loaded" class="w-full h-200">
            <SquaresLoader class="mt-20 mx-auto" />
        </div>
        <div v-else class="bg-neutral-800 p-8 rounded-2xl shadow-lg w-128 border-1 border-violet-600">
            <h2 class="text-2xl font-semibold text-center text-white mb-6">
                {{ edit ? $t('editProduct') : $t('upload') }}
            </h2>

            <div class="w-full flex justify-center mb-2" v-if="edit">
                <img :src="currentImageUrl" alt="Product image" class="h-50">
            </div>

            <form @submit.prevent="handleSubmit()">
                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-gray-300">
                        {{ $t('title') }}
                    </label>
                    <input type="text" id="title" v-model="title"
                        class="w-full p-2 mt-2 border border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-violet-500"
                        :placeholder="$t('enterTitle')" />
                    <ErrorMessage :error="errors.title" />
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-300">
                        {{ $t('description') }}
                    </label>
                    <textarea id="description" v-model="description"
                        class="w-full p-2 mt-2 border border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-violet-500 resize-none"
                        :placeholder="$t('enterDescription')" rows="6">
                    </textarea>
                    <ErrorMessage :error="errors.description" />
                </div>

                <div class="mb-4">
                    <label for="price" class="block text-sm font-medium text-gray-300">
                        {{ $t('price') }}
                    </label>
                    <input type="text" id="price" v-model="price"
                        class="w-full p-2 mt-2 border border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-violet-500"
                        :placeholder="$t('enterPrice')" />
                    <ErrorMessage :error="errors.price" />
                </div>

                <div class="mb-4">
                    <label for="image" class="block text-sm font-medium text-gray-300">
                        {{ $t('image') }}
                    </label>
                    <input
                        class="mt-2 block w-full h-8 text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none"
                        type="file" id="image" @change="handleImageChange($event)" />
                    <ErrorMessage :error="errors.image" />
                </div>

                <div class="flex items-center justify-between">
                    <button type="submit"
                        class="cursor-pointer w-full py-2 px-4 bg-violet-600 text-white rounded-md hover:bg-violet-700 focus:outline-none focus:ring-2 focus:ring-violet-500">
                        {{ $t('publish') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>