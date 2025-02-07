<script>
import ProductCard from '@/components/Product/ProductCard.vue';

export default {
    components : {
        ProductCard
    }
}
</script>

<template>
    <div class="w-full flex flex-wrap gap-4">
		<ProductCard :remove="true"/>
		<ProductCard :remove="true"/>
		<ProductCard :remove="true"/>
		<ProductCard :remove="true"/>
		<ProductCard :remove="true"/>
    </div>
</template>