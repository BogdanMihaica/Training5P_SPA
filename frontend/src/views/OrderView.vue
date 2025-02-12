<script>
import SquaresLoader from '@/components/Loaders/SquaresLoader.vue';
import router from '@/router';
import axios from 'axios';

export default {
    components: { SquaresLoader },

    data() {
        return {
            products: [],
            customerName: "",
            customerEmail: "",
            createdAt: "",
            loaded: false
        }
    },

    methods: {
        /**
         * Fetches all the products corresponding to the order
         */
        async getOrderProducts() {
            const id = this.$route.params.id;
            console.log(id);

            await axios.get(`/spa/orders/${id}`)
                .then((res) => {
                    res = res.data;

                    this.products = res.data.products;
                    this.customerEmail = res.data.customer_email;
                    this.customerName = res.data.customer_name;
                    this.createdAt = res.data.created_at;
                    console.log(this.products);

                })
                .catch(err => console.log(err))

            if (!this.products.length) {
                router.push({ name: "notFound" });
            }

            this.loaded = true;
        },

        /**
         * Calculates the total cost of the order and returns it
         * 
         * @returns Number
         */
        computeGrandTotal() {
            let total = 0;

            if (this.products) {
                this.products.forEach(product => total += (product.price * product.quantity));
            }

            return total.toFixed(2);
        }
    },

    mounted() {
        this.getOrderProducts();
    }
}
</script>

<template>

    <div class="text-white w-full">

        <SquaresLoader v-if="!loaded" class="mx-auto" />
        <div v-else>
            <h1 class="text-5xl text-center"> {{ $t("order") + " #" + $route.params.id }}</h1>
            <div class="text-xl mb-10">
                <h2>{{ $t("customerName") + ": " + customerName }}</h2>
                <h2>{{ $t("customerEmail") + ": " + customerEmail }}</h2>
                <h2>{{ $t("createdAt") + ": " + createdAt }}</h2>
            </div>

            <div class="w-full flex flex-col justify-center items-center text-white">
                <table class="w-[90%] rounded-lg">
                    <thead>
                        <tr class="bg-violet-800">
                            <th class="rounded-tl-lg">
                                {{ $t('id') }}
                            </th>
                            <th>
                                {{ $t('image') }}
                            </th>
                            <th>
                                {{ $t('title') }}
                            </th>
                            <th>
                                {{ $t('description') }}
                            </th>
                            <th>
                                {{ $t('price') }}
                            </th>
                            <th>
                                {{ $t('quantity') }}
                            </th>
                            <th>
                                {{ $t('total') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="bg-neutral-800" v-for="(product) in products" :key="product.id">
                            <td>
                                {{ product.id }}
                            </td>
                            <td>
                                <img class="h-20 rounded-lg" :src="product.image_url" alt="Product image">
                            </td>
                            <td>
                                {{ product.title }}
                            </td>
                            <td>
                                {{ product.description }}
                            </td>
                            <td>
                                {{ product.price }}
                            </td>
                            <td>
                                {{ product.quantity }}
                            </td>
                            <td>
                                {{ product.quantity * product.price }}
                            </td>
                        </tr>

                        <tr class="bg-neutral-800">
                            <td colspan="5"></td>
                            <td>{{ $t('grandTotal') }}</td>
                            <td>{{ computeGrandTotal() }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
<style scoped>
th,
td {
    padding: 1rem;
    text-align: center;
}

td {
    border-bottom: 1px solid rgb(77, 77, 77);
}
</style>