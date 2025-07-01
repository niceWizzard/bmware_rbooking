<script setup lang="ts">
    import Sidebar from '@/Components/Sidebar.vue'
</script>

<template>
    <div class="flex min-h-screen w-full">
        <Sidebar/>
        <main class="flex-1">
            <slot/>
        </main>
    </div>
</template>
