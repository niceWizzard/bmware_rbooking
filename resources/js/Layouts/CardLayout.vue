<script setup lang="ts">
defineProps<{
    header?: string;
}>();
</script>

<template>
    <main class="grid min-h-screen w-full place-items-center">
        <section class="rounded-2xl border bg-white p-6 shadow-sm">
            <!-- Check for named slot 'header' first, then fallback to prop -->
            <div
                v-if="$slots.header || header"
                class="mb-4 text-xl font-semibold"
            >
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
