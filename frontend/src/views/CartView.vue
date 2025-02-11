<script>
import ErrorMessage from '@/components/Error/ErrorMessage.vue';
import SquaresLoader from '@/components/Loaders/SquaresLoader.vue';
import ProductCard from '@/components/Product/ProductCard.vue';
import axios from 'axios';
import Swal from 'sweetalert2';

export default {
	components: { ProductCard, SquaresLoader, ErrorMessage },

	data() {
		return {
			products: [],
			loaded: false,
			checkoutOpen: false,
			name: '',
			email: '',
			errors: {}
		}
	},

	mounted() {
		this.getProducts();
	},

	methods: {
		/**
		 * Fetches the products corresponding with the server session variable 'cart'
		 */
		async getProducts() {

			await axios.get("/spa/cart")
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
		},

		/**
		 * Toggles the value of checkoutOpen
		 */
		toggleCheckout() {
			this.checkoutOpen = !this.checkoutOpen;
		},

		async handleCheckout() {
			const body = {
				name: this.name,
				email: this.email
			}

			await axios.get('/sanctum/csrf-cookie');

			await axios.post(`/spa/orders`, body).then(() => {
				Swal.fire({
					title: "Success",
					text: "Order placed successfully!",
					icon: "success"
				});

				this.products = [];
			}).catch(error => {
				this.errors = error.response.data.errors
				console.log(error.response);
			});

		}
	}
}
</script>

<template>
	<h1 class="text-white text-center text-5xl mb-10">{{ $t('yourShoppingCart') }}</h1>

	<div v-show="!loaded" class="w-full h-500">
		<SquaresLoader class="mt-20 mx-auto"></SquaresLoader>
	</div>

	<div class="w-full flex flex-wrap gap-4 justify-center">
		<ProductCard v-for="(product, i) in products" :key="product.id" :title="product.title"
			:description="product.description" :imageUrl="product.image_url" :id="product.id" :price="product.price"
			@removed="removeItemFromList(i)" :cart="true" :bought-quantity="product.quantity" />
	</div>

	<div class="my-6 w-full flex justify-center items-center flex-col">
		<button class=" cursor-pointer w-40 py-2 px-4 bg-violet-600 text-white rounded-md 
			hover:bg-violet-700 focus:outline-none focus:ring-2 focus:ring-violet-500" v-if="!checkoutOpen"
			@click.prevent="toggleCheckout()">
			{{ $t('checkout') }}
		</button>

		<div class="w-100 h-auto py-5 bg-neutral-800 border-1 border-violet-600 flex justify-center items-center flex-col rounded-lg"
			v-else>

			<div class="w-full flex flex-row-reverse">
				<button class="cursor-pointer w-40 text-white" @click.prevent="toggleCheckout()">
					<i class="fas fa-x"></i>
				</button>
			</div>

			<h2 class="text-2xl font-semibold text-center text-white mb-6">{{ $t('checkout') }}</h2>

			<form @submit.prevent="handleLogin" class="w-[70%]">
				<div class="mb-4">
					<label for="customer-email" class="block text-sm font-medium text-gray-300">
						{{ $t('email') }}
					</label>
					<input type="email" id="customer-email" v-model="email"
						class="text-white w-full p-2 mt-2 border border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-violet-500"
						:placeholder="$t('enterEmail')" />
					<ErrorMessage :error="errors.email" />
				</div>

				<div class="mb-6">
					<label for="name" class="block text-sm font-medium text-gray-300">{{ $t('name') }}</label>
					<input type="text" id="name" v-model="name"
						class="text-white w-full p-2 mt-2 border border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-violet-500"
						:placeholder="$t('enterName')" />
					<ErrorMessage :error="errors.name" />
				</div>

				<div class="flex items-center justify-between">
					<button type="submit" class="cursor-pointer w-full py-2 px-4 bg-violet-600 text-white rounded-md 
						hover:bg-violet-700 focus:outline-none focus:ring-2 focus:ring-violet-500" @click.prevent="handleCheckout">
						{{ $t('submit') }}
					</button>
				</div>
				<ErrorMessage :error="errors.cart" />
			</form>
		</div>
	</div>
</template>
