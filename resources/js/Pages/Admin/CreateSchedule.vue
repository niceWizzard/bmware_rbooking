<script setup lang="ts">
import AuthLayout from "@/Layouts/AuthLayout.vue";
import Button from 'primevue/button'
import { Head } from "@inertiajs/vue3";
import FullCalendar from '@fullcalendar/vue3';
import interactionPlugin, {Draggable} from '@fullcalendar/interaction';
import timeGridPlugin from '@fullcalendar/timegrid';
import {CalendarOptions, EventApi} from '@fullcalendar/core'
import {onMounted, ref} from "vue";


const calendarRef = ref<any>();

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
        if(confirm("Do you want to remove this?"))
            info.event.remove();
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

function resetButtonClick() {
    const calendarApi = calendarRef.value.getApi()
    calendarApi?.removeAllEvents();
}

function finishButtonClicked() {
    const calendarApi = calendarRef.value.getApi()
    const events: EventApi[] = calendarApi.getEvents()
    console.log(
        events.map(e => ({
        id: e.id,
        title: e.title,
        start: e.start,
        end: e.end
    })));
}

</script>

<template>
    <Head title="Create a Schedule" />
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

