<script setup lang="ts">
    import {Doctor} from "@/types";

    defineProps<{
        doctor: Doctor;
    }>();
</script>

<template>
    Booking with {{doctor.name}}
</template>

<style scoped>

</style>
