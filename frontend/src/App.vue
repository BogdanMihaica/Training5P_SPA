<script>

import { RouterView } from 'vue-router'
import NavbarComponent from './components/Navbar/NavbarComponent.vue';

export default {
	components: { RouterView, NavbarComponent }
}
</script>

<template>
	<NavbarComponent />

	<RouterView />
</template>