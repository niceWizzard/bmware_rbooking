<script setup lang="ts">
import ScheduleEditDialog from '@/Components/Schedule/ScheduleEditDialog.vue';
import { CalendarOptions, EventApi, EventInput } from '@fullcalendar/core';
import interactionPlugin from '@fullcalendar/interaction';
import timeGridPlugin from '@fullcalendar/timegrid';
import FullCalendar from '@fullcalendar/vue3';
import { ToastServiceMethods } from 'primevue';
import { reactive, ref } from 'vue';

const { toast, initialEvents } = defineProps<{
    toast: ToastServiceMethods;
    initialEvents?: EventInput[];
}>();

const calendarRef = ref<any>();

const selectedEvent = ref<EventApi | null>(null);

defineExpose({
    resetCalendar: (asInitial = false): void => {
        if (asInitial) {
            calendarOptions.events =
                JSON.parse(JSON.stringify(initialEvents)) ?? [];
        } else {
            calendarOptions.events = [];
        }
    },
    getCalendarEvents: (): EventApi[] => {
        return calendarRef.value.getApi().getEvents();
    },
});

const calendarOptions: CalendarOptions = reactive({
    plugins: [timeGridPlugin, interactionPlugin],
    initialView: 'timeGridWeek',
    allDaySlot: false,
    nowIndicator: false,
    stickyFooterScrollbar: true,
    dragScroll: true,
    slotDuration: '00:60:00',
    height: '100vh',
    dayHeaderFormat: { weekday: 'long' },
    headerToolbar: false,
    editable: true,
    droppable: true,
    expandRows: true,
    eventOverlap: false,
    stickyHeaderDates: true,
    initialEvents: initialEvents,
    eventClick(info) {
        selectedEvent.value = info.event;
    },
    eventResize: function (info) {
        if (info.event.start?.getDate() !== info.event.end?.getDate()) {
            toast.add({
                severity: 'warn',
                detail: 'Invalid end time',
                life: 2000,
            });
            info.revert();
        }
    },
    eventDrop(info) {
        if (info.event.start?.getDate() !== info.event.end?.getDate()) {
            toast.add({
                severity: 'warn',
                detail: 'Invalid end time',
                life: 2000,
            });
            info.revert();
            return;
        }
        const events = info.view.calendar.getEvents();
        const hasConflict = events.some((event) => {
            return (
                event.id !== info.event.id && // skip the moved event
                event.start &&
                event.start.getDate() === info.event.start?.getDate()
            );
        });

        if (hasConflict) {
            toast.add({
                severity: 'warn',
                detail: 'Cannot have more than two schedule per day',
                life: 2000,
            });
            info.revert();
        }
    },
    dateClick(info) {
        const events = info.view.calendar.getEvents();
        if (events.some((e) => e.start?.getDate() === info.date.getDate())) {
            toast.add({
                severity: 'warn',
                detail: 'Cannot be more than two schedule at one day.',
                life: 2000,
            });
            return;
        }
        selectedEvent.value = info.view.calendar.addEvent({
            title: 'Schedule',
            start: info.date,
            allDay: info.allDay,
            id: new Date().toString(),
        });
    },
});
</script>

<template>
    <ScheduleEditDialog v-model="selectedEvent" />
    <FullCalendar ref="calendarRef" :options="calendarOptions" />
</template>
