<script setup lang="ts">

import Button from "primevue/button";
import InputText from "primevue/inputtext";
import Dialog from "primevue/dialog";
import {computed, ref} from "vue";
import {EventApi} from "@fullcalendar/core";
import {Doctor} from "@/types";
import dayjs from "dayjs";


const selectedSlot = ref<EventApi|null>(null);
const isLoading = ref(false);
const visible = computed({
    get: () => selectedSlot.value != null,
    set: (val: boolean) => {
        if (!val) selectedSlot.value = null;
    }
});

const {onSubmit, } = defineProps<{
    onSubmit: (slot: EventApi, setIsLoading: (v: boolean) => void,) => void,
    doctor : Doctor,
}>()

defineExpose({
    setSlot(e : EventApi|null){
        selectedSlot.value = e;
    },
});

function submit() {
    isLoading.value = true;
    onSubmit(selectedSlot.value!, (v) => isLoading.value = v);
}

const time = computed(() => {
    return dayjs.utc(selectedSlot.value?.start).format('YYYY-MM-DD (dddd) h a') +
        dayjs.utc(selectedSlot.value?.end).format(' to h a');
})

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
                label="Set"
                icon="pi pi-clock"
                :loading="isLoading"
            />
        </form>
    </Dialog>
</template>
