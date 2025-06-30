<script setup lang="ts">
    import CardLayout from '@/Layouts/CardLayout.vue'
    import {Head, useForm} from '@inertiajs/vue3'
    import Button from "primevue/button";
    import InputText from "primevue/inputtext";
    import FloatLabel from "primevue/floatlabel";

  defineProps<{}>();
  const form = useForm({
      email: '',
      password: '',
      password_confirmation: '',
      name: '',
  });

</script>

<template>
  <CardLayout>
      <Head title="Register" />
      <template #header>
          <h3 class="text-2xl font-bold text-center">
              Register
          </h3>
      </template>
      <form @submit.prevent="form.post(route('register'))"
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
                  <InputText class="w-full" id="name" v-model="form.name"  />
                  <label for="name">Name</label>
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
          <div class="flex flex-col gap-1">
              <FloatLabel variant="on" class="w-full" >
                  <InputText class="w-full" id="password_confirmation" v-model="form.password_confirmation"  type="password" />
                  <label for="password_confirmation">Password Confirmation</label>
              </FloatLabel>
              <p class="text-sm font-light text-red-500" v-if="form.errors.password">{{form.errors.password}}</p>
          </div>
          <Button type="submit" :disabled="form.processing">Continue</Button>
          <p class=" font-light text-center">
              Already have an account? <a :href="route('login')" class="font-medium text-primary">
              Login
              </a>
          </p>
      </form>
  </CardLayout>
</template>
