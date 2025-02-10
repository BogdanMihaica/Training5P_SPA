<script>
import { useAuthStore } from '@/stores/authStore';
import NavbarLink from './NavbarLink.vue';
import { mapStores } from 'pinia';

export default {
    components: { NavbarLink },

    data() {
        return {
            loggedIn: false
        }
    },

    methods: {
        async handleLogout() {
            await this.authStore.logout();
        },
        isAuthenticated() {
            return this.authStore.isAuthenticated;
        }
    },

    computed: {
        ...mapStores(useAuthStore),
    }
}
</script>

<template>
    <div
        class="top-0 w-full h-15 bg-gradient-to-r from-violet-500 to-violet-600 rounded-b-2xl fixed flex justify-start items-center">
        <div class="flex gap-5">
            <NavbarLink :route="{ 'name': 'home' }" icon="fas fa-house" class="first:ml-5">{{ $t('home') }}</NavbarLink>
            <NavbarLink :route="{ 'name': 'cart' }" icon="fas fa-cart-shopping">{{ $t('cart') }}</NavbarLink>
            <NavbarLink :route="{ 'name': 'login' }" icon="fas fa-right-to-bracket" v-show="!isAuthenticated()">
                {{ $t('login') }}
            </NavbarLink>
            <span v-show="isAuthenticated()" class="flex gap-5">
                <NavbarLink :route="{ 'name': 'products' }" icon="fas fa-boxes-stacked">
                    {{ $t('products') }}
                </NavbarLink>
                <NavbarLink :route="{ 'name': 'orders' }" icon="fas fa-box">
                    {{ $t('orders') }}
                </NavbarLink>
                <NavbarLink :route="{ 'name': 'upload' }" icon="fas fa-upload">
                    {{ $t('upload') }}
                </NavbarLink>
                <button class="text-white rounded-lg min-w-20 px-4 py-2 bg-red-800 transition-all duration-500 flex justify-center items-center text-lg
                    hover:bg-red-600 cursor-pointer" @click.prevent="handleLogout">
                    {{ $t('logout') }}
                </button>
            </span>

        </div>
    </div>
</template>