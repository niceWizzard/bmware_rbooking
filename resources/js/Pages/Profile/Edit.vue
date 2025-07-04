<script setup lang="ts">

import AuthLayout from "@/Layouts/AuthLayout.vue";
import {Head, useForm, usePage} from "@inertiajs/vue3";
import ProfileForm from "@/Pages/Profile/ProfileForm.vue";





</script>

<template>
    <AuthLayout header-title="Edit Profile">
        <Head title="Edit Profile" />
        <section class="p-8 flex flex-col items-center">
            <ProfileForm />
        </section>
    </AuthLayout>
</template>
