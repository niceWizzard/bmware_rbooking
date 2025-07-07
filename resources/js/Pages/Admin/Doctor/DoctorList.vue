<script setup lang="ts">
import AuthLayout from '@/Layouts/AuthLayout.vue';
import { Doctor } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import axios from 'axios';
import { useConfirm, useToast } from 'primevue';
import Button from 'primevue/button';
import Card from 'primevue/card';
import ConfirmDialog from 'primevue/confirmdialog';

defineProps<{
    doctors: Doctor[];
}>();

const confirm = useConfirm();
const toast = useToast();

function deleteDoctor(id: number) {
    confirm.require({
        message: 'This is irreversible. Please confirm',
        header: 'Delete doctor?',
        icon: 'pi pi-exclamation-triangle',
        rejectProps: {
            label: 'Cancel',
            severity: 'secondary',
            outlined: true,
        },
        acceptProps: {
            label: 'Remove Doctor',
            severity: 'danger',
        },
        accept: async () => {
            try {
                const res = await axios.delete(
                    route('doctor.delete', { id: id }),
                );
                if (!res.data.success) throw new Error(res.data.message);
                toast.add({
                    severity: 'success',
                    summary: 'Doctor deleted successfully',
                });
                window.location.reload();
            } catch (e) {
                const err = e as Error;
                toast.add({
                    severity: 'error',
                    detail: err.message,
                });
            }
        },
    });
}
</script>

<template>
    <Head title="List of Doctors" />
    <ConfirmDialog></ConfirmDialog>
    <AuthLayout headerTitle="Doctors">
        <section class="p-8">
            <div class="flex w-full justify-between">
                <h3 class="text-lg font-bold">Doctors</h3>
                <Link
                    :href="route('doctor.create')"
                    class="rounded-md bg-primary px-3 py-2 text-white"
                    >Create</Link
                >
            </div>
            <div class="flex flex-wrap gap-4">
                <Card
                    class="w-[32rem]"
                    v-for="doctor of doctors"
                    :key="doctor.id"
                >
                    <template #title>Dr. {{ doctor.name }}</template>
                    <template #subtitle
                        >Expertise: {{ doctor.specialty }}</template
                    >
                    <template #content>
                        <div class="flex gap-4">
                            <template v-if="doctor.schedules_exists">
                                <Button
                                    label="See Schedule"
                                    @click="
                                        router.visit(
                                            route('schedule.view', doctor.id),
                                        )
                                    "
                                />
                                <Link
                                    :href="route('schedule.edit', doctor.id)"
                                    class="rounded-md bg-primary px-3 py-2 text-white"
                                    >Edit Schedule</Link
                                >
                                <Link
                                    :href="
                                        route('admin.dashboard', {
                                            code: doctor.code,
                                        })
                                    "
                                    class="rounded-md px-3 py-2 bg-highlight"
                                    >See Appointments</Link
                                >
                            </template>
                            <Link
                                :href="route('schedule.create', doctor.id)"
                                v-else
                                class="rounded-md bg-primary px-3 py-2 text-white"
                                >Create Schedule</Link
                            >
                            <Button
                                label="Delete"
                                icon="pi pi-trash"
                                @click="deleteDoctor(doctor.id)"
                                severity="danger"
                            />
                        </div>
                    </template>
                </Card>
                <p class="text-sm text-gray-600" v-if="doctors.length === 0">
                    No Doctors yet...
                </p>
            </div>
        </section>
    </AuthLayout>
</template>
