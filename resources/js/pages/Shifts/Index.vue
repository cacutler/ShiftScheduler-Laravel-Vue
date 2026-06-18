<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import {Table, TableBody, TableCell, TableHead, TableHeader, TableRow} from '@/components/ui/table';
import { Badge } from '@/components/ui/badge';
import { create, index, show } from '@/routes/shifts';
import type { Shift, User } from '@/types';
type Props = {
    shifts: (Shift & { user?: User })[];
};
defineProps<Props>();
const breadcrumbs = [
    {
        text: 'Shifts',
        href: index().url,
    }
];
const page = usePage();
const user = computed(() => page.props.auth.user);
const isManager = computed(() => user.value.status === 'manager');
const formatDate = (date: string) => {
    return new Date(date).toLocaleDateString('en-US', {month: 'short', day: 'numeric', year: 'numeric'});
};
const formatTime = (time: string) => {
    return time.slice(0, 5); // HH:mm format
};
const shiftStatus = (shift: Shift & { user?: User }) => {
    if (shift.user) {
        return shift.user.id === user.value.id ? 'Your shift' : 'Assigned';
    }
    return 'Open';
};
const shiftStatusColor = (shift: Shift & { user?: User }) => {
    if (!shift.user) return 'default';
    return shift.user.id === user.value.id ? 'default' : 'secondary';
};
</script>
<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Shifts" />
        <h1 class="sr-only">Shifts</h1>
        <div class="space-y-6">
        <div class="flex items-center justify-between">
            <Heading variant="small" title="Shifts" :description="`You have ${shifts.length} shift${shifts.length === 1 ? '' : 's'}`"/>
            <Button as="a" v-if="isManager" :href="create().url" data-test="create-shift-button">Create shift</Button>
        </div>
        <div class="overflow-hidden rounded-lg border">
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead>Date</TableHead>
                        <TableHead>Time</TableHead>
                        <TableHead>Assigned to</TableHead>
                        <TableHead>Status</TableHead>
                        <TableHead class="text-right">Actions</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="shift in shifts" :key="shift.id">
                        <TableCell class="font-medium">
                            {{formatDate(shift.start_date)}}
                            <span v-if="shift.end_date !== shift.start_date" class="text-xs text-muted-foreground">to {{formatDate(shift.end_date)}}</span>
                        </TableCell>
                        <TableCell>{{formatTime(shift.start_time)}} - {{formatTime(shift.end_time)}}</TableCell>
                        <TableCell>
                            <span v-if="shift.user">{{ shift.user.name }}</span>
                            <span v-else class="text-muted-foreground">Unassigned</span>
                        </TableCell>
                        <TableCell>
                            <Badge :variant="shiftStatusColor(shift)">{{shiftStatus(shift)}}</Badge>
                        </TableCell>
                        <TableCell class="text-right">
                            <div class="flex justify-end gap-2">
                                <Link
                                    :href="show(shift.id).url"
                                    as="button"
                                    class="text-xs text-blue-600 hover:underline"
                                    data-test="view-shift-button"
                                >
                                    View
                                </Link>
                                <Link
                                    v-if="isManager || !shift.user"
                                    :href="show(shift.id).url"
                                    as="button"
                                    class="text-xs text-blue-600 hover:underline"
                                    data-test="edit-shift-button"
                                >
                                    {{isManager ? 'Edit' : 'Claim'}}
                                </Link>
                            </div>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>
        <div v-if="shifts.length === 0" class="rounded-lg border border-dashed p-8 text-center">
            <p class="text-muted-foreground">No shifts yet</p>
            <Button as="a" v-if="isManager" variant="outline" :href="create().url" class="mt-4">Create the first shift</Button>
        </div>
        </div>
    </AppLayout>
</template>