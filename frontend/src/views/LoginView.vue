<script>
import { } from '@/common/helpers';
import axios from 'axios';
import Swal from 'sweetalert2';

export default {
    data() {
        return {
            email: '',
            password: ''
        };
    },
    methods: {
        /**
         * Handles the login form submission
         */
        async handleLogin() {
            const body = {
                email: this.email,
                password: this.password
            };

            await axios.get('/sanctum/csrf-cookie');

            await axios.post(`/spa/login`, body, {
                headers: {
                    'accept': 'application/json',
                },
            }).then((res) => {
                Swal.fire({
                    title: "Success",
                    text: "Successfully logged in!",
                    icon: "success"
                });

                let token = res.data;

                localStorage.setItem('token', token);
            }).catch(error => {
                Swal.fire({
                    title: "Ugh...",
                    text: error.response.data.message,
                    icon: "error"
                });
                console.log(error.response);

            });
        }
    }
};
</script>
<template>
    <div class="h-150 flex justify-center items-center text-white">
        <div class="bg-neutral-800 p-8 rounded-2xl shadow-lg w-96 border-1 border-violet-600">
            <h2 class="text-2xl font-semibold text-center text-white mb-6">{{ $t('login') }}</h2>
            <form @submit.prevent="handleLogin">
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-300">{{ $t('email') }}</label>
                    <input type="email" id="email" v-model="email"
                        class="w-full p-2 mt-2 border border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-violet-500"
                        :placeholder="$t('enterEmail')" />
                </div>
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-300">{{ $t('password') }}</label>
                    <input type="password" id="password" v-model="password"
                        class="w-full p-2 mt-2 border border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-violet-500"
                        :placeholder="$t('enterPassword')" />
                </div>
                <div class="flex items-center justify-between">
                    <button type="submit"
                        class="cursor-pointer w-full py-2 px-4 bg-violet-600 text-white rounded-md hover:bg-violet-700 focus:outline-none focus:ring-2 focus:ring-violet-500">
                        {{ $t('login') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>