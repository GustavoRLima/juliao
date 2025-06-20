<script setup lang="ts">
const props = withDefaults(
  defineProps<{
    type?: 'button' | 'submit' | 'reset';
    buttonClass?: string;
    icon?: string | null;
    sugestion?: string | null;
  }>(),
  {
    type: 'button',
    buttonClass: 'btn-primary',
    icon: null,
    sugestion: '',
  }
);

const emit = defineEmits<{
  (e: 'click', event: MouseEvent): void;
}>();
</script>

<template>
  <button :type="props.type" @click="$emit('click', $event)"
    v-tooltip="{ 
      content: sugestion, 
      placement: 'top', 
      html: true
    }"
  >
    <i v-if="props.icon" :class="props.icon"></i>
    <slot />
  </button>
</template>
