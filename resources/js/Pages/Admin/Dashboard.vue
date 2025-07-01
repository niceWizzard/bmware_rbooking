<script setup lang="ts">
import Button from 'primevue/button'
import {router, usePage} from '@inertiajs/vue3'
import AuthLayout from "@/Layouts/AuthLayout.vue";

const {props} = usePage();
const {user} = props.auth;

</script>

<template>
    <AuthLayout header-title="Admin Dashboard">
        <div class="p-8">
            <div v-if="user">
                <p >
                    {{user}}
                </p>
            </div>
            <p v-else>
                NO USER
            </p>
        </div>
    </AuthLayout>
</template>


