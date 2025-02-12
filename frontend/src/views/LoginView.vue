<script>
import ErrorMessage from '@/components/Error/ErrorMessage.vue';
import { useAuthStore } from '@/stores/authStore';


export default {
    components: { ErrorMessage },

    data() {
        return {
            email: "",
            password: "",
            errors: {}
        };
    },

    methods: {
        /**
         * Handles the login form submission
         */
        async handleLogin() {
            let authStore = useAuthStore();
            this.errors = await authStore.login(this.email, this.password)
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
                    <ErrorMessage :error="errors.email" />
                </div>

                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-300">{{ $t('password') }}</label>
                    <input type="password" id="password" v-model="password"
                        class="w-full p-2 mt-2 border border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-violet-500"
                        :placeholder="$t('enterPassword')" />
                    <ErrorMessage :error="errors.password" />
                </div>

                <div class="flex items-center justify-between">
                    <button type="submit"
                        class="cursor-pointer w-full py-2 px-4 bg-violet-600 text-white rounded-md hover:bg-violet-700 focus:outline-none focus:ring-2 focus:ring-violet-500">
                        {{ $t('login') }}
                    </button>
                </div>
                <ErrorMessage :error="errors.credentials" />
            </form>
        </div>
    </div>
</template>