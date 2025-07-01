<script setup lang="ts">
    import {ref} from "vue";
    import Button from 'primevue/button'
    import OverlayPanel from 'primevue/overlaypanel'
    import {router} from "@inertiajs/vue3";
    const navLinks = [
        {
            text: 'Dashboard',
            link: route('patient.dashboard'),
        },
        {
            text: 'Dashboard',
            link: route('patient.dashboard'),
        },
    ];
    const overlayRef = ref()

    function toggle(event: Event) {
        overlayRef.value.toggle(event)
    }
    function logout() {
        router.post(route('logout'));
    }
</script>

<template>
    <aside class="w-[18rem] flex flex-col bg-green-500 py-6 items-center justify-between ">
        <h3 class="text-white font-semibold text-xl">
            Booking
        </h3>
        <nav class="flex flex-col w-full flex-1 mt-8">
            <a
                v-for="link in navLinks"
                :href="link.link"
                class="px-3 py-2 hover:bg-green-600 w-full text-center text-white"
            >
                {{link.text}}
            </a>
        </nav>
        <div class="flex flex-col p-3">
            <Button
                type="button"
                icon="pi pi-user"
                label="Profile"
                @click="toggle"
                aria-haspopup="true"
            />
            <OverlayPanel ref="overlayRef">
                <ul class="flex flex-col gap-2 min-w-[160px]">
                    <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer pl-6" >
                        Profile
                    </li>
                    <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer pl-6" >
                        Settings
                    </li>
                    <li class="border-t my-1"></li>
                    <button @click="logout"
                        class="px-4 pl-6 py-2 hover:bg-gray-100 cursor-pointer text-start  text-red-500"
                    >
                        Log out
                    </button>
                </ul>
            </OverlayPanel>
        </div>

    </aside>
</template>

