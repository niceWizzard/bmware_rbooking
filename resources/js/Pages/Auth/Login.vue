<script setup lang="ts">
import Button from 'primevue/button'
import CardLayout from '@/Layouts/CardLayout.vue'
import {Head, useForm} from "@inertiajs/vue3";
import Input from '@/Components/Input.vue'

const form = useForm({
    email: '',
    password: '',
});

function onFormSubmit() {
    form.post(route('login'))
}

</script>

<template>
    <CardLayout class="w-full">
        <Head title="Login" />
        <template #header>
            <h3 class="text-2xl font-bold text-center">
                Login
            </h3>
        </template>
        <form @submit.prevent="onFormSubmit"
              class="w-96 flex flex-col gap-4">
            <Input
                :error="form.errors.email"
                id="email"
                label="Email"
                type="email"
                v-model="form.email"
            />

            <Input
                :error="form.errors.password"
                id="password"
                label="Password"
                type="password"
                v-model="form.password"
            />

            <Button
                type="submit"
                label="Login"
                :loading="form.processing"
            />
            <p class=" font-light text-center">
                Don't have an account? <Link :href="route('register')" class="font-medium text-primary">Register</Link>
            </p>
        </form>
    </CardLayout>
</template>
