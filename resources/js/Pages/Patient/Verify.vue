<script setup lang="ts">
import Input from '@/Components/Input.vue';
import Layout from '@/Layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import Button from 'primevue/button';
import FloatLabel from 'primevue/floatlabel';
import InputMask from 'primevue/inputmask';

const props = defineProps<{
    mobile: string;
    first_name: string;
    last_name: string;
    verificationStatus?: string;
}>();

const form = useForm({
    mobile: props.mobile,
    first_name: props.first_name,
    last_name: props.last_name,
});

function submit() {
    form.transform((v) => ({
        ...v,
        mobile: v.mobile.replaceAll('-', ''),
    })).post(route('patient.verify.verify'), {
        preserveScroll: true,
    });
}
</script>

<template>
    <Layout header-title="Verify Patient Details">
        <Head title="Verify" />
        <section class="flex w-full flex-col p-8">
            <h3 class="text-lg font-medium">Verify your Information</h3>
            <p class="mb-4 font-light text-gray-500">
                Existing details have been found in our records. Please verify
                your records to continue.
                <br />
                <br />
                Either change your mobile number or confirm your details to
                match our records.
            </p>
            <form @submit.prevent="submit" class="flex flex-col gap-2">
                <div class="flex flex-wrap gap-2">
                    <Input
                        id="first_name"
                        label="First Name"
                        v-model="form.first_name"
                        :error="form.errors.first_name"
                    />
                    <Input
                        label="Last Name"
                        v-model="form.last_name"
                        :error="form.errors.last_name"
                        id="last_name"
                    />
                </div>
                <div class="relative flex flex-col">
                    <FloatLabel variant="on">
                        <InputMask
                            id="mobile"
                            v-model="form.mobile"
                            placeholder="Enter mobile number"
                            mask="0999-999-9999"
                            class="w-full"
                        />
                        <label for="mobile">Mobile Number</label>
                        <p
                            class="font-light text-red-600"
                            v-if="form.errors.mobile"
                        >
                            {{ form.errors.mobile }}
                        </p>
                    </FloatLabel>
                </div>
                <p class="text-center text-red-500" v-if="verificationStatus">
                    {{ verificationStatus }}
                </p>
                <Button
                    type="submit"
                    label="Continue"
                    :loading="form.processing"
                />
            </form>
        </section>
    </Layout>
</template>
