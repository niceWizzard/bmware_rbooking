<script setup lang="ts">
import AuthLayout from '@/Layouts/AuthLayout.vue';
import { Patient, User } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import axios from 'axios';
import { useConfirm, useToast } from 'primevue';
import Button from 'primevue/button';
import ConfirmPopup from 'primevue/confirmpopup';
import VirtualScroller from 'primevue/virtualscroller';

type UserPatient = User & { patient: Patient & { appointments_count: number } };

defineProps<{
    patients: UserPatient[];
}>();

const toast = useToast();
const confirm = useConfirm();

function onDeleteClick(userId: number) {
    confirm.require({
        message: 'Are you sure you want to delete this user?',
        acceptProps: {
            icon: 'pi pi-trash',
            label: 'Delete',
            severity: 'danger',
            variant: 'text',
        },
        rejectProps: {
            label: 'Cancel',
        },
        accept() {
            deleteUser(userId);
        },
    });
}

async function deleteUser(userId: number) {
    try {
        const res = await axios.delete(route('patient.delete', userId));
        if (!res.data.success) {
            throw new Error(res.data.message);
        }
        toast.add({
            severity: 'info',
            summary: 'Deleted the user',
            life: 3000,
        });
        router.reload();
    } catch (e) {
        const err = e as Error;
        console.error(err);
        toast.add({
            severity: 'error',
            detail: err.message,
            summary: 'Something went wrong.',
            life: 3000,
        });
    }
}
</script>

<template>
    <AuthLayout header-title="Patient List">
        <ConfirmPopup />
        <Head title="Patient List" />
        <section class="mx-auto p-6">
            <!-- Header -->
            <div
                class="flex gap-4 border-b border-gray-300 bg-primary px-4 py-2 text-sm font-semibold text-primary-contrast"
            >
                <span class="w-1/4">Name & Email</span>
                <span class="w-1/4"># of Appointments</span>
                <span class="w-1/2 text-right">Actions</span>
            </div>
            <p
                v-if="patients.length === 0"
                class="text-md font-light text-gray-500"
            >
                No users...
            </p>

            <!-- VirtualScroller -->
            <VirtualScroller
                :items="patients"
                :itemSize="60"
                style="width: 100%; height: 80vh"
            >
                <template #item="{ item, options }">
                    <div
                        :key="item.id"
                        :class="[
                            'flex items-center gap-4 px-4 py-3 transition',
                            options.odd ? 'bg-gray-200' : 'bg-gray-50',
                        ]"
                        style="height: 60px"
                    >
                        <!-- Column 1: Name & Email -->
                        <div class="w-1/4">
                            <div class="text-sm font-medium text-gray-900">
                                {{ item.patient.first_name }}
                                {{ item.patient.last_name }}
                            </div>
                            <div class="text-xs text-gray-600">
                                {{ item.email }}
                            </div>
                        </div>

                        <!-- Column 2: Appointment Count -->
                        <div class="w-1/4 text-sm text-gray-600">
                            {{ item.patient.appointments_count }}
                        </div>

                        <!-- Column 3: Actions -->
                        <div class="relative w-1/2 text-right">
                            <!-- Inline buttons on md+ -->
                            <div class="justify-end gap-2">
                                <Button
                                    label="Delete"
                                    icon="pi pi-trash"
                                    variant="text"
                                    severity="danger"
                                    @click="onDeleteClick(item.id)"
                                />
                            </div>
                        </div>
                    </div>
                </template>
            </VirtualScroller>
        </section>
    </AuthLayout>
</template>
