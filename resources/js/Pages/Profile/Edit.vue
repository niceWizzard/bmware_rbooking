<script setup lang="ts">
import AuthLayout from '@/Layouts/AuthLayout.vue';
import AccountDeleteForm from '@/Pages/Profile/Partials/AccountDeleteForm.vue';
import PasswordChangeForm from '@/Pages/Profile/Partials/PasswordChangeForm.vue';
import PatientForm from '@/Pages/Profile/Partials/PatientForm.vue';
import ProfileForm from '@/Pages/Profile/Partials/ProfileForm.vue';
import { Patient } from '@/types';
import { Head } from '@inertiajs/vue3';

defineProps<{
    patient?: Patient;
}>();
</script>

<template>
    <AuthLayout header-title="Edit Profile">
        <Head title="Edit Profile" />
        <section class="flex flex-col items-center gap-4 p-8">
            <ProfileForm />
            <PatientForm v-if="patient" :patient="patient" />
            <PasswordChangeForm />
            <AccountDeleteForm />
        </section>
    </AuthLayout>
</template>
