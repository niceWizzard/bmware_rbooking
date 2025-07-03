<script setup lang="ts">

import InputText from "primevue/inputtext";
import Dialog from "primevue/dialog";
import Button from 'primevue/button'
import {computed, ref} from "vue";
import {EventApi} from "@fullcalendar/core";
import dayjs from "dayjs";
import {Doctor} from "@/types";


const selectedSlot = ref<EventApi|null>(null);
const isLoading = ref(false);
const visible = computed({
    get: () => selectedSlot.value != null,
    set: (val: boolean) => {
        if (!val) selectedSlot.value = null;
    }
});

const {onSubmit, onDelete} = defineProps<{
    onSubmit: (slot: EventApi, setIsLoading: (v: boolean) => void,) => void,
    onDelete: (slot: EventApi, setIsLoading: (v: boolean) => void,) => void,
    doctor : Doctor,
}>()

defineExpose({
    setSlot(e : EventApi|null){
        selectedSlot.value = e;
    },
});

const time = computed(() => {
    return dayjs.utc(selectedSlot.value?.start).format('YYYY-MM-DD (dddd) h a') +
        dayjs.utc(selectedSlot.value?.end).format(' to h a');
})

function submit() {
    isLoading.value = true;
    onSubmit(selectedSlot.value!, (v) => isLoading.value = v);
}

function onCancel() {
    isLoading.value = true;
    onDelete(selectedSlot.value!, (v) => isLoading.value = v);
}


</script>

<template>
    <Dialog
        v-model:visible="visible"
        modal
        header="Book"
        :style="{ width: '25rem' }"
        :dismissable-mask="!isLoading"
    >
        <form
            @submit.prevent="submit"
            class="flex gap-4 flex-col"
            v-if="selectedSlot?.extendedProps.type === 'free'"
        >
            <div class="flex flex-col gap-2">
                <label for="doctor">Doctor</label>
                <InputText
                    id="doctor"
                    readonly
                    :value="doctor.name"
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
                :loading="isLoading"
            />
        </form>
        <div class="flex flex-col gap-4" v-else>
            <div class="flex flex-col gap-2">
                <label for="doctor">Doctor</label>
                <InputText
                    id="doctor"
                    readonly
                    :value="doctor.name"
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
            <div class="flex flex-col gap-2">
                <label for="booked_at">Booked at</label>
                <InputText
                    id="booked_at"
                    readonly
                    :value="dayjs(selectedSlot?.extendedProps.booked_at).format('YYYY-MM-DD (dddd) hh:mm a')"
                />
            </div>
            <Button
                type="button"
                label="Close"
                variant="text"
                severity="secondary"
                @click="visible = false"
            />
            <Button
                type="submit"
                label="Cancel"
                icon="pi pi-trash"
                severity="danger"
                :loading="isLoading"
                @click="onCancel"
            />
        </div>
    </Dialog>
</template>
