<script>
import ProductCard from '@/components/Product/ProductCard.vue';


export default {
	components : {ProductCard},
	data() {
		return { 
			products : []
		}
	},

	mounted() {
		this.getProducts();
	},

	methods:{
		async getProducts() { 
			await fetch("http://localhost:8000/api/products")
				.then(res=>res.json())
				.then(res=>this.products = res.data)
		}
	}
}
</script>

<template>
	<h1 class="text-white text-center text-5xl mb-10">Browse products</h1>
    <div class="w-full flex flex-wrap gap-4">
		<ProductCard v-for="product in products" 
			:key="product.id" 
			:title="product.title" 
			:description="product.description"
			:image="product.image"
			:id="product.id"
		/>

    </div>
</template>
