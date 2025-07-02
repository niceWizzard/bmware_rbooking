<script setup lang="ts">

import AuthLayout from "@/Layouts/AuthLayout.vue";
import {useForm} from "@inertiajs/vue3";
import Input from '@/Components/Input.vue'
import Button from 'primevue/button';


const form = useForm({
    name: '',
    code: '',
    specialty: '',
});

function onSubmit() {
    form.post(route('doctor.create'));
}

</script>

<template>
    <AuthLayout headerTitle="Create Doctor" >
        <section class="p-8 flex flex-col gap-4 items-center">
            <h3 class="text-lg font-medium">
                Create a New Doctor
            </h3>
            <form
                @submit.prevent="onSubmit"
                class="flex flex-col gap-4 max-w-xl mx-auto w-full"
                method="post"
            >
                <Input
                    id="name"
                    label="Doctor's Name"
                    :error="form.errors.name"
                    v-model="form.name"
                />
                <Input
                    id="code"
                    label="Doctor's Code"
                    :error="form.errors.code"
                    v-model="form.code"
                />
                <Input
                    id="specialty"
                    label="Specialization"
                    :error="form.errors.specialty"
                    v-model="form.specialty"
                />
                <Button
                    label="Cancel"
                    severity="secondary"
                />
                <Button type="submit"
                    label="Create"
                    :loading="form.processing"
                />
            </form>
        </section>
    </AuthLayout>
</template>

