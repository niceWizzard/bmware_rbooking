<script setup lang="ts">
    import CardLayout from '@/Layouts/CardLayout.vue'
    import { Head } from '@inertiajs/vue3'
    import Button from "primevue/button";
    import InputText from "primevue/inputtext";

  defineProps<{}>()
</script>

<template>
  <CardLayout>
      <Head title="Register" />
      <template #header>
          <h3 class="text-2xl font-bold text-center">
              Register
          </h3>
      </template>
      <div class="w-96 flex flex-col gap-4 ">
          <InputText placeholder="Email" />
          <InputText placeholder="Password" type="password" />
          <InputText placeholder="Confirm Password" type="password" />
          <Button >Register</Button>
          <p class=" font-light text-center">
              Already have an account? <a :href="route('login')" class="font-medium text-primary">Register</a>
          </p>
      </div>
  </CardLayout>
</template>
