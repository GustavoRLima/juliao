<script setup lang="ts">
import { computed, ref } from 'vue';
import { useErrorStore } from "@/stores/errorShow";

const props = defineProps<{
  label?: string,
  id: string,
  name: string,
  options: { value: string | number, label: string }[],
  modelValue: string | number | null,
  required?: boolean
}>();

const emit = defineEmits<{
  (e: 'update:modelValue', value: string | number | null): void
}>();

const errorStore = useErrorStore();
const touched = ref(false);

const fieldError = computed(() => {
  return errorStore.errors[props.id] || null;
});

const selectedValue = computed({
  get: () => props.modelValue,
  set: (value) => {
    emit('update:modelValue', value);
    touched.value = true;
    if (props.id) {
      errorStore.clearError(props.id);
    }
  }
});
</script>

<template>
  <div class="sm:col-span-3">
    <label :for="props.id" class="block text-sm font-medium text-white">
      {{ props.label }}
      <span v-if="required" class="text-red-400 ml-1">*</span>
    </label>

    <div class="mt-2 space-y-2">
      <div
        v-for="option in props.options"
        :key="option.value"
        class="flex items-center"
      >
        <input
          type="radio"
          :name="props.name"
          :id="`${props.id}-${option.value}`"
          :value="option.value"
          v-model="selectedValue"
          :required="required"
          class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300"
        />
        <label
          :for="`${props.id}-${option.value}`"
          class="ml-2 block text-sm text-gray-300"
        >
          {{ option.label }}
        </label>
      </div>
    </div>

    <div v-if="fieldError" class="text-red-400 text-xs mt-1">
      {{ fieldError }}
    </div>
  </div>
</template>
