<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import {Table, TableBody, TableCell, TableHead, TableHeader, TableRow} from '@/components/ui/table';
import { Badge } from '@/components/ui/badge';
import { create, edit, index, show } from '@/routes/users';
import type { User } from '@/types';
type Props = {
    users: User[];
};
defineProps<Props>();
const breadcrumbs = [
    {
        text: 'Users',
        href: index().url,
    },
];

const statusColor = (status: string) => {
    return status === 'manager' ? 'default' : 'secondary';
};

const formatDate = (date: string | null) => {
    if (!date) return 'Not verified';
    return new Date(date).toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
    });
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Users" />
        <h1 class="sr-only">Users</h1>

        <div class="space-y-6">
        <div class="flex items-center justify-between">
            <Heading
                variant="small"
                title="Users"
                :description="`Manage ${users.length} user${users.length === 1 ? '' : 's'}`"
            />
            <Button as="a" :href="create().url" data-test="create-user-button">
                Create user
            </Button>
        </div>

        <div class="overflow-hidden rounded-lg border">
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead>Name</TableHead>
                        <TableHead>Email</TableHead>
                        <TableHead>Status</TableHead>
                        <TableHead>Email verified</TableHead>
                        <TableHead class="text-right">Actions</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="user in users" :key="user.id">
                        <TableCell class="font-medium">{{ user.name }}</TableCell>
                        <TableCell>{{ user.email }}</TableCell>
                        <TableCell>
                            <Badge :variant="statusColor(user.status)">
                                {{ user.status }}
                            </Badge>
                        </TableCell>
                        <TableCell>{{ formatDate(user.email_verified_at) }}</TableCell>
                        <TableCell class="text-right">
                            <div class="flex justify-end gap-2">
                                <Link
                                    :href="show(user.id).url"
                                    as="button"
                                    class="text-xs text-blue-600 hover:underline"
                                    data-test="view-user-button"
                                >
                                    View
                                </Link>
                                <Link
                                    :href="edit(user.id).url"
                                    as="button"
                                    class="text-xs text-blue-600 hover:underline"
                                    data-test="edit-user-button"
                                >
                                    Edit
                                </Link>
                            </div>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>

        <div v-if="users.length === 0" class="rounded-lg border border-dashed p-8 text-center">
            <p class="text-muted-foreground">No users yet</p>
            <Button as="a" variant="outline" :href="create().url" class="mt-4">
                Create the first user
            </Button>
        </div>
        </div>
    </AppLayout>
</template>
