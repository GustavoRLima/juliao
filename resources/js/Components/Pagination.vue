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
  <nav v-if="props.pagination.total > 0" aria-label="Page navigation">
    <ul class="pagination justify-content-end mb-0">
      <li
        v-for="page in pages"
        :key="page.label"
        class="page-item"
        :class="{ active: page.active, disabled: !page.url }"
      >
        <Link
          class="page-link"
          :href="page.url || '#'"
          v-html="page.label"
        />
      </li>
    </ul>
  </nav>
</template>
