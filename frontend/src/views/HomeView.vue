<script>
import ProductLoader from '@/components/Loaders/ProductLoader.vue';
import ProductCard from '@/components/Product/ProductCard.vue';
import axios from 'axios';

export default {
	components : {ProductCard, ProductLoader},
	data() {
		return { 
			products : [],
			loaded: false,
		}
	},
	
	mounted() {
		this.getProducts();
	},

	methods:{
		async getProducts() { 

			axios.get("http://localhost:8000/api/products")
				.then(response => {
					this.products = response.data.data;
				})
				.catch(error => {
					console.error("There was an error fetching the products:", error);
				});

			this.loaded = true;
		
		},

		
	}
}
</script>
<template>
	<h1 class="text-white text-center text-5xl mb-10">Browse products</h1>

	<div v-show="!loaded" class="w-full h-500">
		<ProductLoader class="mt-20 mx-auto"></ProductLoader>
	</div>
	
    <div class="w-full flex flex-wrap gap-4 justify-center">
		<ProductCard v-for="(product,i) in products" 
			:key="i" 
			:title="product.title" 
			:description="product.description"
			:image="product.image"
			:id="product.id"
		/>
    </div>
</template>
