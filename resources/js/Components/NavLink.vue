<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: String,
    active: Boolean,
});

const classes = computed(() => {
    return props.active
        ? 'inline-flex items-center px-1 pt-1 text-lg font-semibold leading-5 text-white  hover:text-gray-100 focus:outline-none transition duration-150 ease-in-out'
        : 'inline-flex items-center px-1 pt-1 text-lg font-semibold leading-5 text-white hover:text-gray-100 focus:outline-none focus:text-gray-100 transition duration-150 ease-in-out';
});
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>
