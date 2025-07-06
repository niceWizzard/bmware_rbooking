<script setup lang="ts">
import FloatLabel from 'primevue/floatlabel';
import InputText from 'primevue/inputtext';
import { computed } from 'vue';

const props = defineProps<{
    id: string;
    label: string;
    type?: string;
    error?: string;
    autocomplete?: string;
}>();

const model = defineModel<string>();

const inputId = computed(
    () => props.id || props.label.toLowerCase().replace(/\s+/g, '_'),
);
</script>

<template>
    <div class="flex flex-col gap-1">
        <FloatLabel variant="on" class="w-full">
            <InputText
                class="w-full"
                :id="inputId"
                :type="type || 'text'"
                :autocomplete="autocomplete"
                v-model="model"
            />
            <label :for="inputId">{{ label }}</label>
        </FloatLabel>

        <Transition
            enter-active-class="transition-all duration-300 ease-out"
            enter-from-class="opacity-0 -translate-y-1"
            enter-to-class="opacity-100 translate-y-0"
            leave-active-class="transition-all duration-200 ease-in"
            leave-from-class="opacity-100 translate-y-0"
            leave-to-class="opacity-0 -translate-y-1"
        >
            <p v-if="error" class="text-sm font-light text-red-500">
                {{ error }}
            </p>
        </Transition>
    </div>
</template>
