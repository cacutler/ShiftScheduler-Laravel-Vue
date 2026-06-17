<script setup lang="ts">
import { Head, Form, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { store, index } from '@/routes/shifts';
import type { User } from '@/types';

type PageProps = {
    users?: User[];
};

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Shifts',
                href: index().url,
            },
            {
                title: 'Create shift',
            },
        ],
    },
});

const selectedUserId = ref<string>('');
</script>

<template>
    <Head title="Create shift" />
    <h1 class="sr-only">Create shift</h1>

    <div class="space-y-6">
        <div class="flex items-start justify-between">
            <Heading
                variant="small"
                title="Create shift"
                description="Create a new shift for your team"
            />
            <Link :href="index().url" as="button" class="text-xs text-muted-foreground hover:underline">
                Back to shifts
            </Link>
        </div>

        <Form v-bind="store.form()" class="space-y-6 max-w-lg" v-slot="{ errors, processing }">
            <div class="space-y-4">
                <div class="grid gap-2">
                    <Label for="start_date">Start Date <span class="text-red-500">*</span></Label>
                    <Input
                        id="start_date"
                        type="date"
                        name="start_date"
                        required
                        data-test="start-date-input"
                    />
                    <InputError class="mt-2" :message="errors.start_date" />
                </div>

                <div class="grid gap-2">
                    <Label for="end_date">End Date <span class="text-red-500">*</span></Label>
                    <Input
                        id="end_date"
                        type="date"
                        name="end_date"
                        required
                        data-test="end-date-input"
                    />
                    <InputError class="mt-2" :message="errors.end_date" />
                </div>

                <div class="grid gap-2">
                    <Label for="start_time">Start Time <span class="text-red-500">*</span></Label>
                    <Input
                        id="start_time"
                        type="time"
                        name="start_time"
                        required
                        data-test="start-time-input"
                    />
                    <InputError class="mt-2" :message="errors.start_time" />
                </div>

                <div class="grid gap-2">
                    <Label for="end_time">End Time <span class="text-red-500">*</span></Label>
                    <Input
                        id="end_time"
                        type="time"
                        name="end_time"
                        required
                        data-test="end-time-input"
                    />
                    <InputError class="mt-2" :message="errors.end_time" />
                </div>

                <div class="grid gap-2">
                    <Label for="user_id">Assign to Employee (Optional)</Label>
                    <Select v-model="selectedUserId" name="user_id">
                        <SelectTrigger id="user_id" data-test="user-select">
                            <SelectValue placeholder="Leave unassigned" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="">Leave unassigned</SelectItem>
                            <SelectItem v-for="u in ($props as PageProps).users" :key="u.id" :value="String(u.id)">
                                {{ u.name }}
                            </SelectItem>
                        </SelectContent>
                    </Select>
                    <InputError class="mt-2" :message="errors.user_id" />
                </div>
            </div>

            <div class="flex gap-2">
                <Button type="submit" :disabled="processing" data-test="create-shift-submit">
                    Create shift
                </Button>
                <Button as="a" type="button" variant="outline" :href="index().url">
                    Cancel
                </Button>
            </div>
        </Form>
    </div>
</template>
