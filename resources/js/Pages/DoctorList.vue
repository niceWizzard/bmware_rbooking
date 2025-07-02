<script setup lang="ts">

    import AuthLayout from "@/Layouts/AuthLayout.vue";
    import Card from 'primevue/card';
    import Button from 'primevue/button';
    import {Doctor} from "@/types";
    import { Link } from "@inertiajs/vue3";

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
                <Card style="width: 25rem; overflow: hidden"
                    v-for="doctor of doctors" :key="doctor.id"
                >
                    <template #title>{{doctor.name}}</template>
                    <template #subtitle>{{doctor.specialty}}</template>
                    <template #footer>
                        <div class="flex gap-4 mt-1">
                            <Button label="Cancel" severity="secondary" outlined class="w-full" />
                            <Button label="Save" class="w-full" />
                        </div>
                    </template>
                </Card>
            </div>

        </section>
    </AuthLayout>
</template>

