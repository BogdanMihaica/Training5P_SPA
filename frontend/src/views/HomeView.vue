<script>
import SquaresLoader from '@/components/Loaders/SquaresLoader.vue';
import ProductCard from '@/components/Product/ProductCard.vue';
import axios from 'axios';

export default {
	components: { ProductCard, SquaresLoader },

	data() {
		return {
			products: [],
			loaded: false,
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

			await axios.get("/spa/products")
				.then(response => {
					this.products = response.data.data;
				})
				.catch(error => {
					console.error("There was an error fetching the products:", error);
				});

			this.loaded = true;

		},

		/**
		 * Removes an item from the products array by an index
		 * 
		 * @param index 
		 */
		removeItemFromList(index) {
			this.products.splice(index, 1)
		}
	}
}
</script>
<template>
	<h1 class="text-white text-center text-5xl mb-10">{{ $t('browse') }}</h1>

	<div v-show="!loaded" class="w-full h-500">
		<SquaresLoader class="mt-20 mx-auto" />
	</div>

	<div class="w-full flex flex-wrap gap-4 justify-center">
		<ProductCard v-for="(product, i) in products" :key="product.id" :title="product.title"
			:description="product.description" :imageUrl="product.image_url" :id="product.id" :index="i"
			:price="product.price" @added="removeItemFromList(i)" />
	</div>
</template>
