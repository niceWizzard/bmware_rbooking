<script setup lang="ts">
    import {ref} from "vue";
    import Button from 'primevue/button'
    import Popover from 'primevue/popover'
    import {router, usePage} from "@inertiajs/vue3";

    const loggingOut = ref(false);
    const {props} = usePage();
    const {user} = props.auth;
    const navLinks = user!.role === 'patient' ? [
        {
            text: 'Dashboard',
            link: route('patient.dashboard'),
        },
    ] : [
        {
            text: 'Dashboard',
            link: route('admin.dashboard'),
        },{
            text: 'Doctors',
            link: route('doctor.list'),
        }
    ];


    const overlayRef = ref()

    function toggle(event: Event) {
        overlayRef.value.toggle(event)
    }
    function logout() {
        loggingOut.value = true;
        router.post(route('logout'));
    }
</script>

<template>
    <aside class="w-[14rem] fixed top-0 left-0 h-screen flex flex-col bg-green-500 py-6 items-center justify-between ">
        <h3 class="text-white font-semibold text-xl">
            Booking
        </h3>
        <nav class="flex flex-col w-full flex-1 mt-8">
            <Link
                v-for="link in navLinks"
                :href="link.link"
                class="px-3 py-2 hover:bg-green-600 w-full text-center text-white"
            >
                {{link.text}}
            </Link>
        </nav>
        <div class="flex flex-col p-3">
            <Button
                type="button"
                icon="pi pi-user"
                :label="user?.role === 'patient' ? user.patient.first_name : user?.admin.name"
                @click="toggle"
                aria-haspopup="true"
            />
            <Popover ref="overlayRef">
                <ul class="flex flex-col gap-2 min-w-[160px]">
                    <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer pl-6" >
                        Profile
                    </li>
                    <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer pl-6" >
                        Settings
                    </li>
                    <li class="border-t my-1"></li>
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

