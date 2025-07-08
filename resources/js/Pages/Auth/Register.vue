<script setup lang="ts">
import Input from '@/Components/Input.vue';
import CardLayout from '@/Layouts/CardLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import Button from 'primevue/button';
import FloatLabel from 'primevue/floatlabel';
import InputMask from 'primevue/inputmask';

defineProps<{}>();
const form = useForm({
    email: '',
    password: '',
    password_confirmation: '',
    first_name: '',
    last_name: '',
    mobile: '',
});

function submit() {
    form.transform((v) => ({
        ...v,
        mobile: v.mobile.replaceAll('-', ''),
    })).post(route('register'));
}
</script>

<template>
    <CardLayout>
        <Head title="Register" />
        <template #header>
            <h3 class="text-center text-2xl font-bold">Register</h3>
        </template>
        <form @submit.prevent="submit" class="flex w-96 flex-col gap-4">
            <div class="flex gap-2">
                <Input
                    id="first_name"
                    label="First Name"
                    :error="form.errors.first_name"
                    v-model="form.first_name"
                    type="text"
                />
                <Input
                    id="last_name"
                    label="Last Name"
                    :error="form.errors.last_name"
                    v-model="form.last_name"
                    type="text"
                />
            </div>
            <Input
                id="email"
                label="Email Address"
                :error="form.errors.email"
                v-model="form.email"
                type="email"
            />
            <FloatLabel variant="on">
                <label>Mobile Number</label>
                <InputMask
                    mask="0999-999-9999"
                    v-model="form.mobile"
                    class="w-full"
                />
                <p
                    class="mt-1 text-sm font-light text-red-500"
                    v-if="form.errors.mobile"
                >
                    {{ form.errors.mobile }}
                </p>
            </FloatLabel>
            <Input
                id="password"
                label="Password"
                :error="form.errors.password"
                v-model="form.password"
                type="password"
            />
            <Input
                id="password_confirmation"
                label="Confirm your Password"
                :error="form.errors.password_confirmation"
                v-model="form.password_confirmation"
                type="password"
            />
            <Button type="submit" :loading="form.processing" label="Continue" />
            <p class="text-center font-light">
                Already have an account?
                <Link :href="route('login')" class="font-medium text-primary">
                    Login
                </Link>
            </p>
        </form>
    </CardLayout>
</template>
