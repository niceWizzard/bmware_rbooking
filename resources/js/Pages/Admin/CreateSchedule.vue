<script setup lang="ts">
import ScheduleCalendarCreator from '@/Components/Schedule/ScheduleCalendarCreator.vue';
import AuthLayout from '@/Layouts/AuthLayout.vue';
import { Doctor } from '@/types';
import { Head, router, usePage } from '@inertiajs/vue3';
import dayjs from 'dayjs';
import { useConfirm, useToast } from 'primevue';
import Button from 'primevue/button';
import ConfirmDialog from 'primevue/confirmdialog';
import { ref, watchEffect } from 'vue';

const toast = useToast();
const confirmPopup = useConfirm();

const calendarRef = ref<InstanceType<typeof ScheduleCalendarCreator>>();

const props = defineProps<{
    doctor: Doctor;
}>();

watchEffect(() => {
    const { props: pageProps } = usePage();
    if (pageProps.flash?.message) {
        toast.add({
            severity: 'error',
            summary: 'Error',
            detail: pageProps.flash.message,
        });
    }
});

function resetButtonClick() {
    confirmPopup.require({
        message: 'Are you sure you want to reset everything?',
        icon: 'pi pi-exclamation-triangle',
        rejectProps: {
            label: 'Cancel',
            severity: 'secondary',
            outlined: true,
        },
        acceptProps: {
            label: 'Reset',
            severity: 'danger',
        },
        accept: () => {
            calendarRef.value?.resetCalendar();
        },
    });
}

function finishButtonClicked() {
    const eventsFromCalendar = calendarRef.value!.getCalendarEvents();
    const events = eventsFromCalendar.map((e) => ({
        id: e.id,
        title: e.title,
        start: dayjs(e.start).format('YYYY-MM-DDTHH:mm:ss'), // no Z suffix
        end: dayjs(e.end).format('YYYY-MM-DDTHH:mm:ss'),
        clinic: e.extendedProps.clinic,
        day: dayjs(e.start).day(),
    }));
    if (events.length === 0) {
        toast.add({
            detail: 'Please add a schedule.',
            life: 2000,
            closable: false,
        });
        return;
    }
    const eventCountsByDate = events.reduce(
        (acc, event) => {
            const dateKey = dayjs(event.start).day();
            acc[dateKey] = (acc[dateKey] || 0) + 1;
            return acc;
        },
        {} as Record<string, number>,
    );
    const [maxDate, maxCount] = Object.entries(eventCountsByDate).reduce(
        ([maxD, maxC], [date, count]) =>
            count > maxC ? [date, count] : [maxD, maxC],
        ['', 0],
    );

    if (maxCount > 1) {
        toast.add({
            detail: `There are ${maxCount} events in ${dayjs(maxDate).format('dddd')}`,
            life: 2000,
            severity: 'error',
        });
        return;
    }

    let safe = true;
    for (const event of events) {
        if (!event.clinic) {
            safe = false;
            toast.add({
                detail: `Please add a clinic in ${dayjs(event.start).format('dddd')}`,
                life: 2000,
                severity: 'error',
            });
            break;
        }
    }

    if (!safe) return;

    router.post(route('schedule.create', props.doctor.id), { events });
}
</script>

<template>
    <Head title="Create a Schedule" />
    <ConfirmDialog />
    <AuthLayout header-title="Create Schedule">
        <section class="flex flex-col p-8">
            <h3 class="text-lg">Dr. {{ doctor.name }}</h3>
            <div class="flex w-full justify-end gap-3">
                <Button
                    label="Reset"
                    severity="danger"
                    @click="resetButtonClick"
                />
                <Button label="Finish" @click="finishButtonClicked" />
            </div>
            <div class="flex-1 p-4">
                <ScheduleCalendarCreator :toast="toast" ref="calendarRef" />
            </div>
        </section>
    </AuthLayout>
</template>

<style scoped></style>
