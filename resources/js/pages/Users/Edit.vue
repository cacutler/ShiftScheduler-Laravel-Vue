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
import { update, index, show } from '@/routes/users';
import type { User } from '@/types';

type Props = {
    profile: User;
};

const props = defineProps<Props>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Users',
                href: index().url,
            },
            {
                title: 'Edit user',
            },
        ],
    },
});

const page = usePage();
const currentUser = computed(() => page.props.auth.user);
const isManager = computed(() => currentUser.value.status === 'manager');

const selectedStatus = ref<string>(props.profile.status);

const title = computed(() => {
    if (currentUser.value.id === props.profile.id) {
        return 'Edit profile';
    }
    return 'Edit user';
});

const description = computed(() => {
    if (currentUser.value.id === props.profile.id) {
        return 'Update your profile information';
    }
    return 'Update user information';
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
            <Link :href="show(profile.id).url" as="button" class="text-xs text-muted-foreground hover:underline">
                Back to profile
            </Link>
        </div>

        <Form :action="update(profile.id)" method="put" class="space-y-6 max-w-lg" v-slot="{ errors, processing }">
            <div class="space-y-4">
                <div class="grid gap-2">
                    <Label for="name">Full Name <span class="text-red-500">*</span></Label>
                    <Input
                        id="name"
                        type="text"
                        name="name"
                        required
                        :default-value="profile.name"
                        autocomplete="name"
                        data-test="name-input"
                    />
                    <InputError class="mt-2" :message="errors.name" />
                </div>

                <div class="grid gap-2">
                    <Label for="username">Username</Label>
                    <Input
                        id="username"
                        type="text"
                        name="username"
                        :default-value="profile.username"
                        autocomplete="username"
                        disabled
                        class="disabled:cursor-not-allowed disabled:opacity-50"
                    />
                    <p class="text-xs text-muted-foreground">Username cannot be changed</p>
                </div>

                <div class="grid gap-2">
                    <Label for="email">Email Address <span class="text-red-500">*</span></Label>
                    <Input
                        id="email"
                        type="email"
                        name="email"
                        required
                        :default-value="profile.email"
                        autocomplete="email"
                        data-test="email-input"
                    />
                    <InputError class="mt-2" :message="errors.email" />
                </div>

                <div class="grid gap-2">
                    <Label for="phone_number">Phone Number (Optional)</Label>
                    <Input
                        id="phone_number"
                        type="tel"
                        name="phone_number"
                        :default-value="profile.phone_number ?? undefined"
                        autocomplete="tel"
                        data-test="phone-input"
                    />
                    <InputError class="mt-2" :message="errors.phone_number" />
                </div>

                <div v-if="isManager && currentUser.id !== profile.id" class="grid gap-2">
                    <Label for="status">Role <span class="text-red-500">*</span></Label>
                    <Select v-model="selectedStatus" name="status" required>
                        <SelectTrigger id="status" data-test="status-select">
                            <SelectValue placeholder="Select role" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="employee">Employee</SelectItem>
                            <SelectItem value="manager">Manager</SelectItem>
                        </SelectContent>
                    </Select>
                    <InputError class="mt-2" :message="errors.status" />
                </div>

                <div v-if="!isManager" class="rounded-lg border border-blue-100 bg-blue-50 p-4 dark:border-blue-200/10 dark:bg-blue-700/10">
                    <p class="text-sm text-blue-900 dark:text-blue-100">
                        You can only update your own profile information.
                    </p>
                </div>
            </div>

            <div class="flex gap-2">
                <Button type="submit" :disabled="processing" data-test="update-user-submit">
                    Update
                </Button>
                <Button as="a" type="button" variant="outline" :href="show(profile.id).url">
                    Cancel
                </Button>
            </div>
        </Form>
    </div>
</template>
