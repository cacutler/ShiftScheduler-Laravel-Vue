<script setup lang="ts">
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';

type BreadcrumbItem = {
    text: string;
    href?: string;
    active?: boolean;
};

const { breadcrumbs = [] } = defineProps<{
    breadcrumbs?: BreadcrumbItem[];
}>();
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <slot />
    </AppLayout>
</template>
