<script setup lang="ts">
import AppointmentChangeDialog from '@/Components/Appointment/AppointmentChangeDialog.vue';
import AuthLayout from '@/Layouts/AuthLayout.vue';
import { Doctor } from '@/types';
import { CalendarOptions, EventApi } from '@fullcalendar/core';
import timeGridPlugin from '@fullcalendar/timegrid';
import FullCalendar from '@fullcalendar/vue3';
import { Head, Link, router } from '@inertiajs/vue3';
import { useQuery } from '@tanstack/vue-query';
import axios from 'axios';
import { useToast } from 'primevue';
import { reactive, ref } from 'vue';

const props = defineProps<{
    doctor: Doctor;
    slots: any;
    hiddenDays: number[];
    timeRange: [string, string];
    dateRange: [string, string];
    appointmentId: number;
}>();

const dialogRef = ref<InstanceType<typeof AppointmentChangeDialog>>();
const calendarRef = ref<InstanceType<typeof FullCalendar>>();
const toast = useToast();

const { data: fetchedSlots, refetch: refetchSlots } = useQuery<any>({
    queryKey: ['changeEvents'],
    refetchInterval: 2000,
    initialData: props.slots,
    queryFn: async () => {
        try {
            const res = await axios.get(
                route('patient.appointment.change.fetch', props.appointmentId),
            );
            if (!res.data.success) {
                toast.add({
                    severity: 'error',
                    summary: 'Failed refetching slots',
                    detail: res.data.message,
                });
            }
            return res.data.slots;
        } catch (e) {
            const err = e as Error;
            console.error(err);
            toast.add({
                severity: 'error',
                summary: 'Failed refetching slots',
                detail: err.message,
            });
            return [];
        }
    },
});

const calendarOptions: CalendarOptions = reactive({
    plugins: [timeGridPlugin],
    initialView: 'timeGridWeek',
    allDaySlot: false,
    nowIndicator: false,
    stickyFooterScrollbar: true,
    slotDuration: '00:60:00',
    dayHeaderFormat: { weekday: 'long' },
    height: '100vh',
    stickyHeaderDates: true,
    firstDay: new Date().getDay(),
    timeZone: 'utc',
    hiddenDays: props.hiddenDays,
    slotMinTime: props.timeRange[0],
    slotMaxTime: props.timeRange[1],
    validRange: {
        start: props.dateRange[0],
        end: props.dateRange[1],
    },
    events: fetchedSlots as any,
    eventClick(info) {
        if (info.event.extendedProps.type === 'free')
            dialogRef.value?.setSlot(info.event);
    },
});

async function onSubmit(event: EventApi, setIsLoading: (v: boolean) => void) {
    try {
        const res = await axios.post(
            route('patient.appointment.change', props.appointmentId),
            {
                date: event.start,
            },
        );
        if (!res.data.success) {
            throw new Error(res.data.message);
        }
        toast.add({
            severity: 'success',
            summary: 'Appointment updated!',
            life: 3000,
        });
        router.visit(route('patient.book'));
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
        await refetchSlots();
    }
}
</script>

<template>
    <Head title="Change Appointment Time" />
    <AppointmentChangeDialog
        ref="dialogRef"
        :on-submit="onSubmit"
        :doctor="doctor"
    />
    <AuthLayout header-title="Change Time">
        <section class="flex flex-col gap-4 p-8">
            <div class="flex justify-between">
                <div class="flex gap-4">
                    <Link
                        :href="route('patient.book')"
                        class="flex items-center gap-3 rounded-md bg-primary px-3 py-2 text-white"
                    >
                        Cancel</Link
                    >
                </div>
                <h3 class="text-2xl font-medium">
                    Update your Appointment Time
                </h3>
            </div>
            <p class="text-md font-light text-gray-600">
                Please select a new time for your appointment.
            </p>
            <div class="flex-1">
                <FullCalendar ref="calendarRef" :options="calendarOptions" />
            </div>
        </section>
    </AuthLayout>
</template>
