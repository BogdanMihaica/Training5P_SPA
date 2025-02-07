<script>

import { RouterView } from 'vue-router'
import NavbarComponent from './components/Navbar/NavbarComponent.vue';
export default {
	components: { RouterView, NavbarComponent }
}
</script>

<template>
	<NavbarComponent class="z-100"/>
	<div class="z-0 w-full fixed blob left-100 top-0">
		<img src="@/assets/bg-blob.png" alt="bg" class="w-300 blur-2xl opacity-20">
	</div>
	<div class="wrapper mt-20 mx-5 z-10">
		<RouterView />
	</div>	
</template>

<style>
.blob{
	z-index:-100;
}
</style>