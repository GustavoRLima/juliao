
<script setup lang="ts">
import Chaveamento from '@/Pages/Competicao/Chaveamento.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

const competitors = [
  'Lucas Fagner Santos - Cassão Team Janaúba',
  'Competidor 2',
  'Competidor 3',
  'Competidor 4',
  'Competidor 5',
  'Competidor 6',
  'Competidor 7',
  'Competidor 8',
  'Competidor 9',
  'Competidor 10',
  'Competidor 11',
  'Competidor 12',
  'Competidor 13',
  'Competidor 14',
  'Competidor 15',
]
</script>

<template>
    <Head title="Tabela Competição" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Tabela competição 
            </h2>
        </template>
        <Chaveamento :competitors="competitors" />
    </AuthenticatedLayout>
</template>
