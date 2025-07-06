<script setup lang="ts">
import AuthLayout from '@/Layouts/AuthLayout.vue';
import AccountDeleteForm from '@/Pages/Profile/Partials/AccountDeleteForm.vue';
import PasswordChangeForm from '@/Pages/Profile/Partials/PasswordChangeForm.vue';
import ProfileForm from '@/Pages/Profile/Partials/ProfileForm.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <AuthLayout header-title="Edit Profile">
        <Head title="Edit Profile" />
        <section class="flex flex-col items-center gap-4 p-8">
            <ProfileForm />
            <PasswordChangeForm />
            <AccountDeleteForm />
        </section>
    </AuthLayout>
</template>
