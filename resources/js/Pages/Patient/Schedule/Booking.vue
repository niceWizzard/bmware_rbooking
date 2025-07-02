<script setup lang="ts">
    import {Doctor} from "@/types";
    import AuthLayout from "@/Layouts/AuthLayout.vue";
    import FullCalendar from "@fullcalendar/vue3";
    import {CalendarOptions} from "@fullcalendar/core";
    import timeGridPlugin from "@fullcalendar/timegrid";
    import {computed} from "vue";
    import {Link} from "@inertiajs/vue3";

    type BookingProps =
        | { invalid: true }
        | { invalid: false, doctor: Doctor }

    const props = withDefaults(defineProps<{
        invalid: boolean,
        doctor?: Doctor
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
        timeZone: "utc",
        height: '100vh',
        headerToolbar: false,
    }

</script>

<template>
    <AuthLayout header-title="Booking" >
        <section class="p-8 flex flex-col" v-if="!invalid">
            <h3 class="text-lg font-medium"> Dr. {{doctor!.name}}</h3>
            <div class="flex-1 p-4">
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
