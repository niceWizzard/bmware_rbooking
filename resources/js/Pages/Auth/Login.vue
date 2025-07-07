<script setup lang="ts">
import Input from '@/Components/Input.vue';
import CardLayout from '@/Layouts/CardLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import Button from 'primevue/button';

const form = useForm({
    email: '',
    password: '',
});

function onFormSubmit() {
    form.post(route('login'));
}
</script>

<template>
    <Head title="Login" />
    <CardLayout class="w-full">
        <Head title="Login" />
        <template #header>
            <h3 class="text-center text-2xl font-bold">Login</h3>
        </template>
        <form @submit.prevent="onFormSubmit" class="flex w-96 flex-col gap-4">
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

            <Button type="submit" label="Login" :loading="form.processing" />
            <p class="text-center font-light">
                Don't have an account?
                <Link :href="route('register')" class="font-medium text-primary"
                    >Register</Link
                >
            </p>
            <Link
                :href="route('password.request')"
                class="text-center font-medium text-primary"
                >Forgot Password?</Link
            >
        </form>
    </CardLayout>
</template>
