<script setup lang="ts">
import Input from '@/Components/Input.vue';
import Layout from '@/Layouts/CardLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import Button from 'primevue/button';

const props = defineProps<{
    email: string;
    token: string;
}>();

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => {
            form.reset('password', 'password_confirmation');
        },
    });
};
</script>

<template>
    <Layout>
        <Head title="ResetPassword" />
        <template #header>
            <h3 class="text-lg font-medium">Reset your Password</h3>
        </template>
        <form @submit.prevent="submit" class="flex flex-col gap-3">
            <Input
                label="Email Address"
                id="email"
                name="email"
                v-model="form.email"
                :error="form.errors.email"
            />
            <Input
                label="New Password"
                id="password"
                name="password"
                type="password"
                v-model="form.password"
                :error="form.errors.password"
            />
            <Input
                label="Confirm your Password"
                id="password_confirmation"
                name="password_confirmation"
                type="password"
                v-model="form.password_confirmation"
                :error="form.errors.password_confirmation"
            />
            <Button label="Reset Password" type="submit" />
        </form>
    </Layout>
</template>
