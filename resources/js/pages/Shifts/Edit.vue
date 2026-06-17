<script setup lang="ts">
import { Head, Form, Link, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
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
import { update, index, show } from '@/routes/shifts';
import type { Shift, User } from '@/types';

type Props = {
    shift: Shift & { user?: User };
    users?: User[];
};

const props = defineProps<Props>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Shifts',
                href: index().url,
            },
            {
                title: 'Edit shift',
            },
        ],
    },
});

const page = usePage();
const user = computed(() => page.props.auth.user);
const isManager = computed(() => user.value.status === 'manager');

const selectedUserId = ref<string>(props.shift.user_id ? String(props.shift.user_id) : '');

const formatTimeForInput = (time: string) => {
    // Assuming time is already in HH:mm:ss format
    return time.slice(0, 5);
};

const title = computed(() => {
    if (isManager.value) return 'Edit shift';
    return 'Claim shift';
});

const description = computed(() => {
    if (isManager.value) return 'Update shift information';
    return 'Claim this unassigned shift';
});
</script>

<template>
    <Head :title="title" />
    <h1 class="sr-only">{{ title }}</h1>

    <div class="space-y-6">
        <div class="flex items-start justify-between">
            <Heading
                variant="small"
                :title="title"
                :description="description"
            />
            <Link :href="show(shift.id).url" as="button" class="text-xs text-muted-foreground hover:underline">
                Back to shift
            </Link>
        </div>

        <Form :action="update(shift.id)" method="put" class="space-y-6 max-w-lg" v-slot="{ errors, processing }">
            <div class="space-y-4">
                <div v-if="isManager" class="grid gap-2">
                    <Label for="start_date">Start Date</Label>
                    <Input
                        id="start_date"
                        type="date"
                        name="start_date"
                        :default-value="shift.start_date"
                        data-test="start-date-input"
                    />
                    <InputError class="mt-2" :message="errors.start_date" />
                </div>

                <div v-if="isManager" class="grid gap-2">
                    <Label for="end_date">End Date</Label>
                    <Input
                        id="end_date"
                        type="date"
                        name="end_date"
                        :default-value="shift.end_date"
                        data-test="end-date-input"
                    />
                    <InputError class="mt-2" :message="errors.end_date" />
                </div>

                <div v-if="isManager" class="grid gap-2">
                    <Label for="start_time">Start Time</Label>
                    <Input
                        id="start_time"
                        type="time"
                        name="start_time"
                        :default-value="formatTimeForInput(shift.start_time)"
                        data-test="start-time-input"
                    />
                    <InputError class="mt-2" :message="errors.start_time" />
                </div>

                <div v-if="isManager" class="grid gap-2">
                    <Label for="end_time">End Time</Label>
                    <Input
                        id="end_time"
                        type="time"
                        name="end_time"
                        :default-value="formatTimeForInput(shift.end_time)"
                        data-test="end-time-input"
                    />
                    <InputError class="mt-2" :message="errors.end_time" />
                </div>

                <div v-if="isManager" class="grid gap-2">
                    <Label for="user_id">Assign to Employee (Optional)</Label>
                    <Select v-model="selectedUserId" name="user_id">
                        <SelectTrigger id="user_id" data-test="user-select">
                            <SelectValue placeholder="Leave unassigned" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="">Leave unassigned</SelectItem>
                            <SelectItem v-for="u in users" :key="u.id" :value="String(u.id)">
                                {{ u.name }}
                            </SelectItem>
                        </SelectContent>
                    </Select>
                    <InputError class="mt-2" :message="errors.user_id" />
                </div>

                <div v-if="!isManager" class="rounded-lg border border-blue-100 bg-blue-50 p-4 dark:border-blue-200/10 dark:bg-blue-700/10">
                    <p class="text-sm text-blue-900 dark:text-blue-100">
                        Clicking "Claim shift" will assign this shift to you.
                    </p>
                </div>
            </div>

            <div class="flex gap-2">
                <Button type="submit" :disabled="processing" data-test="update-shift-submit">
                    {{ isManager ? 'Update shift' : 'Claim shift' }}
                </Button>
                <Button as="a" type="button" variant="outline" :href="show(shift.id).url">
                    Cancel
                </Button>
            </div>
        </Form>
    </div>
</template>
