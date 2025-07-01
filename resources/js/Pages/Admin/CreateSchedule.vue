<script setup lang="ts">
import AuthLayout from "@/Layouts/AuthLayout.vue";
import { Head } from "@inertiajs/vue3";
import FullCalendar from '@fullcalendar/vue3';
import interactionPlugin from '@fullcalendar/interaction';
import timeGridPlugin from '@fullcalendar/timegrid';
import type { CalendarOptions } from '@fullcalendar/core'

const calendarOptions : CalendarOptions = {
    plugins: [timeGridPlugin, interactionPlugin],
    initialView: 'timeGridWeek',
    allDaySlot: false,
    expandRows: true,
};

</script>

<template>
    <Head title="Create Schedule" />
    <AuthLayout headerTitle="Create Schedule">
        <section class="p-8">
            <FullCalendar :options="calendarOptions" />
        </section>
    </AuthLayout>
</template>
