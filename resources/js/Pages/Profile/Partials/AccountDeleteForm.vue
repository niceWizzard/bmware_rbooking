<script setup>
import Input from '@/Components/Input.vue';
import { useForm } from '@inertiajs/vue3';
import Button from 'primevue/button';

const form = useForm({
    password: '',
});

function submit() {
    form.delete(route('profile.destroy'), {
        preserveScroll: true,
    });
}
</script>

<template>
    <form
        class="h-lg container mx-auto flex w-full flex-col gap-4 rounded-md border border-gray-200 p-4 shadow-sm"
        @submit.prevent="submit"
    >
        <h3 class="text-lg">Delete your account</h3>
        <p class="text-md font-light text-gray-600">
            This action is irreversible. Enter your password to continue.
        </p>
        <Input
            v-model="form.password"
            name="password"
            id="del_password"
            label="Password"
            :error="form.errors.password"
        />
        <Button
            type="submit"
            label="Delete your account"
            class="w-fit self-start"
            severity="danger"
            icon="pi pi-trash"
            :loading="form.processing"
        />
    </form>
</template>
