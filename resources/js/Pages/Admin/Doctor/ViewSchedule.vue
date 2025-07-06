<script setup lang="ts">
import AuthLayout from '@/Layouts/AuthLayout.vue';
import { Doctor } from '@/types';
import { CalendarOptions, EventInput } from '@fullcalendar/core';
import timeGridPlugin from '@fullcalendar/timegrid';
import FullCalendar from '@fullcalendar/vue3';
import { Head, Link } from '@inertiajs/vue3';

const {
    slots,
    range,
} = defineProps<{
    doctor: Doctor;
    slots: EventInput[];
    range: [string, string];
}>();

const calendarOptions: CalendarOptions = {
    plugins: [timeGridPlugin],
    initialView: 'timeGridWeek',
    allDaySlot: false,
    nowIndicator: false,
    stickyFooterScrollbar: true,
    slotDuration: '00:60:00',
    expandRows: true,
    dayHeaderFormat: { weekday: 'long' },
    slotMinTime: range[0],
    slotMaxTime: range[1],
    events: slots,
    timeZone: 'utc',
    height: '24rem',
    headerToolbar: false,
    stickyHeaderDates: true,
};
</script>

<template>
    <Head :title="`Schedule of ${doctor.name}`" />
    <AuthLayout headerTitle="Schedule">
        <section class="flex flex-col p-8">
            <div class="flex items-center justify-between gap-2">
                <h3 class="text-lg font-medium">Dr. {{ doctor.name }}</h3>
                <div class="flex gap-4">
                    <Link
                        :href="route('doctor.list')"
                        class="rounded-md bg-gray-600 px-3 py-2 text-white"
                        >Back</Link
                    >
                    <Link
                        :href="route('schedule.edit', doctor.id)"
                        class="bg-primary rounded-md px-3 py-2 text-white"
                        >Edit</Link
                    >
                </div>
            </div>

            <div class="flex-1 p-4">
                <FullCalendar :options="calendarOptions" />
            </div>
        </section>
    </AuthLayout>
</template>
