<script setup lang="ts">
    import {Doctor} from "@/types";
    import AuthLayout from "@/Layouts/AuthLayout.vue";
    import FullCalendar from "@fullcalendar/vue3";
    import {CalendarOptions} from "@fullcalendar/core";
    import timeGridPlugin from "@fullcalendar/timegrid";
    import {Link} from "@inertiajs/vue3";

    type BookingProps =
        | { invalid: true }
        | { invalid: false, doctor: Doctor }

    const props = withDefaults(defineProps<{
        invalid: boolean,
        doctor?: Doctor,
        slots: any
    }>(), {
        invalid: false
    });


    const calendarOptions: CalendarOptions = {
        plugins: [timeGridPlugin],
        initialView: 'timeGridWeek',
        allDaySlot: false,
        nowIndicator: false,
        stickyFooterScrollbar: true,
        slotDuration: '00:60:00',
        expandRows: true,
        dayHeaderFormat: { weekday: 'long' },
        height: '100vh',
        events: props.slots,
        stickyHeaderDates: true,
        firstDay: (new Date()).getDay(),
    }

</script>

<template>
    <AuthLayout header-title="Booking" >
        <section class="p-8 flex flex-col gap-4 " v-if="!invalid">
            <div class="flex justify-between">
                <h3 class="text-2xl font-medium"> Dr. {{doctor!.name}}</h3>
                <div class="flex gap-4">
                    <Link :href="route('patient.doctors')"
                          class="px-3 py-2 rounded-md bg-green-600 text-white flex items-center gap-3" >
                        <i class="pi pi-arrow-right-arrow-left"></i>
                        Change Doctors</Link>
                </div>
            </div>
            <div class="flex-1 ">
                <FullCalendar  :options="calendarOptions" />
            </div>
        </section>
        <section class="w-full flex flex-col gap-4 p-8 items-center" v-else>
            <p class="text-center text-2xl">
                Invalid Doctor
            </p>
            <Link :href="route('patient.doctors')"
                class="px-4 py-2 text-white bg-green-600 rounded-md"
            >See Doctor's List</Link>
        </section>
    </AuthLayout>
</template>

<style scoped>

</style>
