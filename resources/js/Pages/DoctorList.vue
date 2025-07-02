<script setup lang="ts">

    import AuthLayout from "@/Layouts/AuthLayout.vue";
    import Card from 'primevue/card';
    import Button from 'primevue/button';
    import {Doctor} from "@/types";
    import {Link, router} from "@inertiajs/vue3";

    defineProps<{
        doctors:  Doctor[]
    }>()
</script>

<template>
    <AuthLayout headerTitle="Doctors" >
        <section class="p-8">
            <div class="flex w-full justify-between">
                <h3 class="font-bold text-lg">Doctors</h3>
                <Link :href="route('doctors.create')" class="bg-green-500 px-3 py-2 rounded-md text-white"
                >Create</Link>
            </div>
            <div class="flex flex-wrap gap-4">
                <Card class="w-96"
                    v-for="doctor of doctors" :key="doctor.id"
                >
                    <template #title>Dr. {{doctor.name}}</template>
                    <template #subtitle>Expertise: {{doctor.specialty}}</template>
                    <template #content>
                        <div class="flex gap-4">
                            <Button label="See Schedule" @click="router.visit(route('schedule.view', doctor.id))" />
                        </div>
                    </template>
                </Card>
            </div>

        </section>
    </AuthLayout>
</template>

