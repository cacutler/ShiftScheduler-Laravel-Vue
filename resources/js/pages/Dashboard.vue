<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { route } from 'ziggy-js';
defineOptions({layout: AppLayout});
const page = usePage();
const user = computed(() => page.props.auth.user);
const isManager = computed(() => user.value?.status === 'manager');
</script>
<template>
    <Head title="Dashboard" />
    <div class="space-y-6">
        <div class="rounded-lg border bg-background p-6 shadow-sm">
            <h1 class="text-2xl font-semibold">Dashboard</h1>
            <p class="mt-2 text-sm text-muted-foreground">View your current shift schedule and access user management if permitted.</p>
        </div>
        <div class="grid gap-4 md:grid-cols-2">
            <div class="rounded-lg border p-6">
                <h2 class="text-lg font-semibold">Shifts</h2>
                <p class="mt-2 text-sm text-muted-foreground">
                    Browse shifts and manage assignments according to your role.
                </p>
                <div class="mt-4">
                    <Button as="a" :href="route('shifts.index')">View shifts</Button>
                </div>
            </div>
            <div v-if="isManager" class="rounded-lg border p-6">
                <h2 class="text-lg font-semibold">Users</h2>
                <p class="mt-2 text-sm text-muted-foreground">
                    Manage team users and review profiles. Only managers can access this section.
                </p>
                <div class="mt-4">
                    <Button as="a" :href="route('users.index')">View users</Button>
                </div>
            </div>
        </div>
    </div>
</template>