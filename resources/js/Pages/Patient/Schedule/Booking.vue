<script setup lang="ts">
    import {Doctor} from "@/types";
    import AuthLayout from "@/Layouts/AuthLayout.vue";
    import FullCalendar from "@fullcalendar/vue3";
    import {CalendarOptions, EventApi} from "@fullcalendar/core";
    import timeGridPlugin from "@fullcalendar/timegrid";
    import {Link, Head} from "@inertiajs/vue3";
    import {ref} from "vue";
    import BookSlotDialog from "@/Components/Schedule/BookSlotDialog.vue";
    import axios from "axios";
    import {useToast} from "primevue";
    import Toast from 'primevue/toast';
    import dayjs from "dayjs";

    const toast = useToast();
    const props = withDefaults(defineProps<{
        invalid: boolean,
        doctor?: Doctor,
        slots: any
    }>(), {
        invalid: false
    });

    const bookSlotDialogRef = ref<InstanceType<typeof BookSlotDialog>>();

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
        timeZone: 'utc',
        eventClick (info) {
            bookSlotDialogRef.value?.setSlot(info.event);
        },
    }

    function onSubmit(slot : EventApi, setIsLoading: (value: boolean) => void) {
        axios.post(route('patient.book'), {
            date: dayjs.utc(slot.start).utc().format('YYYY-MM-DD HH:mm:ss'),
            code: props.doctor!.code,
        }).then(response => {
            if(!response.data.success) {
                toast.add({
                    severity: 'error',
                    summary: response.data.message,
                    life: 3000,
                });
                return;
            }
            toast.add({
                severity: 'success',
                summary: 'Booked successfully',
                life: 3000,
            })
            bookSlotDialogRef.value?.setSlot(null);
        }).finally(() => {
            setIsLoading(false);
        });
    }


</script>

<template>
    <Head title="Book an Appointment" />
    <AuthLayout header-title="Booking" >
        <BookSlotDialog :on-submit="onSubmit" ref="bookSlotDialogRef"/>
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
