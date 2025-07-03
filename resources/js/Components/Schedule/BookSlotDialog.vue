<script setup lang="ts">

import InputText from "primevue/inputtext";
import Dialog from "primevue/dialog";
import Button from 'primevue/button'
import {computed, ref} from "vue";
import {EventApi} from "@fullcalendar/core";
import dayjs from "dayjs";


const selectedSlot = ref<EventApi|null>(null);

const visible = computed({
    get: () => selectedSlot.value != null,
    set: (val: boolean) => {
        if (!val) selectedSlot.value = null;
    }
});

const {onSubmit} = defineProps<{
    onSubmit: (slot: EventApi) => void,
}>()

defineExpose({
    setSlot(e : EventApi|null){
        selectedSlot.value = e;
    }
});

const time = computed(() => {
    return dayjs(selectedSlot.value?.start).format('YYYY-MM-DD (dddd) h a') +
        dayjs(selectedSlot.value?.end).format(' to h a');
})

function submit() {
    onSubmit(selectedSlot.value!);
}


</script>

<template>
    <Dialog
        v-model:visible="visible"
        modal
        header="Book"
        :style="{ width: '25rem' }"
        dismissable-mask
    >
        <form
            @submit.prevent="submit"
            class="flex gap-4 flex-col"
        >
            <div class="flex flex-col gap-2">
                <label for="doctor">Doctor</label>
                <InputText
                    id="doctor"
                    readonly
                    :value="selectedSlot?.extendedProps.doctor"
                />
            </div>
            <div class="flex flex-col gap-2">
                <label for="clinic">Clinic</label>
                <InputText
                    readonly
                    id="clinic"
                    :value="selectedSlot?.extendedProps.clinic"
                />
            </div>
            <div class="flex flex-col gap-2">
                <label for="time">Time</label>
                <InputText
                    id="time"
                    readonly
                    :value="time"
                />
            </div>
            <Button
                type="button"
                label="Cancel"
                variant="text"
                severity="secondary"
                @click="visible = false"
            />
            <Button
                type="submit"
                label="Book"
                icon="pi pi-clock"
            />
        </form>
    </Dialog>
</template>
