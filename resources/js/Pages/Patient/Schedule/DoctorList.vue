<script setup lang="ts">
    import {Doctor} from "@/types";
    import CardLayout from "@/Layouts/CardLayout.vue";
    import Card from 'primevue/card'
    import {Link} from "@inertiajs/vue3";
    defineProps<{
        doctors: Doctor[];
    }>()
</script>

<template>
    <CardLayout class="w-full">
        <template #header>
            <h3 class="text-2xl font-bold text-center">
                Doctors
            </h3>
            <p class="text-sm font-light text-center text-gray-600">
                All the available doctors
            </p>
        </template>
        <div class="w-full max-w-4xl flex flex-wrap gap-4">
            <Card
                v-for="doctor in doctors"
                class="w-72"
            >
                <template #title>
                    {{doctor.name}}
                </template>
                <template #subtitle>Expertise: {{doctor.specialty}}</template>
                <template #content>
                    <div class="flex gap-4 justify-end">
                        <Link
                            :href="route('patient.book', {code: doctor.code})"
                            class="px-3 py-2 rounded-md bg-green-600 text-white"
                        >
                            Book Now
                        </Link>
                    </div>
                </template>
            </Card>
        </div>
    </CardLayout>
</template>
