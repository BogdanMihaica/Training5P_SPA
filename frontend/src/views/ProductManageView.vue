<script>
import axios from 'axios';
import Swal from 'sweetalert2';

export default {
    data() {
        return {
            id: 0,
            title: "",
            description: "",
            price: 0,
            currentImageUrl: "",
            image: null,
            edit: false
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
        },

        async handleUpload() {
            console.log("uploading fsr");
        },

        /**
         * Handles the process of patching the active element from the database
         */
        async handleEdit() {


            let formData = new FormData();
            formData.append("title", this.title);
            formData.append("description", this.description);
            formData.append("price", this.price);

            if (this.image) {
                formData.append("image", this.image);
            }

            await axios.get("/sanctum/csrf-cookie");

            await axios.patch(`/spa/products/${this.id}`, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
                .then((res) => {
                    console.log(res);

                    Swal.fire({
                        title: "Success",
                        text: "Product updated succesfully!",
                        icon: "success"
                    });
                })
                .catch(err => console.log(err))
        },

        /**
         * Changes the image variable based on an event
         * 
         * @param e 
         */
        handleImageChange(e) {
            this.image = e.target.files[0]
        }
    },

    mounted() {
        this.getProduct();
    }

}
</script>

<template>
    <div class="h-200 flex justify-center items-center text-white">
        <div class="bg-neutral-800 p-8 rounded-2xl shadow-lg w-128 border-1 border-violet-600">
            <h2 class="text-2xl font-semibold text-center text-white mb-6">
                {{ edit ? $t('editProduct') : $t('upload') }}
            </h2>

            <div class="w-full flex justify-center mb-2">
                <img :src="currentImageUrl" alt="Product image" class="h-50">
            </div>

            <form @submit.prevent="edit ? handleEdit() : handleUpload()">
                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-gray-300">
                        {{ $t('title') }}
                    </label>
                    <input type="text" id="title" v-model="title"
                        class="w-full p-2 mt-2 border border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-violet-500"
                        :placeholder="$t('enterTitle')" />
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-300">
                        {{ $t('description') }}
                    </label>
                    <textarea id="description" v-model="description"
                        class="w-full p-2 mt-2 border border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-violet-500 resize-none"
                        :placeholder="$t('enterDescription')" rows="6">
                    </textarea>
                </div>

                <div class="mb-4">
                    <label for="price" class="block text-sm font-medium text-gray-300">
                        {{ $t('price') }}
                    </label>
                    <input type="text" id="price" v-model="price"
                        class="w-full p-2 mt-2 border border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-violet-500"
                        :placeholder="$t('enterPrice')" />
                </div>

                <div class="mb-4">
                    <label for="image" class="block text-sm font-medium text-gray-300">
                        {{ $t('image') }}
                    </label>
                    <input
                        class="mt-2 block w-full h-12text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none"
                        type="file" id="image" @change="handleImageChange($event)" />
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