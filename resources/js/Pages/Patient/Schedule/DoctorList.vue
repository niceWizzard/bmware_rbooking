<script setup lang="ts">
import CardLayout from '@/Layouts/CardLayout.vue';
import { Doctor } from '@/types';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import Button from 'primevue/button';
import Card from 'primevue/card';

defineProps<{
    doctors: Doctor[];
}>();
const {
    props: { auth },
} = usePage();

function logout() {
    router.post(route('logout'));
}
</script>

<template>
    <Head title="List of Doctors" />
    <CardLayout class="w-full">
        <template #header>
            <div class="flex justify-end gap-2">
                <Link
                    :href="route('login')"
                    class="rounded-md px-3 py-2 text-sm font-light"
                    v-if="!auth.user"
                    >Login</Link
                >
                <Button
                    label="Logout"
                    variant="text"
                    severity="danger"
                    @click="logout"
                />
            </div>
            <h3 class="text-center text-2xl font-bold">Doctors</h3>
            <p class="text-center text-sm font-light text-gray-600">
                All the available doctors
            </p>
        </template>
        <div class="flex w-full max-w-4xl flex-wrap gap-4">
            <Card
                v-for="doctor in doctors"
                class="w-72"
                v-bind:key="'Doctor-' + doctor.id"
            >
                <template #title>
                    {{ doctor.name }}
                </template>
                <template #subtitle>Expertise: {{ doctor.specialty }}</template>
                <template #content>
                    <div class="flex justify-end gap-4">
                        <Link
                            :href="route('patient.book', { code: doctor.code })"
                            class="rounded-md bg-primary px-3 py-2 text-white"
                            v-bind:key="doctor.id"
                        >
                            Book Now
                        </Link>
                    </div>
                </template>
            </Card>
        </div>
    </CardLayout>
</template>
