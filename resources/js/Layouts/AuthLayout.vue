<script setup lang="ts">
import Sidebar from '@/Components/Sidebar.vue';
import Toast from 'primevue/toast';

defineProps<{ headerTitle: string }>();
</script>

<template>
    <Toast position="bottom-right" />
    <div class="relative flex min-h-screen w-full">
        <Sidebar />
        <div class="h-screen w-[14rem]"></div>
        <main class="flex flex-1 flex-col">
            <header class="flex items-center justify-between p-4 shadow-sm">
                <h4>
                    {{ headerTitle ?? 'Auth' }}
                </h4>
            </header>
            <div class="flex-1">
                <slot />
            </div>
        </main>
    </div>
</template>
