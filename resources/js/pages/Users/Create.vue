<script setup lang="ts">
import { Head, Form, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
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
import { store, index } from '@/routes/users';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Users',
                href: index().url,
            },
            {
                title: 'Create user',
            },
        ],
    },
});

const selectedStatus = ref<string>('employee');
</script>

<template>
    <Head title="Create user" />
    <h1 class="sr-only">Create user</h1>

    <div class="space-y-6">
        <div class="flex items-start justify-between">
            <Heading
                variant="small"
                title="Create user"
                description="Add a new user to your team"
            />
            <Link :href="index().url" as="button" class="text-xs text-muted-foreground hover:underline">
                Back to users
            </Link>
        </div>

        <Form v-bind="store.form()" class="space-y-6 max-w-lg" v-slot="{ errors, processing }">
            <div class="space-y-4">
                <div class="grid gap-2">
                    <Label for="name">Full Name <span class="text-red-500">*</span></Label>
                    <Input
                        id="name"
                        type="text"
                        name="name"
                        required
                        placeholder="John Doe"
                        autocomplete="name"
                        data-test="name-input"
                    />
                    <InputError class="mt-2" :message="errors.name" />
                </div>

                <div class="grid gap-2">
                    <Label for="username">Username <span class="text-red-500">*</span></Label>
                    <Input
                        id="username"
                        type="text"
                        name="username"
                        required
                        placeholder="johndoe"
                        autocomplete="username"
                        data-test="username-input"
                    />
                    <InputError class="mt-2" :message="errors.username" />
                </div>

                <div class="grid gap-2">
                    <Label for="email">Email Address <span class="text-red-500">*</span></Label>
                    <Input
                        id="email"
                        type="email"
                        name="email"
                        required
                        placeholder="john@example.com"
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
                        placeholder="+1 (555) 000-0000"
                        autocomplete="tel"
                        data-test="phone-input"
                    />
                    <InputError class="mt-2" :message="errors.phone_number" />
                </div>

                <div class="grid gap-2">
                    <Label for="password">Password <span class="text-red-500">*</span></Label>
                    <PasswordInput
                        id="password"
                        name="password"
                        required
                        placeholder="Password"
                        data-test="password-input"
                    />
                    <InputError class="mt-2" :message="errors.password" />
                </div>

                <div class="grid gap-2">
                    <Label for="password_confirmation">Confirm Password <span class="text-red-500">*</span></Label>
                    <PasswordInput
                        id="password_confirmation"
                        name="password_confirmation"
                        required
                        placeholder="Confirm password"
                        data-test="password-confirmation-input"
                    />
                    <InputError class="mt-2" :message="errors.password_confirmation" />
                </div>

                <div class="grid gap-2">
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
            </div>

            <div class="flex gap-2">
                <Button type="submit" :disabled="processing" data-test="create-user-submit">
                    Create user
                </Button>
                <Button as="a" type="button" variant="outline" :href="index().url">
                    Cancel
                </Button>
            </div>
        </Form>
    </div>
</template>
