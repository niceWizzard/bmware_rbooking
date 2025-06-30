<script setup lang="ts">
defineProps<{
    header?: string
}>()
</script>

<template>
    <main class="w-full min-h-screen grid place-items-center">
        <section class="rounded-2xl border bg-white shadow-sm p-6">
            <!-- Check for named slot 'header' first, then fallback to prop -->
            <div v-if="$slots.header || header" class="mb-4 text-xl font-semibold">
                <slot name="header">
                    {{ header }}
                </slot>
            </div>

            <!-- Default slot -->
            <div>
                <slot />
            </div>
        </section>
    </main>
</template>
