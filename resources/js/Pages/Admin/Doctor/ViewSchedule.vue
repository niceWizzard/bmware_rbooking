<script setup lang="ts">

import AuthLayout from "@/Layouts/AuthLayout.vue";
import {Doctor, Schedule} from "@/types";
import FullCalendar from "@fullcalendar/vue3";
import {CalendarOptions, EventInput, EventSourceInput} from "@fullcalendar/core";
import timeGridPlugin from "@fullcalendar/timegrid";
import dayjs from "dayjs";


const props = defineProps<{
    doctor: Doctor;
    schedules: Schedule[];
}>()


const formattedSchedules: EventInput[] = props.schedules
    .map(v => {
    const startDate = dayjs.utc(v.start).day(v.day);
    const endDate = dayjs.utc(v.end).day(v.day);
    return ({
        start: startDate.toDate(),
        end: endDate.toDate(),
        title: v.clinic,
        extendedProps: {
            clinic: v.clinic,
            day: v.day,
        }
    });
});

const [minTime, maxTime] = props.schedules.reduce(([min, max], e) => {
    const start = dayjs.utc(e.start);
    const end = dayjs.utc(e.end);

    return [
        start.format('HH:mm:ss') < min.format('HH:mm:ss') ? start : min,
        end.format('HH:mm:ss') > max.format('HH:mm:ss') ? end : max,
    ];
}, [dayjs.utc(props.schedules[0].start), dayjs.utc(props.schedules[0].end)]);



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
    timeZone: "utc",
    height: '24rem',
    headerToolbar: false,
}

</script>

<template>
    <AuthLayout headerTitle="Schedule" >
        <section class="p-8 flex flex-col">
           <h3 class="text-lg font-medium"> Dr. {{doctor.name}}</h3>
            <div class="flex-1 p-4">
                <FullCalendar  :options="calendarOptions" />
            </div>
        </section>
    </AuthLayout>
</template>
