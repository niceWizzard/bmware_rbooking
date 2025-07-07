<script setup lang="ts">
import Input from '@/Components/Input.vue';
import { Patient } from '@/types';
import { useForm } from '@inertiajs/vue3';
import Button from 'primevue/button';
import DatePicker from 'primevue/datepicker';
import InputMask from 'primevue/inputmask';
import Select from 'primevue/select';

const { patient } = defineProps<{
    patient: Patient;
}>();

const form = useForm({
    first_name: patient.first_name,
    middle_name: patient.middle_name ?? '',
    last_name: patient.last_name,
    birthdate: new Date(patient.birthdate),
    gender: patient.gender,
    civil_status: patient.civil_status,
    height: patient.height ?? '',
    weight: patient.weight ?? '',
    mobile: patient.mobile,
    telephone: patient.telephone ?? '',
    address: patient.address ?? '',
    occupation: patient.occupation ?? '',
    guardian_name: patient.guardian_name ?? '',
    relationship: patient.relationship ?? '',
    guardian_address: patient.guardian_address ?? '',
});

function submit() {
    form.transform((v) => ({
        ...v,
        mobile: v.mobile?.replaceAll('-', ''),
    })).post(route('patient.profile'), {
        preserveScroll: true,
    });
}
</script>
<template>
    <form
        class="h-lg container mx-auto flex w-full flex-col gap-4 rounded-md border border-gray-200 p-4 shadow-sm"
        @submit.prevent="submit"
    >
        <h3 class="mb-4 text-lg">Patient Information</h3>
        <div class="flex w-full flex-wrap gap-2">
            <Input
                id="first_name"
                label="First Name"
                v-model="form.first_name"
                class="max-w-xl"
                :error="form.errors.first_name"
            />
            <Input
                id="middle_name"
                label="Middle Name"
                v-model="form.middle_name"
                :error="form.errors.middle_name"
            />
            <Input
                id="last_name"
                label="Last Name"
                v-model="form.last_name"
                class="max-w-xl"
                :error="form.errors.last_name"
            />
        </div>
        <div class="flex max-w-xl flex-col">
            <label
                for="birth_date"
                class="mb-1 text-sm font-medium text-gray-700"
                >Birthdate</label
            >
            <DatePicker
                id="birth_date"
                v-model="form.birthdate"
                showIcon
                class="w-full"
                :class="{ 'p-invalid': form.errors.birthdate }"
                placeholder="Select birthdate"
            />
            <small v-if="form.errors.birthdate" class="p-error">
                {{ form.errors.birthdate }}
            </small>
        </div>
        <div class="flex w-full flex-wrap gap-2">
            <Select
                id="gender"
                v-model="form.gender"
                class="max-w-xs"
                :options="['Male', 'Female']"
                placeholder="Select Gender"
            />
            <small v-if="form.errors.gender" class="p-error">{{
                form.errors.gender
            }}</small>
            <Select
                id="civil_status"
                v-model="form.civil_status"
                class="max-w-xs"
                :options="['Single', 'Married', 'Divorced']"
                placeholder="Select Civil Status"
            />
            <small v-if="form.errors.civil_status" class="p-error">{{
                form.errors.civil_status
            }}</small>
            <Input
                id="height"
                label="Height (cm)"
                v-model="form.height"
                class="max-w-xl"
                :error="form.errors.height"
            />
            <Input
                id="weight"
                label="Weight (kg)"
                v-model="form.weight"
                class="max-w-xl"
                :error="form.errors.weight"
            />
        </div>
        <InputMask
            id="mobile"
            v-model="form.mobile"
            mask="0999-999-9999"
            placeholder="09XX-XXX-XXXX"
            class="max-w-xl"
            :class="{ 'p-invalid': form.errors.mobile }"
        />
        <small v-if="form.errors.mobile" class="p-error">{{
            form.errors.mobile
        }}</small>
        <Input
            id="telephone"
            label="Telephone"
            v-model="form.telephone"
            class="max-w-xl"
            :error="form.errors.telephone"
        />
        <Input
            id="address"
            label="Address"
            v-model="form.address"
            class="max-w-xl"
            :error="form.errors.address"
        />
        <Input
            id="occupation"
            label="Occupation"
            v-model="form.occupation"
            class="max-w-xl"
            :error="form.errors.occupation"
        />
        <p class="text-lg font-medium">Guardian Information</p>
        <div class="flex flex-wrap gap-2">
            <Input
                id="guardian_name"
                label="Guardian Name"
                v-model="form.guardian_name"
                class="w-full max-w-xl"
                :error="form.errors.guardian_name"
            />
            <Input
                id="relationship"
                label="Relationship"
                v-model="form.relationship"
                class="max-w-xl"
                :error="form.errors.relationship"
            />
            <Input
                id="guardian_address"
                label="Guardian Address"
                v-model="form.guardian_address"
                class="w-full max-w-xl"
                :error="form.errors.guardian_address"
            />
        </div>
        <Button
            type="submit"
            label="Save"
            class="w-fit self-start"
            :loading="form.processing"
        />
        <Transition
            enter-active-class="transition ease-in-out"
            enter-from-class="opacity-0"
            leave-active-class="transition ease-in-out"
            leave-to-class="opacity-0"
        >
            <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">
                Saved.
            </p>
        </Transition>
    </form>
</template>
