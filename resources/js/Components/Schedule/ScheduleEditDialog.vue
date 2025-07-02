
<script setup lang="ts">
import InputText from "primevue/inputtext";
import Dialog from "primevue/dialog";
import Button from "primevue/button";
import DatePicker from "primevue/datepicker";
import {EventApi} from "@fullcalendar/core";
import {computed, reactive, watch, watchEffect} from "vue";
import dayjs from "dayjs";

const selectedEvent = defineModel<EventApi | null>();

const now = dayjs().minute(0).second(0);
const data = reactive({
    clinic : '',
    start:  now.toDate(),
    end: now.add(1, "h").toDate(),
})

const visible = computed({
    get: () => selectedEvent.value != null,
    set: (val: boolean) => {
        if (!val) selectedEvent.value = null;
    }
});

watch(selectedEvent, (event) => {
    if (event) {
        data.clinic = event.extendedProps.clinic ?? "";
        data.start = event.start ? new Date(event.start) : new Date();
        data.end = event.end ? new Date(event.end) : dayjs(data.start).add(1, "hour").toDate();
    }
});


watchEffect(() => {
    const start = dayjs(data.start);
    const end = dayjs(data.end);

    const startMinutes = start.hour() * 60 + start.minute();
    const endMinutes = end.hour() * 60 + end.minute();

    if (startMinutes >= endMinutes) {
        // If start is greater than or equal to end, shift end forward by 1 hour
        data.end = start.add(1, 'hour').toDate();
    }
});




function save() {
    if(selectedEvent.value) {
        const eventDay = dayjs(selectedEvent.value.start);
        selectedEvent.value.setExtendedProp('clinic', data.clinic);
        selectedEvent.value.setStart(
            eventDay.hour(dayjs(data.start).hour()).toDate(),
        );
        selectedEvent.value.setEnd(
            eventDay.hour(dayjs(data.end).hour()).toDate(),
        );
    }
    visible.value = false;
}

function removeEvent() {
    if(selectedEvent.value) {
        selectedEvent.value.remove();
        visible.value = false;
    }
}

const isInvalidData = computed(() => !data.clinic || dayjs(data.start).isSame(data.end));

</script>


<template>
    <Dialog
        v-model:visible="visible"
        modal
        header="Edit Schedule"
        :style="{ width: '25rem' }"
    >
    <span class="text-surface-500 dark:text-surface-400 block mb-8">
        Update the information.
    </span>
        <form
        @submit.prevent="save"
        class="flex gap-4 flex-col"
        >
            <div class="flex items-center gap-4">
                <label for="clinic" class="font-semibold w-24">Clinic</label>
                <InputText
                    id="clinic"
                    class="flex-auto"
                    autocomplete="off"
                    placeholder="Alphamed Diagnostics"
                    v-model="data.clinic"
                />
            </div>
            <div class="flex items-center gap-4">
                <label for="clinic" class="font-semibold w-24">Start</label>
                <DatePicker
                    time-only
                    v-model="data.start"
                    :step-minute="60"
                />
                <label for="clinic" class="font-semibold w-24">End</label>
                <DatePicker
                    time-only
                    v-model="data.end"
                    :step-minute="60"
                />
            </div>

            <div class="flex justify-end gap-2">
                <Button type="button" severity="danger" label="Remove" @click="removeEvent" />
                <Button type="button" label="Cancel" severity="secondary" @click="visible = false" />
                <Button type="submit" label="Save"
                        :disabled="isInvalidData"
                />
            </div>
        </form>
    </Dialog>
</template>

