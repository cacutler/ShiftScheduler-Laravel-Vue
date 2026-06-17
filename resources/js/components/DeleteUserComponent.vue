<script setup lang="ts">
import { Form } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import {Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger} from '@/components/ui/dialog';
import {destroy} from '@/routes/users';
import type {User} from '@/types';
type Props = {
    user: User;
};
defineProps<Props>();
</script>
<template>
    <div class="space-y-6">
        <Dialog>
            <DialogTrigger as-child>
                <Button variant="destructive" data-test="delete-user-button">Delete user</Button>
            </DialogTrigger>
            <DialogContent>
                <Form :action="destroy(user.id)" method="delete" reset-on-success :options="{preserveScroll: true}" class="space-y-6" v-slot="{ processing }">
                    <DialogHeader class="space-y-3">
                        <DialogTitle>Delete user</DialogTitle>
                        <DialogDescription>Are you sure you want to delete <strong>{{user.name}}</strong>?  This action cannot be undone.  All associated shifts will also be affected.</DialogDescription>
                    </DialogHeader>
                    <DialogFooter>
                        <DialogClose as-child>
                            <Button type="button" variant="outline">Cancel</Button>
                        </DialogClose>
                        <Button type="submit" variant="destructive" :disabled="processing" data-test="confirm-delete-user-button">Delete</Button>
                    </DialogFooter>
                </Form>
            </DialogContent>
        </Dialog>
    </div>
</template>