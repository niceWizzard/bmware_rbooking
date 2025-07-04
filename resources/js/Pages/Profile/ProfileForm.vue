<script setup>
import {useForm, usePage} from "@inertiajs/vue3";
import Input from '@/Components/Input.vue';
import Button from 'primevue/button';

const {props: {
    auth: {user}
}} = usePage();

const form = useForm({
    email: user?.email
});

function submit() {
    form.post(route('profile.update'))
}

</script>


<template>
  <form
      class="flex container mx-auto flex-col gap-4 p-4 rounded-md shadow-sm border border-gray-200 w-full h-lg"
      @submit.prevent="submit"
  >
  <h3 class="text-lg mb-4">Profile Information</h3>
    <Input
        id="email"
        label="Email"
        v-model="form.email"
        class="max-w-xl"
        :error="form.errors.email"
    />
      <Button type="submit"
        label="Save"
      class="w-fit self-start"
      />
      <Transition
          enter-active-class="transition ease-in-out"
          enter-from-class="opacity-0"
          leave-active-class="transition ease-in-out"
          leave-to-class="opacity-0"
      >
          <p
              v-if="form.recentlySuccessful"
              class="text-sm text-gray-600"
          >
              Saved.
          </p>
      </Transition>
  </form>
</template>
