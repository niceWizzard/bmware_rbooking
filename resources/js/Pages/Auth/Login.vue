<script setup lang="ts">
import Button from 'primevue/button'
import InputText from 'primevue/inputtext'
import CardLayout from '@/Layouts/CardLayout.vue'
import {Head} from "@inertiajs/vue3";
</script>

<template>
    <CardLayout class="w-full">
        <Head title="Login" />
        <template #header>
            <h3 class="text-2xl font-bold text-center">
                Login
            </h3>
        </template>
        <div class="w-96 flex flex-col gap-4 ">
            <InputText placeholder="Email" />
            <InputText placeholder="Password" type="password" />
            <Button >Login</Button>
            <p class=" font-light text-center">
                Don't have an account? <a :href="route('register')" class="font-medium text-primary">Register</a>
            </p>
        </div>
    </CardLayout>
</template>
