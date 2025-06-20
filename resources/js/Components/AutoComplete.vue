<script setup lang="ts">
import { ref, computed, watch, nextTick, onMounted, onUnmounted, useSlots } from 'vue';
import axios from 'axios';
import { useErrorStore } from '@/stores/errorShow';

interface OptionItem {
  [key: string]: any;
}

const props = withDefaults(
  defineProps<{
    modelValue: string | number | null;
    selectedOption?: OptionItem | null;
    id?: string;
    name?: string;
    clearable?: boolean;
    classWrapper?: string;
    options?: OptionItem[];
    apiUrl?: string | "";
    labelKey?: string;
    idKey?: string;
    minInput?: number | 2;
    selectClass?: string;
    error?: string | null;
    required?: boolean | false;
    placeHolderProps?: string | 'Selecione uma opção';
    params?: object | {};
    selectedOpen?: boolean | true;
  }>(),
  {
    required: false,
    minInput: 2,
    apiUrl: "",
    error: null,
    selectedOpen: true
  }
);

const emit = defineEmits<{
  (e: 'update:modelValue', value: string | number | null): void;
  (e: 'update:selectedOption', value: OptionItem | null): void;
}>();

const errorStore = useErrorStore();
const search = ref('');
const selectedItem = ref<OptionItem | null>(null);
const filteredOptions = ref<OptionItem[]>([]);
const showDropdown = ref(false);
const searchInput = ref<HTMLInputElement | null>(null);
const dropdownContainer = ref<HTMLElement | null>(null);
const highlightedIndex = ref(-1);
const cachedOptions = ref<OptionItem[]>([]);
let abortController: AbortController | null = null;

const selectedText = computed(() => selectedItem.value?.[props.labelKey || 'name'] || '');
const displayPlaceholder = computed(() => (selectedText.value ? '' : props.placeHolderProps));
const slots = useSlots();

const labelText = computed(() => {
  const defaultSlot = slots.default?.();
  if (defaultSlot && defaultSlot[0]) {
    const children = defaultSlot[0].children;
    if (typeof children === 'string') return children;
    else if (Array.isArray(children)) return children.map(child => (typeof child === 'string' ? child : '')).join('');
  }
  return '';
});

const fieldError = computed(() => {
  if (props.error) return props.error;
  return props.id ? errorStore.errors[props.id]?.[0] || null : null;
});

watch(() => props.modelValue, (newVal) => {
  const allOptions = [...(props.options || []), ...cachedOptions.value];
  selectedItem.value = allOptions.find(opt => opt[props.idKey || 'id'] == newVal) || null;
  if (selectedItem.value) emit('update:selectedOption', selectedItem.value);
}, { immediate: true });

watch(() => props.selectedOption, (newOption) => {
  if (newOption) {
    selectedItem.value = newOption;
    emit('update:modelValue', newOption[props.idKey || 'id']);
    if (!cachedOptions.value.some(opt => opt[props.idKey || 'id'] === newOption[props.idKey || 'id'])) {
      cachedOptions.value.unshift(newOption);
    }
  }
}, { immediate: true });

watch(search, async (newValue) => {
  if (props.apiUrl && newValue.length >= (props.minInput || 2)) getApiUrl(newValue);
  else if (!props.apiUrl) {
    const texto = newValue.toLowerCase();
    filteredOptions.value = (props.options || []).filter(opt =>
      opt[props.labelKey || 'name']?.toLowerCase().includes(texto)
    );
  } else filteredOptions.value = [];
});

async function getApiUrl(newValue: string) {
  if (abortController) abortController.abort();
  abortController = new AbortController();

  try {
    let params = { query: newValue, ...(props.params || {}) };
    const response = await axios.get(props.apiUrl, {
      params: params,
      signal: abortController.signal,
    });
    filteredOptions.value = response.data;
    cachedOptions.value = response.data;
  } catch (error: any) {
    if (error.name !== 'AbortError') console.error('Erro na busca:', error);
  }
}

watch(() => props.options, (novas) => {
  if (!props.apiUrl) filteredOptions.value = novas || [];
}, { immediate: true });

function openDropdown() {
  showDropdown.value = true;
  highlightedIndex.value = -1;
  if (props.selectedOpen && selectedItem.value) filteredOptions.value = [selectedItem.value];
  else if (!props.apiUrl) filteredOptions.value = props.options || [];
  nextTick(() => searchInput.value?.focus());
  if (props.minInput == 0) getApiUrl('');
}

function closeDropdown() {
  showDropdown.value = false;
  search.value = '';
}

function selectOption(option: OptionItem) {
  selectedItem.value = option;
  emit('update:modelValue', option[props.idKey || 'id']);
  emit('update:selectedOption', option);
  closeDropdown();
}

function clearSelection() {
  selectedItem.value = null;
  emit('update:modelValue', null);
  emit('update:selectedOption', null);
}

function navigateDown() {
  if (filteredOptions.value.length === 0) return;
  highlightedIndex.value = (highlightedIndex.value + 1) % filteredOptions.value.length;
  scrollHighlightedIntoView();
}

function navigateUp() {
  if (filteredOptions.value.length === 0) return;
  highlightedIndex.value = (highlightedIndex.value - 1 + filteredOptions.value.length) % filteredOptions.value.length;
  scrollHighlightedIntoView();
}

function selectHighlightedOption() {
  if (highlightedIndex.value >= 0 && highlightedIndex.value < filteredOptions.value.length) {
    selectOption(filteredOptions.value[highlightedIndex.value]);
  }
}

function scrollHighlightedIntoView() {
  nextTick(() => {
    const items = dropdownContainer.value?.querySelectorAll('.dropdown-item');
    if (items && highlightedIndex.value >= 0 && items[highlightedIndex.value]) {
      items[highlightedIndex.value].scrollIntoView({ block: 'nearest' });
    }
  });
}

function handleClickOutside(event: MouseEvent) {
  if (dropdownContainer.value && !dropdownContainer.value.contains(event.target as Node)) closeDropdown();
}

onMounted(() => document.addEventListener('click', handleClickOutside));
onUnmounted(() => {
  abortController?.abort();
  document.removeEventListener('click', handleClickOutside);
});
</script>

<template>
  <div :class="classWrapper" ref="dropdownContainer" class="relative">
    <!-- <label :for="id || name" class="block text-sm font-medium text-gray-300 mb-1" :title="labelText" @click.stop>
      <slot></slot>
      <span v-if="required" class="text-red-500 ml-1">*</span>
    </label> -->
    <label :for="props.id" class="block text-sm/6 font-medium text-gray-900 text-white"><slot></slot> <span v-if="required" class="text-red-400">*</span></label>

    <div class="flex items-center mt-2 rounded px-2 py-1 focus-within:ring-2 focus-within:ring-blue-500"
         :class="fieldError ? 'border-red-500' : 'border-gray-300'">
      <input
        :id="id || name"
        :name="name"
        type="text"
        :placeholder="displayPlaceholder"
        :value="selectedText"
        readonly
        @click="openDropdown"
        autocomplete="off"
        :required="required"
        :class="[
          'w-full rounded bg-white px-2 py-1',
          selectedText ? 'text-black' : 'text-gray-500 placeholder-gray-400',
          'focus:outline-none'
        ]"
      />
      <button v-if="clearable && selectedItem" type="button" @click="clearSelection" class="text-gray-400 hover:text-black ml-2">
        ✕
      </button>
    </div>

    <p v-if="fieldError" class="text-red-500 text-xs mt-1">{{ fieldError }}</p>

    <ul
      v-if="showDropdown"
      class="absolute z-10 mt-1 w-full bg-gray-800 border border-gray-600 rounded shadow max-h-60 overflow-y-auto"
      @keydown.tab="closeDropdown"
      @keydown.arrow-down.prevent="navigateDown"
      @keydown.arrow-up.prevent="navigateUp"
      @keydown.enter.prevent="selectHighlightedOption"
    >
      <li class="p-2">
        <input
          type="text"
          v-model="search"
          ref="searchInput"
          class="w-full bg-gray-700 text-white placeholder-gray-400 border border-gray-500 rounded px-2 py-1 focus:outline-none"
          placeholder="Pesquisar..."
        />
      </li>

      <template v-if="filteredOptions.length > 0">
        <li
          v-for="(option, index) in filteredOptions"
          :key="option[props.idKey || 'id']"
          @mousedown.prevent="() => selectOption(option)"
          :class="[
            'px-3 py-2 cursor-pointer text-white truncate',
            index === highlightedIndex || option[props.idKey || 'id'] === modelValue
              ? 'bg-blue-600'
              : 'hover:bg-gray-700'
          ]"
        >
          {{ option[props.labelKey || 'name'] }}
        </li>
      </template>

      <template v-else>
        <li v-if="!props.apiUrl" class="px-3 py-2 text-gray-400 truncate">Nenhum resultado encontrado.</li>
        <li v-else-if="search.length < (props.minInput || 2)" class="px-3 py-2 text-gray-400 truncate">
          Digite {{ props.minInput ?? 2 }} ou mais caracteres para buscar.
        </li>
        <li v-else class="px-3 py-2 text-gray-400 truncate">Nenhum resultado encontrado.</li>
      </template>
    </ul>
  </div>
</template>

<style scoped>
.dropdown-item.active {
  @apply bg-blue-600 text-white;
}
</style>
