<script setup lang="ts">
import AuthLayout from '@/Layouts/AuthLayout.vue';
import { Doctor, Schedule } from '@/types';
import { CalendarOptions, EventInput } from '@fullcalendar/core';
import timeGridPlugin from '@fullcalendar/timegrid';
import FullCalendar from '@fullcalendar/vue3';
import { Head, Link } from '@inertiajs/vue3';
import dayjs from 'dayjs';

const props = defineProps<{
    doctor: Doctor;
    schedules: Schedule[];
}>();

const formattedSchedules: EventInput[] = props.schedules.map((v) => {
    const startDate = dayjs.utc(v.start_at).day(v.day);
    const endDate = dayjs.utc(v.end_at).day(v.day);
    return {
        start: startDate.toDate(),
        end: endDate.toDate(),
        title: v.clinic,
        extendedProps: {
            clinic: v.clinic,
            day: v.day,
        },
    };
});

const [minTime, maxTime] = props.schedules.reduce(
    ([min, max], e) => {
        const start = dayjs.utc(e.start_at);
        const end = dayjs.utc(e.end_at);

        return [
            start.format('HH:mm:ss') < min.format('HH:mm:ss') ? start : min,
            end.format('HH:mm:ss') > max.format('HH:mm:ss') ? end : max,
        ];
    },
    [
        dayjs.utc(props.schedules[0].start_at),
        dayjs.utc(props.schedules[0].end_at),
    ],
);

const calendarOptions: CalendarOptions = {
    plugins: [timeGridPlugin],
    initialView: 'timeGridWeek',
    allDaySlot: false,
    nowIndicator: false,
    stickyFooterScrollbar: true,
    slotDuration: '00:60:00',
    expandRows: true,
    dayHeaderFormat: { weekday: 'long' },
    slotMinTime: minTime.format('HH:mm:ss'),
    slotMaxTime: maxTime.format('HH:mm:ss'),
    events: formattedSchedules,
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
                        class="rounded-md bg-primary px-3 py-2 text-white"
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
