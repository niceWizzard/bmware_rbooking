<script setup lang="ts">
import { Link, router, usePage } from '@inertiajs/vue3';
import Button from 'primevue/button';
import Popover from 'primevue/popover';
import { ref } from 'vue';

const loggingOut = ref(false);
const { props } = usePage();
const { user } = props.auth;
const navLinks =
    user!.role === 'patient'
        ? [
              {
                  text: 'Book',
                  link: route('patient.book'),
              },
              {
                  text: 'Doctors',
                  link: route('patient.doctors'),
              },
          ]
        : [
              {
                  text: 'Dashboard',
                  link: route('admin.dashboard'),
              },
              {
                  text: 'Doctors',
                  link: route('doctor.list'),
              },
              {
                  text: 'Patients',
                  link: route('patient.list'),
              },
          ];

const overlayRef = ref();

function toggle(event: Event) {
    overlayRef.value.toggle(event);
}
function logout() {
    loggingOut.value = true;
    router.post(route('logout'));
}
</script>

<template>
    <aside
        class="left-0 top-0 flex flex-col items-center justify-between bg-primary py-6 max-sm:h-[14rem] max-sm:w-full sm:fixed sm:h-screen sm:w-[14rem]"
    >
        <h3 class="text-xl font-semibold text-white">Booking</h3>
        <nav class="mt-8 flex w-full flex-1 flex-col">
            <Link
                v-for="link in navLinks"
                :href="link.link"
                class="w-full px-3 py-2 text-center text-white hover:bg-primary"
                v-bind:key="link.link"
            >
                {{ link.text }}
            </Link>
        </nav>
        <div class="flex flex-col p-3">
            <Button
                type="button"
                icon="pi pi-user"
                :label="
                    user?.role === 'patient'
                        ? user.patient.first_name
                        : user?.admin.name
                "
                @click="toggle"
                aria-haspopup="true"
            />
            <Popover ref="overlayRef">
                <ul class="flex min-w-[160px] flex-col gap-2">
                    <Link
                        :href="route('profile.edit')"
                        class="cursor-pointer px-4 py-2 pl-6 hover:bg-gray-100"
                    >
                        Profile
                    </Link>

                    <li class="my-1 border-t"></li>
                    <Button
                        @click="logout"
                        :loading="loggingOut"
                        label="Log out"
                        severity="danger"
                        variant="text"
                        icon="pi pi-sign-out"
                    />
                </ul>
            </Popover>
        </div>
    </aside>
</template>
