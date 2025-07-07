<script setup lang="ts">
import Input from '@/Components/Input.vue';
import { UserAdmin } from '@/types';
import { useForm } from '@inertiajs/vue3';
import Button from 'primevue/button';

const { admin } = defineProps<{
    admin: UserAdmin;
}>();

const form = useForm({
    name: admin.name ?? '',
});

function submit() {
    form.post(route('admin.profile'), {
        preserveScroll: true,
    });
}
</script>

<template>
    <form
        class="h-lg container mx-auto flex w-full flex-col gap-4 rounded-md border border-gray-200 p-4 shadow-sm"
        @submit.prevent="submit"
    >
        <h3 class="mb-4 text-lg">Admin Information</h3>
        <Input
            id="name"
            label="Admin Name"
            v-model="form.name"
            class="max-w-xl"
            :error="form.errors.name"
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
