<script setup lang="ts">

import AuthLayout from "@/Layouts/AuthLayout.vue";
import {Head, useForm, usePage} from "@inertiajs/vue3";
import ProfileForm from "@/Pages/Profile/ProfileForm.vue";
import AccountDeleteForm from "@/Pages/Profile/AccountDeleteForm.vue";

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
