<script setup lang="ts">

import AuthLayout from "@/Layouts/AuthLayout.vue";
import {CalendarOptions, EventApi} from "@fullcalendar/core";
import timeGridPlugin from "@fullcalendar/timegrid";
import {Doctor} from "@/types";
import {Link, router} from "@inertiajs/vue3";
import FullCalendar from "@fullcalendar/vue3";
import AppointmentChangeDialog from "@/Components/Appointment/AppointmentChangeDialog.vue";
import {ref} from "vue";
import axios from "axios";
import {useToast} from "primevue";

const props = defineProps<{
    doctor: Doctor,
    slots: any,
    hiddenDays: number[],
    timeRange: [string, string],
    dateRange: [string, string],
    appointmentId: number,
}>();

const dialogRef = ref<InstanceType<typeof AppointmentChangeDialog>>();
const toast = useToast();
const calendarOptions: CalendarOptions = {
    plugins: [timeGridPlugin],
    initialView: 'timeGridWeek',
    allDaySlot: false,
    nowIndicator: false,
    stickyFooterScrollbar: true,
    slotDuration: '00:60:00',
    dayHeaderFormat: { weekday: 'long' },
    height: '100vh',
    stickyHeaderDates: true,
    firstDay: (new Date()).getDay(),
    timeZone: 'utc',
    hiddenDays:  props.hiddenDays,
    slotMinTime: props.timeRange[0],
    slotMaxTime: props.timeRange[1],
    validRange: {
        start: props.dateRange[0],
        end: props.dateRange[1],
    },
    events: props.slots,
    eventClick(info) {
        if(info.event.extendedProps.type === 'free')
            dialogRef.value?.setSlot(info.event);
    }
}

async function onSubmit(event: EventApi,  setIsLoading: (v : boolean) => void) {
    try {
        const res = await axios.post(route('patient.appointment.change', props.appointmentId), {
            date: event.start
        });
        if(!res.data.success) {
            throw new Error(res.data.message)
        }
        toast.add({
            severity: 'success',
            summary: "Appointment updated!",
            life: 3000,
        });
        router.visit(route('patient.book'))
    } catch (e) {
        const err = e as Error;
        toast.add({
            severity: 'error',
            detail: err.message,
            summary: 'Something went wrong.',
            life: 3000,
        });
    } finally {
        setIsLoading(false);
        dialogRef.value?.setSlot(null);
    }
}

</script>

<template>
    <AppointmentChangeDialog ref="dialogRef" :on-submit="onSubmit" :doctor="doctor" />
    <AuthLayout header-title="Change Time">
        <section class="p-8 flex flex-col gap-4 " >
            <p class="text-sm font-light text-gray-400">
                Please select a new time for your appointment.
            </p>
            <div class="flex justify-between">
                <div class="flex gap-4">
                    <Link :href="route('patient.book')"
                          class="px-3 py-2 rounded-md bg-green-600 text-white flex items-center gap-3" >
                        Cancel</Link>
                </div>
                <h3 class="text-2xl font-medium"> Dr. {{doctor!.name}}</h3>
            </div>
            <div class="flex-1 ">
                <FullCalendar :options="calendarOptions" />
            </div>
        </section>
    </AuthLayout>
</template>
