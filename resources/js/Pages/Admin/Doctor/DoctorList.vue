<script setup lang="ts">
import AuthLayout from '@/Layouts/AuthLayout.vue';
import { Doctor } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import Button from 'primevue/button';
import Card from 'primevue/card';

defineProps<{
    doctors: Doctor[];
}>();
</script>

<template>
    <Head title="List of Doctors" />
    <AuthLayout headerTitle="Doctors">
        <section class="p-8">
            <div class="flex w-full justify-between">
                <h3 class="text-lg font-bold">Doctors</h3>
                <Link
                    :href="route('doctor.create')"
                    class="rounded-md bg-green-500 px-3 py-2 text-white"
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
                                    class="rounded-md bg-green-500 px-3 py-2 text-white"
                                    >Edit Schedule</Link
                                >
                                <Link
                                    :href="
                                        route('admin.dashboard', {
                                            code: doctor.code,
                                        })
                                    "
                                    class="rounded-md bg-green-700 px-3 py-2 text-white"
                                    >See Appointments</Link
                                >
                            </template>
                            <Link
                                :href="route('schedule.create', doctor.id)"
                                v-else
                                class="rounded-md bg-green-600 px-3 py-2 text-white"
                                >Create Schedule</Link
                            >
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
