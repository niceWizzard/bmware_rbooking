<script setup lang="ts">

import AuthLayout from "@/Layouts/AuthLayout.vue";
import {Head} from "@inertiajs/vue3";
import ProfileForm from "@/Pages/Profile/Partials/ProfileForm.vue";
import AccountDeleteForm from "@/Pages/Profile/Partials/AccountDeleteForm.vue";

</script>

<template>
    <AuthLayout header-title="Edit Profile">
        <Head title="Edit Profile" />
        <section class="p-8 flex flex-col items-center gap-4">
            <ProfileForm />
            <AccountDeleteForm />
        </section>
    </AuthLayout>
</template>
