<script setup lang="ts">
  import Button from 'primevue/button'
  import {router, usePage} from '@inertiajs/vue3'

  const {props} = usePage();
  const {user} = props.auth;

</script>

<template>
    Dashboard
  <div v-if="user">
      <p >
        {{user}}

      </p>
      <Button @click="router.post(route('logout'))">
        Logout
      </Button>
  </div>
  <p v-else>
    NO USER
  </p>

</template>


