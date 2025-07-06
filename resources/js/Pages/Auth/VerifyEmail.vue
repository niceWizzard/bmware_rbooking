<script setup lang="ts">
import Layout from '@/Layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import Button from 'primevue/button';
import { computed } from 'vue';

const props = defineProps<{
    status?: string;
}>();

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(
    () => props.status === 'verification-link-sent',
);
</script>

<template>
    <Layout header-title="Verify your email address">
        <Head title="Verify your Email" />
        <div class="p-8">
            <div class="mb-4 text-sm text-gray-600">
                Thanks for signing up! Before getting started, could you verify
                your email address by clicking on the link we just emailed to
                you? If you didn't receive the email, we will gladly send you
                another.
            </div>

            <div
                class="text-primary mb-4 text-sm font-medium"
                v-if="verificationLinkSent"
            >
                A new verification link has been sent to the email address you
                provided during registration.
            </div>

            <form @submit.prevent="submit">
                <div class="mt-4 flex items-center justify-between">
                    <Button type="submit" :disabled="form.processing">
                        Resend Verification Email
                    </Button>
                </div>
            </form>
        </div>
    </Layout>
</template>
