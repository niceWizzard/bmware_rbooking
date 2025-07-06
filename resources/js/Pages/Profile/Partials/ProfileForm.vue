<script setup>
import Input from '@/Components/Input.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import Button from 'primevue/button';

const {
    props: {
        auth: { user },
    },
} = usePage();

const form = useForm({
    email: user?.email,
});

function submit() {
    form.post(route('profile.update'));
}
</script>

<template>
    <form
        class="h-lg container mx-auto flex w-full flex-col gap-4 rounded-md border border-gray-200 p-4 shadow-sm"
        @submit.prevent="submit"
    >
        <h3 class="mb-4 text-lg">Profile Information</h3>
        <Input
            id="email"
            label="Email"
            v-model="form.email"
            class="max-w-xl"
            :error="form.errors.email"
        />
        <Button type="submit" label="Save" class="w-fit self-start" />
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
