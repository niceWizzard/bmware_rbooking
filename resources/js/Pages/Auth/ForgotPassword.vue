<script setup lang="ts">
import Input from '@/Components/Input.vue';
import Layout from '@/Layouts/CardLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import Button from 'primevue/button';

defineProps<{
    status?: string;
}>();

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <Layout>
        <Head title="Forgot Password" />
        <template #header>
            <h3>Forgot Password</h3>
        </template>
        <div class="mb-4 text-sm text-gray-600">
            Forgot your password? No problem. Just let us know your email
            address and we will email you a password reset link that will allow
            you to choose a new one.
        </div>

        <form @submit.prevent="submit">
            <Input
                id="email"
                label="Email Address"
                v-model="form.email"
                :error="form.errors.email"
            />

            <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
                {{ status }}
            </div>
            <div class="mt-4 flex items-center justify-end">
                <Button
                    type="submit"
                    :class="{ 'opacity-25': form.processing }"
                    :loading="form.processing"
                    label="Email Password Reset Link"
                />
            </div>
        </form>
    </Layout>
</template>
