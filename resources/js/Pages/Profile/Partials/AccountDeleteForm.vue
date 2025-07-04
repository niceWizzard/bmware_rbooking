<script setup>
import {useForm, usePage} from "@inertiajs/vue3";
import Input from '@/Components/Input.vue';
import Button from 'primevue/button';

const {props: {
    auth: {user}
}} = usePage();

const form = useForm({
    password: '',
});

function submit() {
    form.delete(route('profile.destroy'))
}

</script>


<template>
    <form
        class="flex container mx-auto flex-col gap-4 p-4 rounded-md shadow-sm border border-gray-200 w-full h-lg"
        @submit.prevent="submit"
    >
        <h3 class="text-lg">Delete your account</h3>
        <p class="text-md font-light text-gray-600">
            This action is irreversible. Enter your password to continue.
        </p>
        <Input
            v-model="form.password"
            name="password"
            id="password"
            label="Password"
            :error="form.errors.password"
        />
        <Button
            type="submit"
            label="Delete your account"
            class="w-fit self-start"
            severity="danger"
            icon="pi pi-trash"
        />
    </form>
</template>
