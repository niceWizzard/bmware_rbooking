<script setup lang="ts">
import AuthLayout from '@/Layouts/AuthLayout.vue';
import DashboardDialog from '@/Pages/Admin/Partials/DashboardDialog.vue';
import { Doctor } from '@/types';
import { CalendarOptions, EventApi, EventInput } from '@fullcalendar/core';
import timeGridPlugin from '@fullcalendar/timegrid';
import FullCalendar from '@fullcalendar/vue3';
import { Head, Link } from '@inertiajs/vue3';
import { useQuery } from '@tanstack/vue-query';
import axios from 'axios';
import { useToast } from 'primevue';
import { reactive, ref } from 'vue';

const props = defineProps<{
    doctor: Doctor;
    slots: EventInput[];
    timeRange: [string, string];
    clinicCounts: { clinic: string; count: number }[];
}>();

const toast = useToast();
const dialogRef = ref<InstanceType<typeof DashboardDialog>>();

const { data: fetchedSlots, refetch: refetchSlots } = useQuery({
    queryKey: ['doctorAppointments'],
    refetchInterval: 2000,
    initialData: props.slots,
    queryFn: async () => {
        try {
            const res = await axios.get(
                route('admin.dashboard.fetch', { code: props.doctor!.code }),
            );
            if (!res.data.success) throw new Error(res.data.message);
            return res.data.slots;
        } catch (error: unknown) {
            const err = error as Error;
            toast.add({
                severity: 'error',
                summary: 'An error occurred while fetching bookings',
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
    expandRows: true,
    slotDuration: '00:60:00',
    dayHeaderFormat: { weekday: 'long' },
    height: '100vh',
    stickyHeaderDates: true,
    firstDay: new Date().getDay(),
    timeZone: 'utc',
    slotMinTime: props.timeRange[0],
    slotMaxTime: props.timeRange[1],
    events: fetchedSlots as any,
    eventClick(info) {
        dialogRef.value?.setSlot(info.event);
    },
});

async function onDelete(slot: EventApi, setIsLoading: (v: boolean) => void) {
    try {
        const res = await axios.delete(
            route('patient.appointment.delete', slot.id),
        );
        if (res.data.succes) {
            throw new Error(res.data.message);
        }
        toast.add({
            severity: 'success',
            summary: 'Cancelled successfully',
            life: 3000,
        });
        dialogRef.value?.setSlot(null);
    } catch (e) {
        const err = e as Error;
        toast.add({
            severity: 'error',
            summary: 'Something went wrong while cancelling the appointment',
            detail: err.message,
            life: 3000,
        });
    } finally {
        setIsLoading(false);
        await refetchSlots();
    }
}
</script>

<template>
    <Head title="Admin Dashboard" />
    <AuthLayout header-title="Admin Dashboard">
        <DashboardDialog
            ref="dialogRef"
            :on-delete="onDelete"
            :doctor="doctor"
        />
        <section class="flex flex-col gap-4 p-8">
            <div class="flex justify-between">
                <h3 class="text-2xl font-medium">Dr. {{ doctor!.name }}</h3>
                <div class="flex gap-4">
                    <Link
                        :href="route('doctor.list')"
                        class="flex items-center gap-3 rounded-md bg-primary px-3 py-2 text-white"
                    >
                        <i class="pi pi-arrow-right-arrow-left"></i>
                        Change Doctors</Link
                    >
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-4">
                Clinics:
                <p
                    class="rounded-md bg-gray-400 px-2 py-1 text-white"
                    v-for="c of clinicCounts"
                    v-bind:key="c.clinic"
                >
                    {{ c.clinic }} ({{ c.count }})
                </p>
            </div>
            <div class="flex-1">
                <FullCalendar ref="calendarRef" :options="calendarOptions" />
            </div>
        </section>
    </AuthLayout>
</template>
