<script setup lang="ts">
    import Sidebar from '@/Components/Sidebar.vue'

    defineProps<{headerTitle: string}>()
</script>

<template>
    <div class="flex min-h-screen w-full relative">
        <Sidebar/>
        <div class="h-screen w-[18rem]"></div>
        <main class="flex-1 flex flex-col">
            <header class="flex items-center justify-between p-4 shadow-sm">
                <h4>
                    {{headerTitle ?? "Auth"}}
                </h4>
            </header>
            <div class="flex-1">
                <slot />
            </div>
        </main>
    </div>
</template>
