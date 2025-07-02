
<script setup lang="ts">
import InputText from "primevue/inputtext";
import Dialog from "primevue/dialog";
import Button from "primevue/button";
import DatePicker from "primevue/datepicker";
import {EventApi} from "@fullcalendar/core";
import {computed, reactive, watchEffect} from "vue";
import dayjs from "dayjs";

const selectedEvent = defineModel<EventApi | null>();

const data = reactive({
    clinic : '',
    start: new Date(),
    end: new Date(),
})

const visible = computed({
    get: () => selectedEvent.value != null,
    set: (val: boolean) => {
        if (!val) selectedEvent.value = null;
    }
});

watchEffect(() => {
   if(selectedEvent.value) {
       data.clinic = selectedEvent.value.extendedProps.clinic ?? "";
       data.start = selectedEvent.value.start ?? new Date();
       data.end = selectedEvent.value.end ?? dayjs(data.start).add(1, 'hour').toDate();
   }
});
watchEffect(() => {
    if (dayjs(data.start).isAfter(dayjs(data.end))) {
        data.end = new Date(data.start); // sync end with start
    }
});

function save() {
    if(selectedEvent.value) {
        selectedEvent.value.setExtendedProp('clinic', data.clinic);
        selectedEvent.value.setStart(data.start);
        selectedEvent.value.setEnd(data.end);
    }
    visible.value = false;
}

function removeEvent() {
    if(selectedEvent.value) {
        selectedEvent.value.remove();
        visible.value = false;
    }
}

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
                        :disabled="!data.clinic"
                />
            </div>
        </form>
    </Dialog>
</template>

