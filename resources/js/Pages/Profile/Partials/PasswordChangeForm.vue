<script setup>
import Input from '@/Components/Input.vue';
import { useForm } from '@inertiajs/vue3';
import Button from 'primevue/button';

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

function submit() {
    form.post(route('profile.password'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
        },
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
            }
            if (form.errors.current_password) {
                form.reset('current_password');
            }
        },
    });
}
</script>

<template>
    <form
        class="h-lg container mx-auto flex w-full flex-col gap-4 rounded-md border border-gray-200 p-4 shadow-sm"
        @submit.prevent="submit"
    >
        <h3 class="text-lg">Profile Information</h3>
        <p class="text-md font-light text-gray-600">
            To change your password, enter your current password
        </p>
        <Input
            id="current_password"
            label="Current Password"
            v-model="form.current_password"
            class="max-w-xl"
            :error="form.errors.current_password"
            type="password"
        />
        <Input
            id="password"
            label="Current Password"
            v-model="form.password"
            class="max-w-xl"
            :error="form.errors.password"
            type="password"
        />
        <Input
            id="password_confirmation"
            label="Confirm New Password"
            v-model="form.password_confirmation"
            class="max-w-xl"
            :error="form.errors.password_confirmation"
            type="password"
        />
        <Button
            type="submit"
            label="Save"
            class="w-fit self-start"
            :loading="form.processing"
        />
        <Transition
            enter-active-class="transition ease-in-out"
            enter-from-class="opacity-0"
            leave-active-class="transition ease-in-out"
            leave-to-class="opacity-0"
        >
            <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">
                Saved.
            </p>
        </Transition>
    </form>
</template>
