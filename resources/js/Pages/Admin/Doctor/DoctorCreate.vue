<script setup lang="ts">
import Input from '@/Components/Input.vue';
import AuthLayout from '@/Layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import Button from 'primevue/button';
import FileUpload from 'primevue/fileupload';
const form = useForm({
    name: '',
    code: '',
    specialty: '',
    profile_picture: '',
});

function onSubmit() {
    form.post(route('doctor.create'));
}

function handleFileUpload(event: any) {
    if (event.files && event.files.length > 0) {
        form.profile_picture = event.files[0]; // Only grab the first file
    }
}
</script>

<template>
    <Head title="Create a doctor" />
    <AuthLayout headerTitle="Create Doctor">
        <section class="flex flex-col items-center gap-4 p-8">
            <h3 class="text-lg font-medium">Create a New Doctor</h3>
            <form
                @submit.prevent="onSubmit"
                class="mx-auto flex w-full max-w-xl flex-col gap-4"
                method="post"
            >
                <div class="flex flex-col gap-2">
                    <label>Doctor's Picture</label>
                    <FileUpload
                        mode="advanced"
                        name="demo[]"
                        accept="image/*"
                        :maxFileSize="1000000"
                        chooseLabel="Browse"
                        @select="handleFileUpload"
                        custom-upload
                    >
                        <template #empty>
                            <span>Drag and drop files to here to upload.</span>
                        </template>
                    </FileUpload>
                </div>
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
                <Button label="Cancel" severity="secondary" />
                <Button
                    type="submit"
                    label="Create"
                    :loading="form.processing"
                />
            </form>
        </section>
    </AuthLayout>
</template>
