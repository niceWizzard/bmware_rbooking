<script setup lang="ts">
import Button from 'primevue/button'
import InputText from 'primevue/inputtext'
import FloatLabel from 'primevue/floatlabel'
import CardLayout from '@/Layouts/CardLayout.vue'
import {Head, useForm} from "@inertiajs/vue3";

const form = useForm({
    email: '',
    password: '',
});


</script>

<template>
    <CardLayout class="w-full">
        <Head title="Login" />
        <template #header>
            <h3 class="text-2xl font-bold text-center">
                Login
            </h3>
        </template>
        <form @submit.prevent="form.post(route('login'))"
              class="w-96 flex flex-col gap-4 ">
            <div class="flex flex-col gap-1">
                <FloatLabel variant="on" class="w-full" >
                    <InputText class="w-full" id="email" v-model="form.email" type="email"  />
                    <label for="email">Email</label>
                </FloatLabel>
                <p class="text-sm font-light text-red-500" v-if="form.errors.email">{{form.errors.email}}</p>
            </div>
            <div class="flex flex-col gap-1">
                <FloatLabel variant="on" class="w-full" >
                    <InputText class="w-full" id="password" v-model="form.password"  type="password" />
                    <label for="password">Password</label>
                </FloatLabel>
                <p class="text-sm font-light text-red-500" v-if="form.errors.password">{{form.errors.password}}</p>
            </div>
            <Button type="submit" :disabled="form.processing">Login</Button>
            <p class=" font-light text-center">
                Don't have an account? <a :href="route('register')" class="font-medium text-primary">Register</a>
            </p>
        </form>
    </CardLayout>
</template>
