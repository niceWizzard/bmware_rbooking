<script setup lang="ts">
import Input from '@/Components/Input.vue';
import AuthLayout from '@/Layouts/AuthLayout.vue';
import { Doctor } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import Button from 'primevue/button';
import FileUpload from 'primevue/fileupload';
import { defineProps, onUnmounted, ref } from 'vue';

const props = defineProps<{
    doctor: Doctor;
}>();

const form = useForm({
    name: props.doctor.name,
    code: props.doctor.code,
    specialty: props.doctor.specialty,
    profile_picture: null,
});
const previewUrl = ref<string | null>(null);

function onSubmit() {
    form.post(route('doctor.update', props.doctor.id), {
        preserveScroll: true,
    });
}

function handleFileUpload(event: any) {
    if (event.files && event.files.length > 0) {
        const file = event.files[0];
        form.profile_picture = file;
        previewUrl.value = URL.createObjectURL(file); // create temporary preview
    }
}

onUnmounted(() => {
    if (previewUrl.value) {
        URL.revokeObjectURL(previewUrl.value);
    }
});
</script>

<template>
    <Head title="Edit Doctor" />
    <AuthLayout headerTitle="Edit Doctor">
        <section class="flex flex-col items-center gap-4 p-8">
            <h3 class="text-lg font-medium">Update Doctor Details</h3>
            <form
                @submit.prevent="onSubmit"
                class="mx-auto flex w-full max-w-xl flex-col gap-4"
                method="post"
            >
                <div class="flex flex-col gap-2">
                    <label>Doctor's Picture</label>
                    <FileUpload
                        accept="image/*"
                        :maxFileSize="1000000"
                        chooseLabel="Browse"
                        @select="handleFileUpload"
                        custom-upload
                    >
                        <template #empty>
                            <span>Drag and drop files to here to upload.</span>
                        </template>
                        <template #content>
                            <div
                                v-if="
                                    previewUrl || props.doctor.profile_picture
                                "
                                class="mt-2"
                            >
                                <img
                                    :src="
                                        previewUrl ||
                                        `/storage/${props.doctor.profile_picture}`
                                    "
                                    alt="Profile Preview"
                                    class="h-24 w-24 rounded-full object-cover"
                                />
                            </div>
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
                <div class="flex gap-2">
                    <Button label="Cancel" severity="secondary" />
                    <Button
                        type="submit"
                        label="Update"
                        :loading="form.processing"
                    />
                </div>
            </form>
        </section>
    </AuthLayout>
</template>
