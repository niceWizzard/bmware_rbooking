<script setup lang="ts">
import { Doctor } from '@/types';
import { EventApi } from '@fullcalendar/core';
import dayjs from 'dayjs';
import Button from 'primevue/button';
import Dialog from 'primevue/dialog';
import InputText from 'primevue/inputtext';
import { computed, ref } from 'vue';

const selectedSlot = ref<EventApi | null>(null);
const isLoading = ref(false);
const visible = computed({
    get: () => selectedSlot.value != null,
    set: (val: boolean) => {
        if (!val) selectedSlot.value = null;
    },
});

const { onDelete } = defineProps<{
    onDelete: (slot: EventApi, setIsLoading: (v: boolean) => void) => void;
    doctor: Doctor;
}>();

defineExpose({
    setSlot(e: EventApi | null) {
        selectedSlot.value = e;
    },
});

const time = computed(() => {
    return (
        dayjs.utc(selectedSlot.value?.start).format('YYYY-MM-DD (dddd) h a') +
        dayjs.utc(selectedSlot.value?.end).format(' to h a')
    );
});

function onCancel() {
    isLoading.value = true;
    onDelete(selectedSlot.value!, (v) => (isLoading.value = v));
}
</script>

<template>
    <Dialog
        v-model:visible="visible"
        modal
        header="Appointment Information"
        :style="{ width: '25rem' }"
        :dismissable-mask="!isLoading"
    >
        <form class="flex flex-col gap-4">
            <div class="flex flex-col gap-2">
                <label for="doctor">Doctor</label>
                <InputText id="doctor" readonly :value="doctor.name" />
            </div>
            <div class="flex flex-col gap-2">
                <label for="patient">Patient</label>
                <InputText
                    id="patient"
                    readonly
                    :value="
                        selectedSlot?.extendedProps.patient ?? 'PATIENT NAME'
                    "
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
                <InputText id="time" readonly :value="time" />
            </div>
            <Button
                label="Cancel Appointment"
                icon="pi pi-trash"
                severity="danger"
                @click="onCancel"
                :loading="isLoading"
            />
        </form>
    </Dialog>
</template>
