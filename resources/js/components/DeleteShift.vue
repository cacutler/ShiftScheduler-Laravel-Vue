<script setup lang="ts">
import {Form} from '@inertiajs/vue3';
import {Button} from '@/components/ui/button';
import {Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger} from '@/components/ui/dialog';
import {destroy} from '@/routes/shifts';
import type {Shift} from '@/types';
type Props = {
    shift: Shift;
};
defineProps<Props>();
</script>
<template>
    <div class="space-y-6">
        <Dialog>
            <DialogTrigger as-child>
                <Button variant="destructive" data-test="delete-shift-button">Delete shift</Button>
            </DialogTrigger>
            <DialogContent>
                <Form :action="destroy(shift.id)" method="delete" reset-on-success :options="{preserveScroll: true}" class="space-y-6" v-slot="{ processing }">
                    <DialogHeader class="space-y-3">
                        <DialogTitle>Delete shift</DialogTitle>
                        <DialogDescription>Are you sure you want to delete this shift? This action cannot be undone.</DialogDescription>
                    </DialogHeader>
                    <DialogFooter>
                        <DialogClose as-child>
                            <Button type="button" variant="outline">Cancel</Button>
                        </DialogClose>
                        <Button type="submit" variant="destructive" :disabled="processing" data-test="confirm-delete-shift-button">Delete</Button>
                    </DialogFooter>
                </Form>
            </DialogContent>
        </Dialog>
    </div>
</template>