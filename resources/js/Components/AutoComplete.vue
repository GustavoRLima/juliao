<script setup lang="ts">
import { ref, computed, watch, nextTick, onMounted, onUnmounted, useSlots } from 'vue';
import axios from 'axios';
import { useErrorStore } from '@/stores/errorShow';

// Tipagem para as opções do autocomplete
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
const touched = ref(false);

const search = ref('');
const selectedItem = ref<OptionItem | null>(null);
const filteredOptions = ref<OptionItem[]>([]);
const showDropdown = ref(false);
const searchInput = ref<HTMLInputElement | null>(null);
const dropdownContainer = ref<HTMLElement | null>(null);
const highlightedIndex = ref(-1);
const cachedOptions = ref<OptionItem[]>([]);
let abortController: AbortController | null = null;

// Computed
const selectedText = computed(() => selectedItem.value?.[props.labelKey || 'name'] || '');
const displayPlaceholder = computed(() => (selectedText.value ? '' : props.placeHolderProps));
const slots = useSlots();

const labelText = computed(() => {
  const defaultSlot = slots.default?.();
  if (defaultSlot && defaultSlot[0]) {
    const children = defaultSlot[0].children;
    if (typeof children === 'string') {
      return children;
    } else if (Array.isArray(children)) {
      return children.map(child => (typeof child === 'string' ? child : '')).join('');
    }
  }
  return '';
});

const fieldError = computed(() => {
  if (props.error) return props.error;
  return props.id ? errorStore.errors[props.id]?.[0] || null : null;
});

// Watchers
watch(
  () => props.modelValue,
  (newVal) => {
    const allOptions = [...(props.options || []), ...cachedOptions.value];
    selectedItem.value = allOptions.find(opt => opt[props.idKey || 'id'] == newVal) || null;
    if (selectedItem.value) {
      emit('update:selectedOption', selectedItem.value);
    }
  },
  { immediate: true }
);

watch(
  () => props.selectedOption,
  (newOption) => {
    if (newOption) {
      selectedItem.value = newOption;
      emit('update:modelValue', newOption[props.idKey || 'id']);
      if (!cachedOptions.value.some(opt => opt[props.idKey || 'id'] === newOption[props.idKey || 'id'])) {
        cachedOptions.value.unshift(newOption);
      }
    }
  },
  { immediate: true }
);

watch(search, async (newValue) => {
  if (props.apiUrl && newValue.length >= (props.minInput || 2)) {
    getApiUrl(newValue);
  } else if (!props.apiUrl) {
    const texto = newValue.toLowerCase();
    filteredOptions.value = (props.options || []).filter(opt =>
      opt[props.labelKey || 'name']?.toLowerCase().includes(texto)
    );
  } else {
    filteredOptions.value = [];
  }
});

async function getApiUrl(newValue: string)
{
  if (abortController) abortController.abort()
  abortController = new AbortController()

  try {
      let params = {
        query: newValue,
        ...(props.params ? props.params : {}),
      }
      const response = await axios.get(props.apiUrl, {
        params: params,
        signal: abortController.signal,
      })
      filteredOptions.value = response.data
      cachedOptions.value = response.data
    } catch (error: any) {
      if (error.name !== 'AbortError') console.error('Erro na busca:', error)
    }
}

watch(
  () => props.options,
  (novas) => {
    if (!props.apiUrl) {
      filteredOptions.value = novas || [];
    }
  },
  { immediate: true }
);

// Métodos
function openDropdown() {
  showDropdown.value = true;
  highlightedIndex.value = -1;

  // 🔥 Sempre mostrar o selecionado se existir
  if(props.selectedOpen){
    if (selectedItem.value) {
      filteredOptions.value = [selectedItem.value];
    } else if (!props.apiUrl) {
      filteredOptions.value = props.options || [];
    } else {
      filteredOptions.value = [];
    }
  }

  nextTick(() => {
    searchInput.value?.focus();
  });

  if(props.minInput == 0) {
    getApiUrl('');
  }
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
  if (dropdownContainer.value && !dropdownContainer.value.contains(event.target as Node)) {
    closeDropdown();
  }
}

onMounted(() => {
  document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
  abortController?.abort();
  document.removeEventListener('click', handleClickOutside);
});
</script>

<template>
  <div :class="classWrapper" ref="dropdownContainer">
    <div class="position-relative">
      <!-- Label -->
      <label :for="id || name" class="form-label mb-0 text-truncate" :title="labelText" @click.stop>
        <slot></slot>
        <span v-if="required" class="text-danger">*</span>
      </label>

      <div class="autocomplete-container">
        <div class="input-wrapper input-group">
          <span class="input-group-text" v-if="selectClass !== 'form-control-sm'">
            <i class="fa-solid fa-magnifying-glass"></i>
          </span>

          <input :id="id || name" :name="name" type="text" :placeholder="displayPlaceholder" :value="selectedText"
            readonly @click="openDropdown" autocomplete="off" :required="required"
            :class="['form-control', selectClass, { 'is-invalid': fieldError }]" />

          <span class="input-group-text" v-if="clearable && selectedItem" @click="clearSelection">
            &times;
          </span>

          <div v-if="fieldError" class="invalid-feedback">
            {{ fieldError }}
          </div>
        </div>
      </div>

      <ul v-if="showDropdown" class="dropdown-menu mt-1 show w-100 custom-dropdown" @keydown.tab="closeDropdown"
        @keydown.arrow-down.prevent="navigateDown" @keydown.arrow-up.prevent="navigateUp"
        @keydown.enter.prevent="selectHighlightedOption">
        <li class="p-2">
          <input type="text" v-model="search" ref="searchInput" :class="['form-control', selectClass]" />
        </li>

        <template v-if="filteredOptions.length > 0">
          <li v-for="(option, index) in filteredOptions" :key="option[props.idKey || 'id']"
            @mousedown.prevent="() => selectOption(option)"
            :class="['dropdown-item', { 'active': index === highlightedIndex || option[props.idKey || 'id'] === modelValue }]">
            {{ option[props.labelKey || 'name'] }}
          </li>
        </template>

        <!-- 🔥 Se NÃO houver nada para mostrar -->
        <template v-else>
          <li v-if="!props.apiUrl" class="dropdown-item text-muted w-100 text-truncate">
            Nenhum resultado encontrado.
          </li>
          <li v-else-if="search.length < (props.minInput || 2)" class="dropdown-item text-muted w-100 text-truncate">
            <span v-if="props.minInput > 0">Digite {{ minInput ?? '2' }} ou mais caracteres para buscar.</span>
          </li>
          <li v-else class="dropdown-item text-muted w-100 text-truncate">
            <span v-if="minInput == 0 && search.length == 0">Pesquisando, aguarde.</span>
            <span v-else-if="minInput != 0">Nenhum resultado encontrado.</span>
          </li>
        </template>
      </ul>
      
    </div>
  </div>
</template>

<style scoped>
.dropdown-item {
  cursor: pointer;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.dropdown-menu {
  max-height: 200px;
  overflow-y: auto;
}

.dropdown-item.active {
  background-color: #007bff;
  color: white;
}

label {
  display: block;
}
</style>
