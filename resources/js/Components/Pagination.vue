<script setup lang="ts">
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";
import type { IPagination } from "@/types/ipagination";

const props = defineProps<{
  pagination: IPagination;
}>();

const pages = computed(() => {
  const isHttps = window.location.protocol === "https:";
  return props.pagination.links.map((link) => ({
    ...link,
    url: link.url ? (isHttps ? link.url.replace("http://", "https://") : link.url) : null,
  }));
});
</script>

<template>
  <nav v-if="props.pagination.total > 0" aria-label="Page navigation" class="mt-6">
    <ul class="flex justify-end space-x-1">
      <li
        v-for="page in pages"
        :key="page.label"
      >
        <Link
          :href="page.url || '#'"
          v-html="page.label"
          class="px-3 py-2 rounded-md text-sm font-medium border transition-all duration-200"
          :class="[
            page.active
              ? 'bg-indigo-600 text-white border-indigo-600'
              : page.url
                ? 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border-gray-300 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700'
                : 'bg-gray-200 dark:bg-gray-700 text-gray-400 border-gray-200 dark:border-gray-600 cursor-not-allowed'
          ]"
        />
      </li>
    </ul>
  </nav>
</template>
