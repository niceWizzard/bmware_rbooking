<script setup lang="ts">
  import Layout from '@/Layouts/AuthLayout.vue'
  import { Head } from '@inertiajs/vue3'
  import Button from "primevue/button";
  import ScheduleCalendarCreator from "@/Components/Schedule/ScheduleCalendarCreator.vue";
  import {Doctor} from "@/types";
  import {useToast} from "primevue";
  import {ref} from "vue";
  import {EventInput} from "@fullcalendar/core";
  import axios from "axios";
  import dayjs from "dayjs";

  const {doctor} = defineProps<{
      doctor : Doctor,
      schedule: EventInput[],
  }>()

  const toast = useToast();
  const calendarRef = ref<InstanceType<typeof ScheduleCalendarCreator>>();

  async function update() {
      const eventsFromCalendar = calendarRef.value?.getCalendarEvents();
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

      try {
          const res = await axios.post(route('schedule.edit', doctor.id), {
              events
          })
          if(!res.data.success) {
              throw new Error(res.data.message);
          }
          toast.add({
              severity: 'success',
              summary: 'Schedule updated successfully',
              life: 2000,
          })
      } catch (e) {
          const err = e as Error;
          toast.add({
              severity: 'error',
              detail: err.message,
              summary: 'Something went wrong.',
              life: 2000,
          })
        console.log(err);
      }
  }

</script>

<template>
  <Layout header-title="Edit Schedule">
    <Head title="EditSchedule" />
      <section class="flex flex-col p-8 ">
          <h3 class="text-2xl">Dr. {{doctor.name}}</h3>
          <p class="text-lg text-gray-600">
              Edit the schedule of the doctor
          </p>
          <div class="flex justify-end w-full gap-3">
              <Button
                  label="Reset"
                  severity="danger"
                  @click="calendarRef?.resetCalendar(true)"
              />
              <Button
                  label="Update"
                  @click="update"
              />
          </div>
          <div class="flex-1 p-4">
              <ScheduleCalendarCreator :toast="toast" ref="calendarRef" :initial-events="schedule" />
          </div>
      </section>
  </Layout>
</template>
