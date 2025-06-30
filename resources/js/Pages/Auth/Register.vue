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
      first_name: '',
      last_name: '',
      mobile: '',
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
          <div class="flex gap-2">
              <div class="flex flex-col gap-1">
                  <FloatLabel variant="on" class="w-full" >
                      <InputText class="w-full" id="first_name" v-model="form.first_name"  />
                      <label for="first_name">First Name</label>
                  </FloatLabel>
                  <p class="text-sm font-light text-red-500" v-if="form.errors.first_name">{{form.errors.first_name}}</p>
              </div>
              <div class="flex flex-col gap-1">
                  <FloatLabel variant="on" class="w-full" >
                      <InputText class="w-full" id="last_name" v-model="form.last_name"  />
                      <label for="last_name">Last Name</label>
                  </FloatLabel>
                  <p class="text-sm font-light text-red-500" v-if="form.errors.last_name">{{form.errors.last_name}}</p>
              </div>
          </div>
          <div class="flex flex-col gap-1">
              <FloatLabel variant="on" class="w-full" >
                  <InputText class="w-full" id="email" v-model="form.email" type="email"  />
                  <label for="email">Email</label>
              </FloatLabel>
              <p class="text-sm font-light text-red-500" v-if="form.errors.email">{{form.errors.email}}</p>
          </div>
          <div class="flex flex-col gap-1">
              <FloatLabel variant="on" class="w-full" >
                  <InputText class="w-full" id="mobile" v-model="form.mobile" type="text"  />
                  <label for="mobile">Mobile Number</label>
              </FloatLabel>
              <p class="text-sm font-light text-red-500" v-if="form.errors.mobile">{{form.errors.mobile}}</p>
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
