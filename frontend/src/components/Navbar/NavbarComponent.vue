<script>
import NavbarLink from './NavbarLink.vue';
import axios from 'axios';

export default {
    components: { NavbarLink },

    data() {
        return {
            loggedIn: false
        }
    },

    methods: {
        async getUser(token) {
            await axios.get('/spa/user')
                .then((res) => {
                    this.loggedIn = true;
                    console.log(token);
                    console.log(res.data);

                })
                .catch(error => {
                    console.error("There was an error fetching the user:", error);
                });
        }
    },

    mounted() {
        const token = localStorage.getItem('token');
        if (token) {
            this.getUser(token);
        }
    }
}
</script>

<template>
    <div
        class="top-0 w-full h-15 bg-gradient-to-r from-violet-500 to-violet-600 rounded-b-2xl fixed flex justify-start items-center">
        <div class="flex gap-5">
            <NavbarLink :route="{ 'name': 'home' }" icon="fas fa-house" class="first:ml-5">Home</NavbarLink>
            <NavbarLink :route="{ 'name': 'cart' }" icon="fas fa-cart-shopping">Cart</NavbarLink>
            <NavbarLink :route="{ 'name': 'login' }" icon="fas fa-right-to-bracket" v-if="!loggedIn">Login</NavbarLink>
            <span v-else class=" flex ">
                <NavbarLink :route="{ 'name': 'login' }" icon="fas fa-boxes-stacked">Products</NavbarLink>
                <NavbarLink :route="{ 'name': 'login' }" icon="fas fa-box">Orders</NavbarLink>
                <NavbarLink :route="{ 'name': 'login' }" icon="fas fa-upload">Upload</NavbarLink>
                <NavbarLink :route="{ 'name': 'login' }" icon="fas fa-right-from-bracket">Logout</NavbarLink>
            </span>
        </div>
    </div>
</template>