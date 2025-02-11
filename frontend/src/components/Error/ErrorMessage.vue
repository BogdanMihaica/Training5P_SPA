<script>
export default {
    props: ['error']
}
</script>

<template>
    <p class="text-red-500" v-if="error">{{ error[0] }}</p>
</template>