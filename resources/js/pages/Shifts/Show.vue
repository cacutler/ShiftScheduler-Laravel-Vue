<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import AppSidebarLayout from '@/layouts/app/AppSidebarLayout.vue';
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import DeleteShift from '@/components/DeleteShift.vue';
import { index, edit } from '@/routes/shifts';
import type { Shift, User } from '@/types';

type Props = {
    shift: Shift & { user?: User };
};

const breadcrumbs = [
    {
        title: 'Shifts',
        href: index().url,
    },
    {
        title: 'Shift details',
    },
];

const props = defineProps<Props>();
const page = usePage();
const user = computed(() => page.props.auth.user);
const isManager = computed(() => user.value.status === 'manager');

const canEdit = computed(() => {
    if (isManager.value) return true;
    return !props.shift.user; // Employees can only edit if unassigned
});

const formatDate = (date: string) => {
    return new Date(date).toLocaleDateString('en-US', {
        weekday: 'long',
        month: 'long',
        day: 'numeric',
        year: 'numeric',
    });
};

const formatTime = (time: string) => {
    return time.slice(0, 5); // HH:mm format
};

const dateRange = () => {
    if (props.shift.start_date === props.shift.end_date) {
        return formatDate(props.shift.start_date);
    }
    return `${formatDate(props.shift.start_date)} to ${formatDate(props.shift.end_date)}`;
};
</script>

<template>
    <AppSidebarLayout :breadcrumbs="breadcrumbs">
        <Head title="Shift details" />
        <h1 class="sr-only">Shift details</h1>

        <div class="space-y-6">
        <div class="flex items-start justify-between">
            <Heading
                variant="small"
                title="Shift details"
                description="View and manage shift information"
            />
            <div class="flex gap-2">
                <Link :href="index().url" as="button" class="text-xs text-muted-foreground hover:underline">
                    Back to shifts
                </Link>
            </div>
        </div>

        <div class="space-y-4 rounded-lg border p-6">
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm font-medium text-muted-foreground">Date Range</p>
                        <p class="mt-1 text-base">{{ dateRange() }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-muted-foreground">Time</p>
                        <p class="mt-1 text-base">
                            {{ formatTime(shift.start_time) }} - {{ formatTime(shift.end_time) }}
                        </p>
                    </div>
                </div>

                <div>
                    <p class="text-sm font-medium text-muted-foreground">Assigned to</p>
                    <div class="mt-1 flex items-center gap-2">
                        <p class="text-base">
                            {{ shift.user ? shift.user.name : 'Unassigned' }}
                        </p>
                        <Badge v-if="shift.user">
                            {{ shift.user.id === user.id ? 'Your shift' : 'Assigned' }}
                        </Badge>
                        <Badge v-else variant="default">Open</Badge>
                    </div>
                </div>

                <div v-if="shift.user" class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm font-medium text-muted-foreground">Email</p>
                        <p class="mt-1 text-base">{{ shift.user.email }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-muted-foreground">Phone</p>
                        <p class="mt-1 text-base">{{ shift.user.phone_number || 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex gap-2">
            <Button
                v-if="canEdit"
                :href="edit(shift.id).url"
                data-test="edit-shift-button"
            >
                {{ isManager ? 'Edit' : 'Claim shift' }}
            </Button>
            <DeleteShift v-if="isManager" :shift="shift" />
        </div>
        </div>
    </AppSidebarLayout>
</template>
