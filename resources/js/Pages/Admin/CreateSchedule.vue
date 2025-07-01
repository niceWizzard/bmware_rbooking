<script setup lang="ts">
import AuthLayout from "@/Layouts/AuthLayout.vue";
import Button from 'primevue/button'
import ConfirmDialog from 'primevue/confirmdialog'
import { Head } from "@inertiajs/vue3";
import FullCalendar from '@fullcalendar/vue3';
import interactionPlugin, {Draggable} from '@fullcalendar/interaction';
import timeGridPlugin from '@fullcalendar/timegrid';
import {CalendarOptions, EventApi} from '@fullcalendar/core'
import {onMounted, ref} from "vue";
import dayjs from "dayjs";
import {useConfirm, useToast} from "primevue";


const calendarRef = ref<any>();
const toast = useToast();
const confirmPopup = useConfirm();

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
    droppable: true,
    editable: true,
    eventClick(info: any){
        confirmPopup.require({
            message: 'Do you want to remove this event?',
            icon: 'pi pi-exclamation-triangle',
            rejectProps: {
                label: 'Cancel',
                severity: 'secondary',
                outlined: true
            },
            acceptProps: {
                label: 'Remove',
                severity: 'danger',
            },
            accept: () => {
                info.event.remove();
            },
        })
    },
    dateClick(info: any) {
        info.view.calendar.addEvent({
            title: "Schedule",
            start: info.date,
            allDay: info.allDay
        })
    },
};

onMounted(() => {
    const containerEl = document.getElementById('external-events');
    if (containerEl) {
        new Draggable(containerEl, {
            itemSelector: '.fc-event',
            eventData: function(eventEl) {
                return {
                    title: eventEl.innerText.trim()
                };
            }
        });
    }
});

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
        start: e.start,
        end: e.end
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

    console.log(events);
}

</script>

<template>

    <Head title="Create a Schedule" />
    <ConfirmDialog />
    <AuthLayout header-title="Create Schedule" >
        <section class="flex flex-col p-8 ">
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
            <div class="flex">
                <div id="external-events" class="w-1/5 p-4 border-r">
                    <p class="mb-2 font-bold">Drag Schedule</p>
                    <div class="fc-event p-2 bg-blue-500 text-white mb-2 rounded cursor-pointer">Schedule</div>
                    <p class="font-light text-sm mt-8">
                        Drag the Blue Schedule to the calendar to add a schedule to that day.
                    </p>
                    <p class="font-light text-sm mt-2">
                        Click on an event in the calendar to remove it.
                    </p>
                </div>

                <!-- FullCalendar -->
                <div class="flex-1 p-4">
                    <FullCalendar ref="calendarRef" :options="calendarOptions" />
                </div>
            </div>
        </section>
    </AuthLayout>
</template>

