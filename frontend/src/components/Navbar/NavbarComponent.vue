<script>
import NavbarLink from './NavbarLink.vue';

export default {
    components: { NavbarLink }
}
</script>

<template>
    <div
        class="top-0 w-full h-15 bg-gradient-to-r from-violet-500 to-violet-600 rounded-b-2xl fixed flex justify-start items-center">
        <div class="flex gap-5">
            <NavbarLink route="/" icon="fas fa-house" class="first:ml-5">Home</NavbarLink>
            <NavbarLink route="/cart" icon="fas fa-cart-shopping">Cart</NavbarLink>
            <NavbarLink route="/login" icon="fas fa-right-to-bracket">Login</NavbarLink>
        </div>
    </div>
</template>