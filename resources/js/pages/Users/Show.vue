<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import AppSidebarLayout from '@/layouts/app/AppSidebarLayout.vue';
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import DeleteUserComponent from '@/components/DeleteUserComponent.vue';
import { index, edit } from '@/routes/users';
import type { User, Shift } from '@/types';

type Props = {
    profile: User & { shifts?: Shift[] };
};

const breadcrumbs = [
    {
        title: 'Users',
        href: index().url,
    },
    {
        title: 'User profile',
    },
];

const props = defineProps<Props>();
const page = usePage();
const currentUser = computed(() => page.props.auth.user);
const isManager = computed(() => currentUser.value.status === 'manager');

const canDelete = computed(() => {
    if (!isManager.value) return false;
    return currentUser.value.id !== props.profile.id;
});

const formatDate = (date: string) => {
    return new Date(date).toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
    });
};

const formatTime = (time: string) => {
    return time.slice(0, 5);
};

const formatDateTime = (dateTime: string) => {
    return new Date(dateTime).toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
        hour: 'numeric',
        minute: '2-digit',
    });
};
</script>

<template>
    <AppSidebarLayout :breadcrumbs="breadcrumbs">
        <Head :title="`${profile.name} profile`" />
        <h1 class="sr-only">{{ profile.name }} profile</h1>

        <div class="space-y-6">
        <div class="flex items-start justify-between">
            <Heading
                variant="small"
                :title="profile.name"
                description="View user information and shifts"
            />
            <Link :href="index().url" as="button" class="text-xs text-muted-foreground hover:underline">
                Back to users
            </Link>
        </div>

        <div class="grid gap-6">
            <!-- User Information -->
            <div class="space-y-4 rounded-lg border p-6">
                <h2 class="font-semibold">User Information</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm font-medium text-muted-foreground">Name</p>
                        <p class="mt-1 text-base">{{ profile.name }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-muted-foreground">Username</p>
                        <p class="mt-1 text-base">{{ profile.username }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-muted-foreground">Email</p>
                        <p class="mt-1 text-base">{{ profile.email }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-muted-foreground">Phone</p>
                        <p class="mt-1 text-base">{{ profile.phone_number || 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-muted-foreground">Status</p>
                        <div class="mt-1">
                            <Badge>{{ profile.status }}</Badge>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-muted-foreground">Email verified</p>
                        <p class="mt-1 text-base">
                            {{ profile.email_verified_at ? formatDateTime(profile.email_verified_at) : 'Not verified' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- User Shifts -->
            <div v-if="profile.shifts && profile.shifts.length > 0" class="space-y-4 rounded-lg border p-6">
                <h2 class="font-semibold">Assigned Shifts</h2>
                <div class="overflow-hidden rounded-lg border">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Date</TableHead>
                                <TableHead>Time</TableHead>
                                <TableHead>Created</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="shift in profile.shifts" :key="shift.id">
                                <TableCell class="font-medium">
                                    {{ formatDate(shift.start_date) }}
                                    <span v-if="shift.end_date !== shift.start_date" class="text-xs text-muted-foreground">
                                        to {{ formatDate(shift.end_date) }}
                                    </span>
                                </TableCell>
                                <TableCell>
                                    {{ formatTime(shift.start_time) }} - {{ formatTime(shift.end_time) }}
                                </TableCell>
                                <TableCell class="text-sm text-muted-foreground">
                                    {{ formatDate(shift.created_at) }}
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </div>
            </div>

            <div v-else-if="profile.shifts" class="rounded-lg border border-dashed p-8 text-center">
                <p class="text-muted-foreground">No shifts assigned</p>
            </div>
        </div>

        <div class="flex gap-2">
            <Button as="a" :href="edit(profile.id).url" data-test="edit-user-button">
                Edit user
            </Button>
            <DeleteUserComponent v-if="canDelete" :user="profile" />
        </div>
        </div>
    </AppSidebarLayout>
</template>
