<script>
import SquaresLoader from '@/components/Loaders/SquaresLoader.vue';
import axios from 'axios';

export default {
    components: { SquaresLoader },

    data() {
        return {
            orders: [],
            loaded: false
        }
    },

    mounted() {
        this.getOrders();
    },

    methods: {
        /**
         * Async function that fetches for the orders that are not in the cart
         */
        async getOrders() {

            await axios.get("/spa/orders")
                .then(response => {
                    this.orders = response.data.data;
                })
                .catch(error => {
                    console.error("There was an error fetching the orders:", error);
                });
            this.loaded = true;
        },
    }
}
</script>

<template>
    <h1 class="text-white text-center text-5xl mb-10">{{ $t('ordersDashboard') }}</h1>
    <div class="w-full flex flex-col justify-center items-center text-white">
        <SquaresLoader v-if="!loaded" />

        <table v-else class="w-[90%] rounded-lg">
            <thead>
                <tr class="bg-violet-800">
                    <th class="rounded-tl-lg">
                        {{ $t('id') }}
                    </th>
                    <th>
                        {{ $t('customerName') }}
                    </th>
                    <th>
                        {{ $t('customerEmail') }}
                    </th>
                    <th>
                        {{ $t('createdAt') }}
                    </th>
                    <th class="rounded-tr-lg">
                        {{ $t('actions') }}
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr class="bg-neutral-800" v-for="(order) in orders" :key="order.id">
                    <td>
                        {{ order.id }}
                    </td>
                    <td>
                        {{ order.customer_name }}
                    </td>
                    <td>
                        {{ order.customer_email }}
                    </td>
                    <td>
                        {{ order.created_at }}
                    </td>
                    <td>
                        <RouterLink
                            class="mx-auto px-4 py-1 bg-blue-500 rounded-lg cursor-pointer hover:bg-blue-600 focus:ring-blue-400 focus:ring-1"
                            :to="{ name: 'order', params: { id: order.id } }">
                            {{ $t('viewOrder') }}
                        </RouterLink>
                    </td>
                </tr>
            </tbody>
        </table>
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