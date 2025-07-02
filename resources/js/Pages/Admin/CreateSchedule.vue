<script setup lang="ts">
import AuthLayout from "@/Layouts/AuthLayout.vue";
import Button from 'primevue/button'
import ConfirmDialog from 'primevue/confirmdialog'
import {Head, router, usePage} from "@inertiajs/vue3";
import FullCalendar from '@fullcalendar/vue3';
import interactionPlugin, {DateClickArg} from '@fullcalendar/interaction';
import timeGridPlugin from '@fullcalendar/timegrid';
import {CalendarOptions, EventApi, EventClickArg} from '@fullcalendar/core'
import dayjs from "dayjs";
import {useConfirm, useToast} from "primevue";
import {Doctor} from "@/types";
import ScheduleEditDialog from "@/Components/Schedule/ScheduleEditDialog.vue";
import {ref, watchEffect} from "vue";


const calendarRef = ref<any>();
const toast = useToast();
const confirmPopup = useConfirm();

const props = defineProps<{
    doctor: Doctor,
}>();


watchEffect(() => {
    const {props: pageProps} = usePage();
    if (pageProps.flash?.message) {
        toast.add({
            severity: "error",
            summary: "Error",
            detail: pageProps.flash.message,
        });
    }
});

const selectedEvent = ref<EventApi|null>(null);

const calendarOptions : CalendarOptions = {
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
    eventClick(info: EventClickArg){
        selectedEvent.value = info.event;
    },
    dateClick(info: DateClickArg) {
        selectedEvent.value = info.view.calendar.addEvent({
            title: "Schedule",
            start: info.date,
            allDay: info.allDay
        });
    },
};


function resetButtonClick(event: MouseEvent) {
    confirmPopup.require({
        message: 'Are you sure you want to reset everything?',
        icon: 'pi pi-exclamation-triangle',
        rejectProps: {
            label: 'Cancel',
            severity: 'secondary',
            outlined: true
        },
        acceptProps: {
            label: 'Reset',
            severity: 'danger',
        },
        accept: () => {
            const calendarApi = calendarRef.value.getApi()
            calendarApi?.removeAllEvents();
        },
    })

}

function finishButtonClicked() {
    const calendarApi = calendarRef.value.getApi()
    const eventsFromCalendar: EventApi[] = calendarApi.getEvents()
    const events = eventsFromCalendar.map(e => ({
        id: e.id,
        title: e.title,
        start: dayjs(e.start).format('YYYY-MM-DDTHH:mm:ss'), // no Z suffix
        end: dayjs(e.end).format('YYYY-MM-DDTHH:mm:ss'),
        clinic: e.extendedProps.clinic,
        day: dayjs(e.start).day()
    }))
    if(events.length === 0){
        toast.add({
            detail:"Please add a schedule.",
            life: 2000,
            closable: false,
        })
        return;
    }
    const eventCountsByDate = events.reduce((acc, event) => {
        const dateKey = dayjs(event.start).day();
        acc[dateKey] = (acc[dateKey] || 0) + 1
        return acc
    }, {} as Record<string, number>)
    const [maxDate, maxCount] = Object.entries(eventCountsByDate)
        .reduce(([maxD, maxC], [date, count]) =>
                count > maxC ? [date, count] : [maxD, maxC],
            ['', 0])

    if(maxCount > 1) {
        toast.add({
            detail: `There are ${maxCount} events in ${dayjs(maxDate).format('dddd')}`,
            life: 2000,
            severity: "error"
        });
        return;
    }

    let safe = true;
    for (const event of events) {
        if(!event.clinic) {
            safe = false;
            toast.add({
                detail: `Please add a clinic in ${dayjs(event.start).format('dddd')}`,
                life: 2000,
                severity: "error"
            });
            break;
        }
    }

    if(!safe)
        return;


    router.post(route('schedule.create', props.doctor.id), {events});
}



</script>

<template>
    <Head title="Create a Schedule" />
    <ConfirmDialog />
    <ScheduleEditDialog v-model="selectedEvent" />
    <AuthLayout header-title="Create Schedule" >
        <section class="flex flex-col p-8 ">
            <h3 class="text-lg">Dr. {{doctor.name}}</h3>
            <div class="flex justify-end w-full gap-3">
                <Button
                    label="Reset"
                    severity="danger"
                    @click="resetButtonClick"
                />
                <Button
                    label="Finish"
                    @click="finishButtonClicked"
                />
            </div>
            <div class="flex-1 p-4">
                <FullCalendar ref="calendarRef" :options="calendarOptions" />
            </div>
        </section>
    </AuthLayout>
</template>

<style scoped>

</style>
