<script setup lang="ts">
import { computed, onUpdated, ref } from 'vue';
import { useErrorStore } from "@/stores/errorShow";


const props = defineProps<{
    label?: string,
    id: string,
    name: string,
    modelValue: string | number | Date | null,
    type: string
    required?: boolean
    placeholder?: string
}>();

const emit = defineEmits<{
    (e: 'update:modelValue', value: string | number | Date | null): void
}>();

const errorStore = useErrorStore();
const touched = ref(false);

const fieldError = computed(() => {
    return errorStore.errors[props.id] || null;
});

const selectedValue = computed({
  get: () => props.modelValue,
  set: (value) => {
    emit("update:modelValue", value);
    touched.value = true;
    if (props.id) {
      errorStore.clearError(props.id);
    }
  },
});
</script>

<template>
     <div class="sm:col-span-3">
        <label :for="props.id" class="block text-sm/6 font-medium text-gray-900 text-white">{{props.label}} <span v-if="required" class="text-red-400">*</span></label>
        <div class="mt-2">
            <input 
            :type="type" 
            :name="props.name" 
            :id="props.id" autocomplete="given-name" 
            v-model="selectedValue"
            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
            v-bind="$attrs"
            :required="required"
            :placeholder="placeholder"
            >
        </div>
        <div v-if="fieldError" class="invalid-feedback">
            <span class="text-red-400">{{ fieldError }}</span>
        </div>
    </div>
</template>