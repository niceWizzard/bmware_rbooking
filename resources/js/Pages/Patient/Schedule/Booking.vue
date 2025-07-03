<script setup lang="ts">
    import {Doctor} from "@/types";
    import AuthLayout from "@/Layouts/AuthLayout.vue";
    import FullCalendar from "@fullcalendar/vue3";
    import {CalendarOptions, EventApi} from "@fullcalendar/core";
    import timeGridPlugin from "@fullcalendar/timegrid";
    import {Link, Head} from "@inertiajs/vue3";
    import {onMounted, ref} from "vue";
    import BookSlotDialog from "@/Components/Schedule/BookSlotDialog.vue";
    import axios from "axios";
    import {useToast} from "primevue";
    import Toast from 'primevue/toast';
    import dayjs from "dayjs";
    import {useIntervalFn} from "@vueuse/core";

    const toast = useToast();
    const props = defineProps<{
        invalid: boolean,
        doctor?: Doctor,
        slots: any,
        hiddenDays: number[],
        timeRange: [string, string],
        dateRange: [string, string],
    }>();

    const bookSlotDialogRef = ref<InstanceType<typeof BookSlotDialog>>();
    const calendarRef = ref<InstanceType<typeof FullCalendar>>();

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
        eventClick (info) {
            bookSlotDialogRef.value?.setSlot(info.event);
        },

        eventSources: [
            {
              id: 'initial',
              events: props.slots,
            }
        ]
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

    const {pause: pausePoll, resume: resumePoll } = useIntervalFn(() => {
        const calendar = calendarRef.value?.getApi();
        calendar?.refetchEvents();
    }, 2000)

    onMounted(() => {
        const calendar = calendarRef.value?.getApi();
        if (!calendar) return;

        // Remove the initial event source
        const removeInitialSource = () => {
            const initialSource = calendar.getEventSourceById('initial');
            if (initialSource) {
                initialSource.remove();
            }
        }

        setTimeout(() => {
            let haveRemoved = false;
            calendar.addEventSource({
                id: 'dynamic',
                async events(info: any, successCb: Function, failureCb: Function) {
                    try {
                        const res = await axios.get(route('patient.book.fetch', { code: props.doctor!.code }));
                        if (!res.data.success) throw new Error(res.data.message);
                        if(!haveRemoved) {
                            haveRemoved = true;
                            removeInitialSource();
                        }
                        successCb(res.data.slots);
                    } catch (error: unknown) {
                        const err = error as Error;
                        toast.add({
                            severity: 'error',
                            summary: 'An error occurred while fetching bookings',
                            detail: err.message,
                        });
                        pausePoll();
                        failureCb(err);
                    }
                }
            });
        }, 2000); // Reduced delay for better responsiveness
    });


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
                <FullCalendar  ref="calendarRef" :options="calendarOptions" />
            </div>
        </section>
    </AuthLayout>
</template>
